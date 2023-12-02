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
    public ?DateTimeImmutable $start_date = null;
    public ?DateTimeImmutable $end_date = null;
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

    public function check(array $data): bool
    {
        if(!isset($data["start_date"]) || !isset($data["end_date"])) {
            return false;
        }

        try {
            $start_date = DateTimeImmutable::createFromFormat("Y/m/d", $data["start_date"]);
            $end_date = DateTimeImmutable::createFromFormat("Y/m/d", $data["end_date"]);
        } catch (Exception $e) {
            throw new Exception(
                "Could not create DateTime from start_date or end_date, verify that you format the dates string as \"year/month/day\"",
                500,
                $e
            );
        }

        $this->start_date = $start_date;
        $this->end_date = $end_date;
        return true;
    }
}
