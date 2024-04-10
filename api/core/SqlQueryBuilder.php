<?php

class SqlQueryBuilder
{
    /**
     * @param array<string,mixed> $query_params
     */
    static public function get(CrudEntity $entity, array &$query_params): string {
        $where_clause = " WHERE";
        $ending_clause = " ";
        foreach($query_params as $attr => $op_val) {
            if (array_key_exists($attr, ["limit"=>"", "order"=>""])) {
                switch ($attr) {
                case "limit":
                    $ending_clause .= " LIMIT ".htmlspecialchars($op_val[1]); 
                    break;
                case "order":
                    throw new Exception("SQL Order in query params is not supported", 500);
                }
                unset($query_params[$attr]);
            } else {
                $where_clause .= " $attr ".$op_val[0]." :$attr";
                if (array_key_last($query_params) != $attr) {
                    $where_clause .= " AND";
                }
            }
        }
        return "SELECT * FROM ".$entity->get_name().$where_clause.$ending_clause;
    }

    /**
     * @param array<string,mixed> $data
     */
    static public function insert(CrudEntity $entity, array $data): string
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
            $property_list_start .= $property;
            $value_list_start .= ":".$property;
        }
        $insert_statement = $insert_statement
            ." " .$property_list_start . $property_list_end
            ." " .$value_list_start . $value_list_end;
        return $insert_statement;
    }
    /**
     * @param array<string,mixed> $data
     */
    static public function update(CrudEntity $entity, array $data, int $id): string
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
            $update_list .= $property ."= :". $property;

        }
        $base_sql = $insert_statement.$update_list.$where_clause;
        return $base_sql;
    }


    static public function toSqlString(mixed $to_cast): string | null
    {
        $sqlString = $to_cast;
        switch (gettype($to_cast)) {
        case "boolean":
            if($to_cast) {
                $sqlString = "1";
            } else {
                $sqlString = "0";
            }
            break;

        case "object":
            switch(get_class($to_cast)) {
            case "DateTimeImmutable":
                $format = date_format($to_cast, DateTimeInterface::ATOM);
                if ($format == false) { throw new Exception("SQLQueryBuilder: Wrong date format when casting DateTimeImmutable", 500);}
                $sqlString = explode("+", str_replace("T", " ", $format))[0];
                break;
            }
            break;

        }
        return $sqlString;
    }
}
