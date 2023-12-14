<?php

include("autoload.php");
try {
    $req = new Request(
        $_SERVER["REQUEST_METHOD"],
        file_get_contents("php://input"),
        $_GET
    );
    $router = new Router();
    $router->add("event", new Event());

    header("Content-Type: application/json");
    echo $router->route($req)->send();
} catch (Exception $e) {
    (new Response([$e->getPrevious()], $e->getMessage(), $e->getCode()))->send();
}
