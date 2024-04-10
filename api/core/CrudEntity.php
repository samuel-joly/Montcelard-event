<?php

abstract class CrudEntity implements CrudEntityInterface
{
    protected Mysql $db;
    private ?int $id = 0;

    public function __construct()
    {
        $this->db = new Mysql();
    }

    abstract public function get_name(): string;

    abstract public function validate(): bool;

    public function get(array $query_params): Response
    {
        $query = SqlQueryBuilder::get($this, $query_params);
        foreach($query_params as $key => $val) {
            $query_params[$key] = $val[1];
        }
        $data = $this->db->queryPrepare($query,$query_params);
        return new Response($data, "GET events", 200);
    }

    public function post(array $data): Response
    {
        $query = SqlQueryBuilder::insert($this, $data);
        $query_values = [];
        foreach($data as $key => $value) {
            $query_values[$key] = SqlQueryBuilder::toSqlString($value);
        }
        $this->db->queryPrepare($query, $query_values);
        $last_insert_id = $this->db->query("Select max(id) as id from ".$this->get_name(), true)[0]["id"];
        $data = ["id" => $last_insert_id];
        return new Response($data, "POST ".$this->get_name(), 200);
    }

    public function put(array $data, int $id): Response
    {
        $query = SqlQueryBuilder::update($this, $data, $id);
        $query_values = [];
        foreach($data as $key => $value) {
            $query_values[$key] = SqlQueryBuilder::toSqlString($value);
        }
        $this->db->queryPrepare($query, $query_values);
        return new Response([], "PUT event", 200);
    }

    public function delete(int $id): Response
    {
        $data = $this->db->query("DELETE FROM ".$this->get_name()." WHERE id=".$id);
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
