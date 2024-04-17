<?php
declare(strict_types=1);
include("autoload.php");

$routes = [
    "reservation" => new Reservation(),
    "login" => new Login()
];

try {
    $req = new Request(
        $_SERVER["REQUEST_METHOD"],
        file_get_contents("php://input"),
        $_GET
    );
    if(!array_key_exists($req->entityName, $routes)) {
        throw new Exception("No API for entity \"".$req->entityName, 400);
    }

    $CRUDEntity = $routes[$req->entityName];

    switch($req->getMethod()) {
    case RequestMethod::GET:
        $schema = [];
        if (array_key_exists("schema", $req->queryParams)) {
            $schema = $CRUDEntity->get_schema();
            unset($req->queryParams["schema"]);
        }

        $qp = $CRUDEntity->parseQueryParams($req->queryParams);
        $res = $CRUDEntity->get($qp);

        if(count($schema) > 0) {
            array_push($res->data , $schema);
        }
        break;

    case RequestMethod::POST:
        $CRUDEntity->constructFromArray($req->body);
        $res = $CRUDEntity->post();
        break;

    case RequestMethod::PUT:
        if (array_key_exists("id", $req->queryParams)) {
            $db_entity = $CRUDEntity->get(["id" => ["=", $req->queryParams["id"]]])->data;
            if (count($db_entity) == 1) {
                $db_entity = $db_entity[0];
                $CRUDEntity->constructFromArray(array_merge($db_entity,$req->body));
                $CRUDEntity->set_id($db_entity["id"]);
                $res = $CRUDEntity->put($req->body);
            } else {
                throw new Error("No ".$CRUDEntity->get_name()." found with id ".$req->queryParams["id"], 200);
            }
        } else {
            throw new Exception("PUT request need an id to operate", 200);
        }
        break;

    case RequestMethod::DELETE:
        if (array_key_exists("id", $req->queryParams)) {
            $db_entity = $CRUDEntity->get(["id" => ["=", $req->queryParams["id"]]])->data;
            if (count($db_entity) == 1) {
                $CRUDEntity->set_id($db_entity[0]["id"]);
                $res = $CRUDEntity->delete();
            } else {
                throw new Exception("Entity does not exist, no deletion performed", 200);
            }
        } else {
            throw new Exception("DELETE request need an id to operate", 200);
        }
        break;
    }
    $res->send();
} catch (Exception $e) {
    (new Response([$e->getPrevious()], $e->getMessage(), (int)$e->getCode()))->send();
}
