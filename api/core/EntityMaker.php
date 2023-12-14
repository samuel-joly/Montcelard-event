<?php

abstract class EntityMaker
{
    /**
     * @param array<string,mixed> $data
     */
    public static function fill_with_properties(array $data, CrudEntity $entity): void
    {
        $entity_name = $entity->get_table_name();
        $data_properties = array_keys($data);
        $entity_properties = get_class_vars($entity_name);
        foreach($data_properties as $d_property) {
            if (!array_key_exists($d_property, $entity_properties)) {
                throw new Exception("Attribute \"$d_property\" not found in \"".$entity_name."\"", 500);
            } else {
                $entity_property_type = EntityMaker::get_entity_property_type($d_property, $entity_name);
                if (gettype($data[$d_property]) != $entity_property_type) {
                    if ($cast = EntityMaker::cast($data[$d_property], $entity_property_type)) {
                        $data[$d_property] = $cast;
                    } else {
                        throw new Exception(
                            "Property \"$d_property\" must be of type \"$entity_property_type\". "
                            .gettype($data[$d_property])." given.",
                            500
                        );
                    }
                }
                $entity->$d_property = $data[$d_property];
            }
        }
        $entity->check($data);
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fill_all_properties(array $data, CrudEntity $entity): void
    {
        $entity_name = $entity->get_table_name();
        $entity_properties = get_class_vars($entity_name);
        foreach($entity_properties as $e_property => $default_value) {
            if (!array_key_exists($e_property, $data)) {
                if($default_value === null) {
                    throw new Exception("Attribute \"$e_property\" in \"".$entity_name."\" is required and have no default values" , 500);
                } else {
                    $data[$e_property] = $default_value;
                }
            } 

            $entity_property_type = EntityMaker::get_entity_property_type($e_property, $entity_name);
            if (gettype($data[$e_property]) != $entity_property_type) {
                if ($cast = EntityMaker::cast($data[$e_property], $entity_property_type)) {
                    $data[$e_property] = $cast;
                } else {
                    throw new Exception(
                        "Property \"$e_property\" must be of type \"$entity_property_type\". "
                        .gettype($data[$e_property])." given.",
                        500
                    );
                }
            }
            $entity->$e_property = $data[$e_property];
        }
        $entity->check($data);
    }

    public static function get_entity_property_type(string $property, string $entity_name): string
    {
        $property_type = (new ReflectionProperty($entity_name, $property))->getType()->getName();
        switch ($property_type) {
            case "int":
                $property_type = "integer";
                break;
            case "bool":
                $property_type = "boolean";
                break;
        }
        return $property_type;
    }

    public static function cast(mixed $data, string $target_type): mixed
    {
        switch ($target_type) {
            case "DateTimeImmutable":
                $casted = DateTimeImmutable::createFromFormat("Y/m/d", htmlspecialchars($data));
                break;
            default:
                $casted = false;
                break;
        }
        return $casted;
    }
}
