<?php

class Router
{
    public array $routes;
    public EntityBuilder $eb;

    public function __construct()
    {
        $this->routes = [];
        $this->eb = new EntityBuilder();
    }

    public function add(string $route, CrudEntity $entity_class): void
    {
        if (!array_key_exists($route, $this->routes)) {
            $this->routes[$route] = $entity_class;
        } else {
            throw new Exception("Route \"$route\" is already defined", 500);
        }
    }

    public function route(Request $req): Response
    {
        $entity_name = $req->getOptions()["entity"];
        if (!array_key_exists($entity_name, $this->routes)) {
            throw new Exception("No crud on entity named \"".$entity_name."\"", 400);
        }
        $this->eb->set_instance($this->routes[$entity_name]);
        $entity = $this->eb->instance;
        $user_data = $req->getBody();
        $res = "";
        switch($req->getMethod()) {
            case RequestMethod::GET:
                if (array_key_exists("id", $req->getOptions())) {
                    $res = $entity->get($req->getOptions()["id"]);
                } else {
                    $res = $entity->get();
                }
                if (array_key_exists("schema", $req->getOptions())) {
                    $entity_type = $this->eb->reflection->getProperties(ReflectionProperty::IS_PUBLIC);
                    $formated_type = [];
                    foreach ($entity_type as $property_type) {
                        $formated_type[$property_type->name] = $this->eb->rename_type($property_type->name);
                    }
                    array_push($res->data , $formated_type);
                }
                break;

            case RequestMethod::POST:
                $user_data = $this->eb->instanciate_all($user_data);
                $res = $entity->post($user_data);
                break;

            case RequestMethod::PUT:
                if (array_key_exists("id", $req->getOptions())) {
                    $user_data = $this->eb->instanciate_with($user_data);
                    $res = $entity->put($user_data, (int)$req->getOptions()["id"]);
                } else {
                    throw new Exception("PUT request need an id to operate", 500);
                }
                break;

            case RequestMethod::DELETE:
                if (array_key_exists("id", $req->getOptions())) {
                    $res = $entity->delete((int)$req->getOptions()["id"]);
                } else {
                    throw new Exception("DELETE request need an id to operate", 500);
                }
                break;
        }
        return $res;
    }
}
