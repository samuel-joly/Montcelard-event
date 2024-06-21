<?php
declare(strict_types=1);

class Reservation extends CrudEntity implements CrudEntityInterface
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
    public int $postItXl = 0;
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
        $this->schema = CrudEntity::create_entity_schema($this);
    }

    public function get_name(): string
    {
        return "reservation";
    }

    public function post(): Response {
        $this->check_overlapping();
        return parent::post();
    }

    public function check_overlapping(): bool {
        $query_params = [
            [
                "startDate" => ["<=", $this->startDate],
                "endDate" => [">=", $this->endDate],
                "roomId" => ["=", $this->roomId]
            ],
            [
                "startDate" => ["=", $this->startDate],
                "roomId" => ["=", $this->roomId] ,
            ],
            [
                "endDate" => ["=", $this->endDate],
                "roomId" => ["=", $this->roomId ],
            ],
            [
                "startDate" => ["=", $this->startDate],
                "endDate" => ["=", $this->endDate],
                "roomId" => ["=", $this->roomId] ,
            ],
        ];
        $overlapping_resa = ($this->get($query_params))->data;
        foreach($overlapping_resa as $key => $resa) {
            if ($resa["id"] == $this->get_id()) {
                unset($overlapping_resa[$key]);
            }
        }
        if (count($overlapping_resa) != 0)
        {
            throw new Exception(
                "Reservation '".$this->name."' cannot start the ".$this->startDate->format("Y-m-d").
                " because reservation '".$overlapping_resa[0]["name"]."' is already present that day", 200);
        } else {
            return true;
        }
    }

    public function validate(): bool
    {
        $this->check_overlapping();
        return true;
    }
}
