<?php

class EntityBuilder
{
    public SqlQueryBuilder $queryBuilder;
    public ReflectionClass $reflection;
    public CrudEntity $instance;

    public function set_instance(CrudEntity $instance): void
    {
        $this->instance = $instance;
        $this->queryBuilder = new SqlQueryBuilder();
        $this->reflection = new ReflectionClass($instance->get_name());
    }

    /**
     * @param array<string,mixed> $data
     */
    public function instanciate_all(array $data): array
    {
        if (array_key_exists("id", $data)) {
            throw new Exception("No id can be manually set", 500);
        }
        foreach($this->reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $e_property) {
            $prop_name = $e_property->getName();
            if (!array_key_exists($prop_name, $data)) {
                if ($e_property->hasDefaultValue()) {
                    $data[$prop_name] = $e_property->getDefaultValue();
                } else {
                    throw new Exception("Attribute \"$prop_name\" in \"".$this->instance->get_name()."\" is required and have no default values", 500);
                }
            }

            $entity_property_type = $this->rename_type($prop_name);
            $data[$prop_name] = $this->cast($data[$prop_name], $entity_property_type);
            $data_type = gettype($data[$prop_name]);
            if($data_type == 'object') {
                $data_type = get_class($data[$prop_name]);
            }
            if ($data_type != $entity_property_type) {
                throw new Exception(
                    "Property \"$prop_name\" must be of type \"$entity_property_type\". "
                    .$data_type." given.",
                    500
                );
            }
            $this->instance->$prop_name = $data[$prop_name];
        }
        $this->instance->check($data);
        return $data;
    }

    /**
     * @param array<string,mixed> $data
     */
    public function instanciate_with(array $data): array
    {
        $entity_properties = $this->reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $ep_format = [];
        foreach ($entity_properties as $ep) {
            $ep_format[$ep->getName()] = "";
        }
        // Remove private property of $entity listed in $data
        $data = array_intersect_key($data, $ep_format);

        foreach($data as $property => $d_value) {
            $entity_property_type = $this->rename_type($property);
            if (gettype($d_value) != $entity_property_type) {
                if ($cast = $this->cast($d_value, $entity_property_type)) {
                    $data[$property] = $cast;
                } else {
                    throw new Exception(
                        "Property \"$property\" must be of type \"$entity_property_type\". "
                        .gettype($d_value)." given.",
                        500
                    );
                }
                $this->instance->$property = $data[$property];
            }
        }
        $this->instance->check($data);
        return $data;
    }

    public function rename_type(string $property_name): string
    {
        $type = $this->reflection->getProperty($property_name)->getType() ;
        if (get_class($type) == "ReflectionNamedType") {
            switch ($type->getName()) {
                case "int":
                    $ret = "integer";
                    break;
                case "bool":
                    $ret = "boolean";
                    break;
                default:
                    $ret = $type->getName();
                    break;
            }
        } else {
            throw new Exception("Only ReflectionNamedType can be 'translated', ".get_class($type)." given", 500);
        }
        return $ret;
    }

    public function cast(mixed $data, string $target_type): mixed
    {
        $casted = $data;
        switch ($target_type) {
            case "DateTimeImmutable":
                $casted = DateTimeImmutable::createFromFormat("Y/m/d", htmlspecialchars($data));
                break;
            case "string":
                $casted = htmlspecialchars($data);
                break;
            default:
                ;
        }

        return $casted;
    }
}
