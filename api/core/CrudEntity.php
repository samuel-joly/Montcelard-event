<?php

declare(strict_types=1);
include "mysql.php";

abstract class CrudEntity implements CrudEntityInterface
{
    abstract public function get_table_name(): string;
    /**
     ** @param array<string,mixed> $data
     */
    abstract public function check(array $data): bool;

    public function get(int $id = null): Response
    {
        $mysql = new Mysql();
        if ($id !== null) {
            $data = $mysql->query("Select * from ".$this->get_table_name()." where id=$id");
        } else {
            $data = $mysql->query("Select * from ".$this->get_table_name());
        }
        return new Response($data, "GET events", 200);
    }

    public function post(array $data): Response
    {
        EntityMaker::fill_all_properties($data, $this);
        $mysql = new Mysql();
        $base_sql = (new SqlQueryBuilder)->insert($this, $data);
        $data = $mysql->query($base_sql);
        if(gettype($data) == "boolean") {
            throw new Exception("SQL Error: ",500);
        } else {
            return new Response($data, "POST event", 200);
        }
    }

    public function put(array $data, int $id): Response
    {
        EntityMaker::fill_with_properties($data, $this);
        $mysql = new Mysql();
        $base_sql = (new SqlQueryBuilder)->insert($this, $data);
        $data = $mysql->query($base_sql);
        if(gettype($data) == "boolean") {
            throw new Exception("SQL Error: ",500);
        } else {
            return new Response($data, "POST event", 200);
        }
    }

    public function delete(int $id): Response
    {
        return new Response([], "DELETE event with id: $id", 200);
    }
}
