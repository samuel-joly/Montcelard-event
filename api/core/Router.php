<?php

class Router
{
    private array $routes;
    private EntityBuilder $eb;

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
        $entity_name = $req->queryParams["entity"];
        if (!array_key_exists($entity_name, $this->routes)) {
            throw new Exception("No crud on entity named \"".$entity_name."\"", 400);
        }
        unset($req->queryParams["entity"]);

        $this->eb->set_instance($this->routes[$entity_name]);
        $entity = $this->eb->instance;
        $body = $req->getBody();
        $res = "";
        switch($req->getMethod()) {
            case RequestMethod::GET:
                $entity_schema = [];
                if (array_key_exists("schema", $req->queryParams)) {
                    unset($req->queryParams["schema"]);
                    $entity_props = $this->eb->reflection->getProperties(ReflectionProperty::IS_PUBLIC);
                    foreach ($entity_props as $prop) {
                        $entity_schema[$prop->name] = $this->eb->getPropType($prop->name);
                    }
                }
                $this->eb->format_query_params($req->queryParams);
                $res = $entity->get($req->queryParams);
                if(count($entity_schema) > 0) {
                    array_push($res->data , $entity_schema);
                }
                break;

            case RequestMethod::POST:
                $data = $this->eb->instanciate_all($body);
                $res = $entity->post($data);
                break;

            case RequestMethod::PUT:
                if (array_key_exists("id", $req->queryParams)) {
                    $body = $this->eb->instanciate_partial($body);
                    $res = $entity->put($body, $req->queryParams["id"]);
                } else {
                    throw new Exception("PUT request need an id to operate", 500);
                }
                break;

            case RequestMethod::DELETE:
                if (array_key_exists("id", $req->queryParams)) {
                    $res = $entity->delete($req->queryParams["id"]);
                } else {
                    throw new Exception("DELETE request need an id to operate", 500);
                }
                break;
        }
        return $res;
    }
}
