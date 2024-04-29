<?php
declare(strict_types=1);

class SqlQueryBuilder
{
    /**
     * @param array<string,mixed> $query_params
     */
    static public function get(CrudEntity $entity, ?array &$query_params): string {
        $qp = [];
        if($entity->get_id() != null && $query_params == null) {
            return "SELECT * FROM ".$entity->get_name()." WHERE id=".$entity->get_id();
        } else if($query_params != null && count($query_params) > 0 ) {
            $where_clause = " WHERE";
            $ending_clause = " ";
            foreach($query_params as $logic_nest => $query_param) {
                foreach($query_param as $attr => $op_val) {
                    if ($attr == "limit") {
                        $ending_clause .= " LIMIT ".htmlspecialchars($query_param[1]); 
                        unset($query_params[$logic_nest][$attr]);
                    } else {
                        if(!isset($op_val[2])) {
                            $op_val[2] = "AND";
                        }       
                        if(array_key_first($query_params) == $logic_nest && array_key_first($query_param) == $attr) {
                            $where_clause .= " $attr ".$op_val[0]." :$attr".$logic_nest;
                        } else {
                            $where_clause .= " ".$op_val[2]." $attr ".$op_val[0]." :$attr".$logic_nest;
                        }
                    }
                    $qp[$attr.$logic_nest] = $op_val[1];
                }
            }
            $query_params = $qp;
            return "SELECT * FROM ".$entity->get_name().$where_clause.$ending_clause;
        }else {
            return "SELECT * FROM ".$entity->get_name();
        }
    }

    static public function insert(CrudEntity $entity): string
    {
        $insert_statement = "INSERT INTO ".$entity->get_name();
        $property_list_start = "(";
        $property_list_end = ")";
        $value_list_start = "VALUES (";
        $value_list_end = ")";
        foreach($schema = $entity->get_schema() as $property => $key) {
            if(array_key_first($schema) != $property) {
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
     * @param array<string,mixed> $properties
     */
    static public function update(CrudEntity $entity, array $properties): string
    {
        if(count($properties) == 0) { throw new Error("Require at least one attribute to update", 200); }
        $insert_statement = "UPDATE ".$entity->get_name()." SET ";
        $update_list = "";
        $where_clause = " where id=".$entity->get_id();

        foreach($properties as $property => $d) {
            if($property != array_key_first($properties)) {
                $update_list .= ",";
            }
            $update_list .= $property ."= :". $property;

        }
        $base_sql = $insert_statement.$update_list.$where_clause;
        return $base_sql;
    }


    static public function cast_for_pdo(mixed $to_cast): mixed 
    {
        $casted = $to_cast;
        switch (gettype($to_cast)) {
        case "boolean":
            if($to_cast) {
                $casted = "1";
            } else {
                $casted = "0";
            }
            break;

        case "object":
            switch(get_class($to_cast)) {
            case "DateTimeImmutable":
                $format = date_format($to_cast, DateTimeInterface::ATOM);
                if ($format == false) { throw new Exception("SQLQueryBuilder: Wrong date format when casting DateTimeImmutable", 500);}
                $casted = explode("+", str_replace("T", " ", $format))[0];
                break;
            }
            break;
        default:
            $casted = $to_cast;
            break;
        }
        return $casted;
    }
}
