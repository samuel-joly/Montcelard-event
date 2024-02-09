<?php

include("autoload.php");

enum Entity: string
{
    case event = "event";
    case login = "login";
}

try {
    $req = new Request(
        $_SERVER["REQUEST_METHOD"],
        file_get_contents("php://input"),
        $_GET
    );

    $router = new Router();
    $router->add(Entity::event->value, new Event());
    $router->add(Entity::login->value, new Login());

    $router->route($req)->send();
} catch (Exception $e) {
    (new Response([$e->getPrevious()], $e->getMessage(), (int)$e->getCode()))->send();
}
