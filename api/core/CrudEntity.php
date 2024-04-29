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
     * @param array<string,mixed> $data
     */
    public function constructFromArray(array $data): void {
        $reflection = new ReflectionClass($this->get_name());
        $entity_properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach($entity_properties as $reflection_property) {
            $prop_name = $reflection_property->getName();
            if (!array_key_exists($prop_name, $data) ) {
                if ($reflection_property->hasDefaultValue()) {
                    $data[$prop_name] = $reflection_property->getDefaultValue();
                } else {
                    throw new Error("Attribute '$prop_name' is required in entity '".$this->get_name()."'", 200);
                }
            } 
            $prop_type = $reflection_property->getType()."";
            $data[$prop_name] = Helper::tryCast($data[$prop_name], $prop_type);
            $this->$prop_name = $data[$prop_name];
        }
        $this->validate();
    }

    /**
     * @param array<string,mixed> $q_params
     * @return array<int,array<string, mixed>>
     */
    public function parseQueryParams(array $q_params): array{
        $qp = [];
        $entity_attrs = [];
        $logic_nest = 0;
        foreach($q_params as $value) {
            $attr = $value[0];
            $attr_str_arr = str_split($attr);
            $operator = implode(array_slice($attr_str_arr, count($attr_str_arr)-3, count($attr_str_arr)));
            $logical_operator = $value[0][0];

            if($logical_operator == "|") {
                $attr = implode(array_slice($attr_str_arr, 1,count($attr_str_arr)));
                $logical_operator = "OR";
                $logic_nest+=1;
            } else {
                $logical_operator = "AND";
            }
            
            if ($operator == '%3C' || $operator  == '%3E') {
                array_pop($attr_str_arr);
                array_pop($attr_str_arr);
                array_pop($attr_str_arr);
                if($operator == "%3C") $operator = "<";
                if($operator == "%3E") $operator = ">";
                if($value[1][0] == "=") {
                    $operator .= "=";
                    $value[1] = str_split($value[1]);
                    unset($value[1][0]);
                    $value[1] = implode($value[1]);
                }

                if(implode($attr_str_arr) == "id") {
                    $this->set_id($value[1]);
                } else {
                    $type = $this->schema[implode($attr_str_arr)];
                    $casted = Helper::tryCast(array_values($value)[count($value)-1], $type);
                    $qp[$logic_nest][implode($attr_str_arr)] = [$operator,$casted,$logical_operator];
                }
            } else if($attr_str_arr[count($attr_str_arr)-1] == "!") {
                array_pop($attr_str_arr);
                $attr = implode($attr_str_arr);
                $operator = "!=";
                $type = $this->schema[$attr];
                $value[1] = Helper::tryCast($value[1], $type);
                $qp[$logic_nest][$attr] = [$operator,$value[1],$logical_operator];
            } else {
                if($attr == "id") {
                    $this->set_id((int)$value[1]);
                } else {
                    $type = $this->schema[$attr];
                    $value[1] = Helper::tryCast($value[1], $type);
                    $qp[$logic_nest][$attr] = ["=", $value[1], $logical_operator];
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
