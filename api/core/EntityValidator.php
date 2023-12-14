<?php

abstract class EntityValidator
{
    /**
     * @param array<string,mixed> $data
     */
    public static function validate(array $data, CrudEntity $entity): void
    {
        $entity_name = $entity->get_table_name();
        $properties = get_class_vars($entity_name);
        foreach($properties as $property => $value) {
            if ($value !== null) {
                if (!array_key_exists($property, $data)) {
                    throw new Exception("Attribute \"$property\" needs to be defined", 500);
                } else {
                    $property_type = (new ReflectionProperty($entity_name, $property))->getType()->getName();
                    if ($property_type == "int") { $property_type="integer";}
                    else if ($property_type == "DateTimeImmutable") {
                        $property_type = "string";
                    }
                    if (gettype($data[$property]) != $property_type) {
                        throw new Exception("Property \"$property\" must be of type \"$property_type\". ".gettype($data[$property])." given.", 500);
                    } else {
                        $entity->$property = $data[$property];
                    }
                }
            }
        }
        if ($entity->check($data) !== true) {
            throw new Exception("Error during entity validation, check your request", 500);
        }
    }
}
