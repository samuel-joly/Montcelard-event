<?php
declare(strict_types=1);
include("autoload.php");


try {
    $routes = [
        "reservation" => new Reservation(),
        "login" => new Login()
    ];

    $req = new Request(
        $_SERVER["REQUEST_METHOD"],
        file_get_contents("php://input"),
        $_SERVER["QUERY_STRING"]
    );
    if(!array_key_exists($req->entityName, $routes)) {
        throw new Exception("No API for entity \"".$req->entityName, 200);
    }

    $CRUDEntity = $routes[$req->entityName];

    switch($req->getMethod()) {
    case RequestMethod::GET:
        $schema = [];
        if ($req->schema) {
            $schema = $CRUDEntity->get_schema();
        }

        $qp = $CRUDEntity->parseQueryParams($req->queryParams);
        $res = $CRUDEntity->get($qp);

        if(count($schema) > 0) {
            array_push($res->data , $schema);
        }
        break;

    case RequestMethod::POST:
        if($req->body != null) {
            $unknown_property = array_diff_key($req->body, $CRUDEntity->get_schema());
            if(count($unknown_property) > 0) {
                $u_props = "";
                foreach($unknown_property as $u_prop => $val) {
                    $u_props .= " '".$u_prop."'";
                }
                throw new Error("Following properties are not defined in ".$CRUDEntity->get_name()." : ".$u_props, 200);
            }
            $CRUDEntity->constructFromArray($req->body);
            $res = $CRUDEntity->post();
        } else {
            throw new Error("POST request must have a body", 200);
        }
        break;

    case RequestMethod::PUT:
        if ($req->have_query_param("id")) {
            $db_entity = $CRUDEntity->get([["id" => ["=", $req->get_query_param("id")]]])->data;
            if (count($db_entity) == 1) {
                $db_entity = $db_entity[0];
                $schema = $CRUDEntity->get_schema();
                if(array_key_exists("id", $req->body)) {
                    $schema["id"] = 0;
                }
                $unknown_property = array_diff_key($req->body, $schema);
                if(count($unknown_property) > 0) {
                    $u_props = "";
                    foreach($unknown_property as $u_prop) {
                        $u_props .= " '".$u_prop."'";
                    }
                    throw new Error("Following properties are not defined in ".$CRUDEntity->get_name()." : ".$u_props, 200);
                }
                $CRUDEntity->set_id($db_entity["id"]);
                $CRUDEntity->constructFromArray(array_merge($db_entity,$req->body));
                $res = $CRUDEntity->put($req->body);
            } else {
                throw new Error("No ".$CRUDEntity->get_name()." found with id ".$req->get_query_param("id"), 200);
            }
        } else {
            throw new Exception("PUT request need an id to operate", 200);
        }
        break;

    case RequestMethod::DELETE:
        if ($req->have_query_param("id")) {
            $db_entity = $CRUDEntity->get([["id" => ["=", $req->get_query_param("id")]]])->data;
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
} catch (Error|PDOException|Exception $e) {
    (new Response([], $e->getMessage(), (int)$e->getCode()))->send();
}
