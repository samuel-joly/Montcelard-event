<?php

abstract class CrudEntity implements CrudEntityInterface
{
    private Mysql $db;
    private ?int $id = 0;

    public function __construct()
    {
        $this->db = new Mysql();
    }

    abstract public function get_name(): string;

    /**
     ** @param array<string,mixed> $data
     */
    abstract public function check(array $data): bool;


    public function get(int $id = null): Response
    {
        if ($id !== null) {
            $data = $this->db->query("Select * from ".$this->get_name()." where id=$id");
        } else {
            $data = $this->db->query("Select * from ".$this->get_name());
        }
        return new Response($data, "GET events", 200);
    }

    public function post(array $data): Response
    {
        $base_sql = (new SqlQueryBuilder())->insert($this, $data);
        $this->db->query($base_sql);
        $last_insert_id = $this->db->query("Select max(id) as id from ".$this->get_name(), true)[0]["id"];
        $this->set_id($last_insert_id);
        $data = [];
        $data = ["id" => $last_insert_id];
        return new Response($data, "POST ".$this->get_name(), 200);
    }

    public function put(array $data, int $id): Response
    {
        $base_sql = (new SqlQueryBuilder())->update($this, $data, $id);
        $data = $this->db->query($base_sql);
        return new Response($data, "PUT event", 200);
    }

    public function delete(int $id): Response
    {
        if (sizeof($this->get($id)->data) > 0) {
            $data = $this->db->query("DELETE FROM ".$this->get_name()." WHERE id=".$id);
            return new Response($data, "DELETE ".$this->get_name(), 200);
        } else {
            throw new Exception("No ".$this->get_name()." with id=".$id." found", 500);
        }
    }

    public function get_id(): ?int
    {
        return $this->id;
    }

    public function set_id(int $id): void
    {
        $this->id = $id;
    }
}
