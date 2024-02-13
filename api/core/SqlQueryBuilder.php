<?php

class SqlQueryBuilder
{
    /**
     * @param array<string,mixed> $data
     */
    public function insert(CrudEntity $entity, array $data): string
    {
        $insert_statement = "INSERT INTO ".$entity->get_name();
        $property_list_start = "(";
        $property_list_end = ")";
        $value_list_start = "VALUES (";
        $value_list_end = ")";
        foreach($data as $property => $d) {
            if(array_key_first($data) != $property) {
                $value_list_start .= ",";
                $property_list_start .= ",";
            }
            $d = SqlQueryBuilder::cast_to_SQL_string($d);
            $property_list_start .= $property;
            $value_list_start .= $d;
        }
        $base_sql = $insert_statement
            ." " .$property_list_start . $property_list_end
            ." " .$value_list_start . $value_list_end;
        return $base_sql;
    }
    /**
     * @param array<string,mixed> $data
     */
    public function update(CrudEntity $entity, array $data, int $id): string
    {
        $insert_statement = "UPDATE ".$entity->get_name()." SET ";
        $update_list = "";
        $where_clause = " where id=$id";

        foreach($data as $property => $d) {
            if ($property == "id") {
                continue;
            }
            if($property != array_key_first($data)) {
                $update_list .= ",";
            }
            $d = SqlQueryBuilder::cast_to_SQL_string($d);
            $update_list .= $property."=".$d;
        }
        $base_sql = $insert_statement.$update_list.$where_clause;
        return $base_sql;
    }


    public function cast_to_SQL_string(mixed $to_cast): string
    {
        $sqlString = $to_cast;
        switch (gettype($to_cast)) {
        case "boolean":
            if($to_cast) {
                $sqlString = "true";
            } else {
                $sqlString = "false";
            }
            break;

        case "string":
            $sqlString = "'".htmlspecialchars($sqlString)."'";
            break;

        case "object":
            switch(get_class($to_cast)) {
            case "DateTimeImmutable":
                $format = date_format($to_cast, DateTimeInterface::ATOM);
                if ($format == false) { throw new Exception("Wrong date format when casting", 500);}
                $sqlString = "'".explode("+", str_replace("T", " ", $format))[0]."'";
                break;

            case "Room":
                $sqlString = "'".$to_cast->value."'";
                break;
            }
            break;

        }
        return $sqlString;
    }
}
