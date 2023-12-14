<?php

include("autoload.php");

enum Entity: string
{
    case event = "event";
    case user = "user";
}

try {
    $req = new Request(
        $_SERVER["REQUEST_METHOD"],
        file_get_contents("php://input"),
        $_GET
    );
    $router = new Router();
    $router->add(Entity::event->value, new Event());
    $router->add(Entity::user->value, new User());

    header("Content-Type: application/json");
    echo $router->route($req)->send();
} catch (Exception $e) {
    (new Response([$e->getPrevious()], $e->getMessage(), (int)$e->getCode()))->send();
}
