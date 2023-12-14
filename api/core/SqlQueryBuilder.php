<?php

class SqlQueryBuilder
{
    /**
     * @param array<string,mixed> $data
     */
    public function insert(CrudEntity $entity, array $data): string
    {
        $insert_statement = "INSERT INTO ".$entity->get_table_name();
        $property_list_start = "(";
        $property_list_end = ")";
        $value_list_start = "VALUES (";
        $value_list_end = ")";

        foreach($data as $property => $d) {
            if(array_key_first($data) != $property) {
                $value_list_start .= ",";
                $property_list_start .= ",";
            }
            if($d instanceof DateTimeImmutable) {
                $d = date_format($d, DateTimeInterface::ATOM);
                $d = "'".explode("+", str_replace("T", " ", $d))[0]."'";
            } elseif (gettype($d) == "boolean") {
                if($d) {
                    $d = "true";
                } else {
                    $d = "false";
                }
            } elseif (gettype($d) == "string") {
                $d = "'$d'";
            }
            $property_list_start .= $property;
            $value_list_start .= $d;
        }
        $base_sql = $insert_statement
                    ." " .$property_list_start . $property_list_end
                    ." " .$value_list_start . $value_list_end;
        return $base_sql;
    }
}
