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
        foreach($this->reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $refl_prop) {
            $prop_name = $refl_prop->getName();
            if (!array_key_exists($prop_name, $data)) {
                if ($refl_prop->hasDefaultValue()) {
                    $data[$prop_name] = $refl_prop->getDefaultValue();
                } else {
                    throw new Exception("Attribute \"$prop_name\" in \"".$this->instance->get_name()."\" is required and have no default values", 500);
                }
            }

            $entity_property_type = $this->getPropType($prop_name);
            $data[$prop_name] = $this::cast($data[$prop_name], $entity_property_type);
            $data_type = gettype($data[$prop_name]);
            if($data_type == 'object') {
                $data_type = get_class($data[$prop_name]);
            }
            if ($data_type != $entity_property_type) {
                throw new Exception(
                    "Instanciate all error: Property '$prop_name' must be of type '$entity_property_type', '$data_type' given.",
                    500
                );
            }
            $this->instance->$prop_name = $data[$prop_name];
        }
        $this->instance->check();
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

        foreach($data as $prop_name => $prop_value) {
            $prop_type = $this->getPropType($prop_name);
            if ($prop_value == null){
               if ($ep_list[$prop_name]->hasDefaultValue() && $ep_list[$prop_name]->getDefaultValue() == null) {
                   $this->instance->$prop_name = $data[$prop_name];
               }
            } else {
                try {
                    $data[$prop_name] = $this::cast($prop_value, $prop_type);
                } catch(Exception $e) {
                    throw new Exception(
                        "Instanciate partial: Property \"$prop_name\" must be of type \"$prop_type\". "
                        .gettype($prop_value)." given.\n".$e->getMessage,
                        500
                    );
                }
                $this->instance->$prop_name = $data[$prop_name];
            }
        }
        $this->instance->check();
        return $data;
    }
    /**
     * @param array<int,string> $q_params
     */
    public function format_query_params(array &$q_params): void{
        $entity_attrs = [];
        $refl_attr = $this->reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($refl_attr as $refl) {
            $entity_attrs[$refl->getName()] = $refl;
        }
        foreach($q_params as $param => $value) {
            $operator = $param[strlen($param)-1];
            $param_str_arr = str_split($param);
            if ($operator == '<' || $operator  == '>') {
                unset($q_params[$param]);
                array_pop($param_str_arr);
                if($value[0] == "=") {
                    $operator .= "=";
                    $value = str_split($value);
                    unset($value[0]);
                    $value = implode($value);
                }
                $q_params[implode($param_str_arr)] = ["operator"=>$operator, "value"=>$value, "type"=>$this->getPropType(implode($param_str_arr))];
            } else {
                $q_params[$param] = ["operator"=>"=", "value"=>$value, "type"=>$this->getPropType($param)];
            }
        }

        $custom_query_attributes = array_diff_key($q_params, $this->instance->get_custom_query_attributes());
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

    static function cast(mixed $data, string $target_type): mixed
    {
        $casted = $data;
        switch ($target_type) {
            case "DateTimeImmutable":
                $casted = DateTimeImmutable::createFromFormat("Y-m-d", explode("T", $data)[0]);
                $casted = $casted->setTime(0,0,0,1);
                break;
            case "string":
                $casted = htmlspecialchars($data);
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
