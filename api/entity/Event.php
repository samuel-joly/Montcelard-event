<?php
declare(strict_types=1);

enum RoomConfiguration
{
    case U;
    case Circle;
    case Board;
    case Theatre;
    case School;
}

class Event extends CrudEntity implements CrudEntityInterface
{
    public string $start_date;
    public string $end_date;
    public string $start_hour;
    public string $end_hour;
    public int $pause_date;
    public string $start_hour_offset;
    public string $end_hour_offset;
    public int $guests;
    public string $host;
    public string $formation_name;
    public string $room_config;
    public string $config_option;
    public string $orga_mail;
    public string $orga_tel;
    public string $orga_name;

    public function get_table_name(): string
    {
        return "event";
    }

    public function check(): bool {
        return true;
    }
}
