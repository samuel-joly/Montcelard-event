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
    public string $name;
    public int $guests;
    public string $host_name;

    public DateTimeImmutable $start_date;
    public DateTimeImmutable $end_date;

    public string $start_hour;

    public string $orga_mail;
    public string $orga_tel;
    public string $orga_name;

    public string $room_configuration;
    public int $configuration_size=0;
    public int $configuration_quantity=0;
    public string $end_hour;

    public string $room_configuration_precision = "";
    public int $pause_date = 0;
    public string $start_hour_offset = "";
    public string $end_hour_offset = "";

    public bool $host_table=true;
    public int $paperboard = 0;
    public int $chair_sup = 0;
    public int $table_sup = 0;

    public bool $pen = false;
    public bool $paper = false;
    public bool $scissors = false;
    public bool $scotch = false;
    public bool $post_it_xl = false;
    public bool $paper_a1 = false;
    public bool $bloc_note = false;
    public bool $gomette = false;
    public bool $post_it = false;

    public int $coffee_groom = 0;
    public int $meal = 0;
    public int $morning_coffee = 0;
    public int $afternoon_coffee = 0;
    public int $coktail = 0;
    public int $vegetarian = 0;
    public int $gluten_free = 0;
    public string $meal_precision = "";

    public function get_name(): string
    {
        return Entity::event->value;
    }

    public function check(array $data): bool
    {
        return true;
    }
}
