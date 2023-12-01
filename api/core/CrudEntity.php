<?php

declare(strict_types=1);
include "mysql.php";

abstract class CrudEntity implements CrudEntityInterface
{
    abstract function get_table_name(): string;
    abstract function check(): bool;

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
        EntityValidator::validate($data,$this);
        return new Response([], "POST event", 200);
    }

    public function put(array $data, int $id): Response
    {
        return new Response([], "PUT event", 200);
    }

    public function delete(int $id): Response
    {
        return new Response([], "DELETE event with id: $id", 200);
    }
}
