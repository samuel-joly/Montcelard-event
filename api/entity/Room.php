<?php

declare(strict_types=1);


class Room extends CrudEntity{
    public string $name;

    public function __construct(string $name) {
        if ($name != "") {
            $this->name = $name;
        } else {
            throw new Exception("Room cannot have an empty name");
        }
    }

    public function __toString(): string {
        return $this->name;
    }

    public function get_name(): string
    {
        return "Room";
    }

    public function validate(): bool
    {
        if ($this->name == "") {
            return false;
        } 
        return true;
    }
}
