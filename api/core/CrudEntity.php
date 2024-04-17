<?php
declare(strict_types=1);

abstract class CrudEntity implements CrudEntityInterface
{
    protected Mysql $db;
    private ?int $id = null;
    protected ?array $schema = null;

    public function __construct()
    {
        $this->db = new Mysql();
    }

    abstract public function get_name(): string;

    abstract public function validate(): bool;
    /**
     * @param array<string,mixed> $body
     */
    public function constructFromArray(array $body): void {
        $reflection = new ReflectionClass($this->get_name());
        $entity_properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach($entity_properties as $reflection_property) {
            $prop_name = $reflection_property->getName();
            if (!array_key_exists($prop_name, $body) ) {
                if ($reflection_property->hasDefaultValue()) {
                    $body[$prop_name] = $reflection_property->getDefaultValue();
                }
            } 
            $prop_type = $reflection_property->getType()."";
            $body[$prop_name] = Helper::tryCast($body[$prop_name], $prop_type);
            $this->$prop_name = $body[$prop_name];
        }
        $this->validate();
    }

    /**
     * @param array<string,mixed> $q_params
     * @return array<string,mixed>
     */
    public function parseQueryParams(array $q_params): array{
        $qp = [];
        $entity_attrs = [];
        foreach($q_params as $attr => $value) {
            $operator = $attr[strlen($attr)-1];
            $param_str_arr = str_split($attr);
            if ($operator == '<' || $operator  == '>') {
                array_pop($param_str_arr);
                if($value[0] == "=") {
                    $operator .= "=";
                    $value = str_split($value);
                    unset($value[0]);
                    $value = implode($value);
                }
                if(implode($param_str_arr) == "id") {
                    $this->set_id($value);
                } else {
                    $type = $this->schema[implode($param_str_arr)];
                    $cast = Helper::tryCast($value, $type);
                    $qp[implode($param_str_arr)] = [$operator,$value];
                }
            } else {
                if($attr == "id") {
                    $this->set_id((int)$value);
                } else {
                    $type = $this->schema[$attr];
                    $cast = Helper::tryCast($value, $type);
                    $qp[$attr] = ["=", $value];
                }
            }
        }
        return $qp;
    }
    /**
     * @return array<string,string>
     */
    static function create_entity_schema(CrudEntity $entity): array {
        $reflection = new ReflectionClass($entity->get_name());
        $entity_props = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $schema = [];
        foreach ($entity_props as $prop) {
            $type = $prop->getType();
            $schema[$prop->name] = $type."";
        }
        return $schema;
    }

    public function get_schema(): array|null {
        return $this->schema;
    }

    public function get(array $query_params): Response
    {
        $query = SqlQueryBuilder::get($this, $query_params);
        $data = $this->db->queryPrepare($query,$query_params);
        return new Response($data, "GET ".$this->get_name(), 200);
    }

    public function to_array(): array {
        $ret = [];
        foreach($this->schema as $attr => $type) {
            $ret[$attr] = $this->$attr;
        } 
        return $ret;
    }

    public function post(): Response
    {
        $query = SqlQueryBuilder::insert($this);
        $this->db->queryPrepare($query, $this->to_array());
        $last_insert_id = $this->db->query("Select max(id) as id from ".$this->get_name(), true)[0]["id"];
        $data = ["id" => $last_insert_id];
        return new Response($data, "POST ".$this->get_name(), 200);
    }

    public function put(array $properties): Response
    {
        $query = SqlQueryBuilder::update($this, $properties);
        $data = [];
        foreach ($properties as $key => $value) {
            $data[$key] = $this->$key;
        }
        $this->db->queryPrepare($query, $data);
        return new Response([], "PUT ".$this->get_name(), 200);
    }

    public function delete(): Response
    {
        $data = $this->db->query("DELETE FROM ".$this->get_name()." WHERE id=".$this->get_id());
        return new Response($data, "DELETE ".$this->get_name(), 200);
    }

    public function get_id(): ?int
    {
        return $this->id;
    }

    public function set_id(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return array<string,string>
     */
    static function get_custom_query_attributes(): array {
        return [
            "id" => "integer",
            "limit" => "integer",
            "order" => "string"
        ];
    }
}
