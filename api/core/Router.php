<?php

declare(strict_types=1);

class Router
{
    public array $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    public function add(string $route, CrudEntity $entity_class): void
    {
        $this->routes += [$route => $entity_class];
    }

    public function route(Request $req): Response
    {
        if (!array_key_exists($req->getOptions()["entity"], $this->routes)) {
            throw new Exception("No entity named \"".$req->getOptions()["entity"]."\"", 400);
        }

        $entity = $this->routes[$req->getOptions()["entity"]];
        switch($req->getMethod()) {

            case RequestMethod::GET:
                return $entity->get((int)$req->getOptions()["id"] ?: null);
                break;

            case RequestMethod::POST:
                return $entity->post($req->getBody());
                break;

            case RequestMethod::PUT:
                if(array_key_exists("id", $req->getOptions())) {
                    $id = $req->getOptions()["id"];
                    return $entity->put($req->getBody(), $id);
                } else {
                    throw new Exception("PUT request must give an id", 500);
                }
                break;

            case RequestMethod::DELETE:
                if(array_key_exists("id", $req->getOptions())) {
                    $id = $req->getOptions()["id"];
                    return $entity->delete($id);
                } else {
                    throw new Exception("DELETE request must give an id", 500);
                }
                break;
        }
    }
}
