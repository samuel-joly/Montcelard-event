<?php
declare(strict_types=1);

class Helper
{
    static function tryCast(mixed $data, string $target_type): mixed
    {
        $casted = $data;
        switch ($target_type) {
        case "DateTimeImmutable":
            $casted = DateTimeImmutable::createFromFormat("Y-m-d", explode("T", $data)[0]);
            $casted = $casted->setTime(0,0,0,1);
            break;
        case "Room":
            $casted = new Room($data);
            break;
        case "bool":
            if(gettype($data) == "integer") {
                $casted = $data ? true : false;
            }
            break;
        default:
            $casted = $data;
            ;
        }
        return $casted;
    }
}
