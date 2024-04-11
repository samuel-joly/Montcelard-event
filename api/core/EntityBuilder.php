<?php

class EntityBuilder
{
    public ReflectionClass $reflection;
    public CrudEntity $instance;

    public function set_instance(CrudEntity $instance): void
    {
        $this->instance = $instance;
        $this->reflection = new ReflectionClass($instance->get_name());
    }

    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function instanciate_all(array $data): array
    {
        if (array_key_exists("id", $data)) {
            throw new Exception("No id can be manually set", 500);
        }
        $refl_props = $this->reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach($refl_props as $refl_prop) {
            $prop_name = $refl_prop->getName();
            if (!array_key_exists($prop_name, $data)) {
                if ($refl_prop->hasDefaultValue()) {
                    $data[$prop_name] = $refl_prop->getDefaultValue();
                } else {
                    throw new Exception("Attribute \"$prop_name\" in entity \"".$this->instance->get_name()."\" is required", 500);
                }
            }

            $fmt_prop_type = $this->getPropType($prop_name);
            $data[$prop_name] = $this::customCast($data[$prop_name], $fmt_prop_type);
            $data_type = gettype($data[$prop_name]);
            if($data_type == 'object') {
                $data_type = get_class($data[$prop_name]);
            }
            if ($data_type != $fmt_prop_type) {
                throw new Exception(
                    "Instanciate all error: Property '$prop_name' must be of type '$fmt_prop_type', '$data_type' given.",
                    500
                );
            }
            $this->instance->$prop_name = $data[$prop_name];
        }
        $this->instance->validate();
        return $data;
    }

    /**
     * @param array<string,mixed> $data
     */
    public function instanciate_partial(array $data): array
    {
        $refl_props = $this->reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $ep_list = [];
        foreach ($refl_props as $refl_prop) {
            $ep_list[$refl_prop->getName()] = $refl_prop;
        }
        // Remove any non-public entity property from $data
        $data = array_intersect_key($data, $ep_list);

        foreach($data as $name => $value) {
            $prop_type = $this->getPropType($name);
            if ($value == null){
                if ($ep_list[$name]->hasDefaultValue() && $ep_list[$name]->getDefaultValue() == null) {
                    $this->instance->$name = $data[$name];
                }
            } else {
                try {
                    $data[$name] = $this::customCast($value, $prop_type);
                } catch(Exception $e) {
                    throw new Exception(
                        "Instanciate partial: Property \"$name\" must be of type \"$prop_type\". "
                        .gettype($value)." given.\n".$e->getMessage(),
                        500
                    );
                }
                $this->instance->$name = $data[$name];
            }
        }
        $this->instance->validate();
        return $data;
    }
    /**
     * @param array<string,string> $q_params
     */
    public function format_query_params(array &$q_params): void{
        $entity_attrs = [];
        foreach($q_params as $attr => $value) {
            $operator = $attr[strlen($attr)-1];
            $param_str_arr = str_split($attr);
            if ($operator == '<' || $operator  == '>') {
                unset($q_params[$attr]);
                array_pop($param_str_arr);
                if($value[0] == "=") {
                    $operator .= "=";
                    $value = str_split($value);
                    unset($value[0]);
                    $value = implode($value);
                }
                $type = $this->getPropType(implode($param_str_arr));
                $cast = EntityBuilder::customCast($value, $type);
                $q_params[implode($param_str_arr)] = [$operator,$value];
            } else {
                $type = $this->getPropType($attr);
                $cast = EntityBuilder::customCast($value, $type);
                $q_params[$attr] = ["=", $value];
            }
        }

        $custom_query_attributes = array_diff_key($q_params, $this->instance->get_custom_query_attributes());
        $refl_attr = $this->reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($refl_attr as $refl) {
            $entity_attrs[$refl->getName()] = $refl;
        }
        $attr_compare = array_diff_key($custom_query_attributes, $entity_attrs);
        if (count($attr_compare) != 0) {
            $bad_props = "";
            foreach ($attr_compare as $prop_name => $value) {
                $bad_props.=" '".$value."', ";
            }
            throw new Exception("EntityBuilder: '".$this->instance->get_name()."' does not contains following properties:".$bad_props);
        }
    }

    public function getPropType(string $property_name): string
    {
        $ret = "";
        if($this->reflection->hasProperty($property_name)) {
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
                throw new Exception("UnionType cannot be used for property ".$property_name, 500);
            }
        } else {
            if (array_key_exists($property_name,$this->instance->get_custom_query_attributes())) {
                $ret = $this->instance->get_custom_query_attributes()[$property_name];
            }
        }
        if ($ret == "") {
            throw new Exception("No attribute named '$property_name' on entity ".$this->instance->get_name(),500);
        }
        return $ret;
    }

    static function customCast(mixed $data, string $target_type): mixed
    {
        $casted = $data;
        switch ($target_type) {
        case "DateTimeImmutable":
            $casted = DateTimeImmutable::createFromFormat("Y-m-d", explode("T", $data)[0]);
            $casted = $casted->setTime(0,0,0,1);
            break;
        case "string":
            $casted = $data;
            break;
        case "Room":
            $casted = new Room($data);
            break;
        default:
            ;
        }
        return $casted;
    }
}
