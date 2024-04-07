<?php

declare(strict_types=1);

class Event extends CrudEntity implements CrudEntityInterface
{
    public ?int $roomId = null;
    public string $name;
    public int $guests;
    public string $hostName;

    public DateTimeImmutable $startDate;
    public DateTimeImmutable $endDate;

    public string $startHour;

    public string $orgaMail;
    public string $orgaTel;
    public string $orgaName;

    public string $roomConfiguration;
    public int $configurationSize;
    public ?int $configurationQuantity = null;
    public string $endHour;

    public string $roomConfigurationPrecision = "";
    public int $pauseDate = 0;
    public string $startHourOffset = "";
    public string $endHourOffset = "";

    public bool $hostTable=true;
    public int $paperboard = 0;
    public int $chairSup = 0;
    public int $tableSup = 0;

    public bool $pen = false;
    public bool $paper = false;
    public bool $scissors = false;
    public bool $scotch = false;
    public bool $postItXl = false;
    public bool $paperA1 = false;
    public bool $blocNote = false;
    public bool $gomette = false;
    public bool $postIt = false;

    public int $coffeeGroom = 0;
    public int $meal = 0;
    public int $morningCoffee = 0;
    public int $afternoonCoffee = 0;
    public int $coktail = 0;
    public int $vegetarian = 0;
    public int $glutenFree = 0;
    public string $mealPrecision = "";

    public function __construct() {
        parent::__construct();
    }
    public function get_name(): string
    {
        return Entity::event->value;
    }

    public function check(): bool
    {
        return true;
    }

    /* public function get(array $query_params): Response */
    /* { */
    /*     $startOfWeek=""; */
    /*     if (array_key_exists("id", $query_params)) { */
    /*         $data = $this->db->query("Select * from ".$this->get_name()." where id=".$query_params["id"]); */
    /*     } else { */
    /*         if (array_key_exists("week", $query_params) && array_key_exists("year", $query_params)) { */
    /*             $startOfWeek=DateTime::createFromFormat('Y z', $query_params["year"]. ' ' .$query_params["week"]*7  ); */
    /*             $endOfWeek=DateTime::createFromFormat('Y z', $query_params["year"]. ' ' .($query_params["week"]*7)+5  ); */

    /*             $query = "Select * from ".$this->get_name() */
    /*                 ." where startDate >= '".explode(" ", date_format($startOfWeek, "Y-m-d"))[0] */
    /*                 ."' and endDate <= '".explode(" ", date_format($endOfWeek, "Y-m-d"))[0] */
    /*                 ."'"; */
    /*             $data = $this->db->query($query); */
    /*         } */
    /*     } */
    /*     return new Response($data, "GET events", 200); */
    /* } */

    static function get_custom_query_attributes(): array {
        $parent_attr = parent::get_custom_query_attributes();
        $parent_attr["week"] = null;
        $parent_attr["year"] = null;
        return $parent_attr;
    }
}
