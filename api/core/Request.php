<?php
declare(strict_types=1);

enum RequestMethod: string
{
    case GET="GET";
    case POST="POST";
    case PUT="PUT";
    case DELETE="DELETE";
}

class Request
{
    public RequestMethod $method;
    public array $body;
    public array $queryParams;
    public string $entityName;

    /**
     * @param $query_params
     */
    public function __construct(
        string $method,
        string $body,
        array $query_params,
    ) {
        if (!array_key_exists("entity", $query_params) || $query_params["entity"] == "") {
            throw new Exception("Request is made without an entity", 400);
        }
        /* if($query_params["entity"] != "login") { */
        /*     if (!array_key_exists("Bearer", getallheaders())) { */
        /*             throw new Exception("Request must have a JWT",403); */
        /*     } */ 
        /*     $bearer = getallheaders()["Bearer"]; */
        /*     if(!Login::validate_JWT($bearer)) { */
        /*         throw new Exception("Request must have a valid JWT",403); */
        /*     } */
        /* } */

        $this->entityName = $query_params["entity"];
        unset($query_params["entity"]);
        $this->queryParams=$query_params;
        $this->setMethod($method);
        $this->setBody($body);
    }

    public function getMethod(): RequestMethod
    {
        return $this->method;
    }

    private function setMethod(string $method): void
    {
        switch ($method) {
            case "DELETE":
                $this->method = RequestMethod::DELETE;
                break;
            case "POST":
                $this->method = RequestMethod::POST;
                break;
            case "PUT":
                $this->method = RequestMethod::PUT;
                break;
            default:
                $this->method = RequestMethod::GET;
                break;
        }
    }

    public function getBody(): array
    {
        return $this->body;
    }

    private function setBody(string $body): void
    {
            $req_body = json_decode($body, true);
            if ($req_body != NULL) {
                if (array_key_exists("id", $req_body)) {
                    unset($req_body["id"]);
                }
                $this->body = $req_body;
            } else {
                $this->body = [];
            }
    }
}
