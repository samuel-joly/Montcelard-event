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
    public bool $schema = false;

    /**
     * @param $query_params
     */
    public function __construct(
        string $method,
        string $body,
        string $query_params,
    ) {
        if($query_params["entity"] != "login") {
            if (!array_key_exists("Bearer", getallheaders())) {
                    throw new Exception("Request must have a JWT",403);
            } 
            $bearer = getallheaders()["Bearer"];
            if(!Login::validate_JWT($bearer)) {
                throw new Exception("Request must have a valid JWT",403);
            }
        }

        $this->setQueryParams($query_params);
        $this->setMethod($method);
        $this->setBody($body);
    }
    /**
     * @param string $qp
     */
    public function setQueryParams(string $qp): void {
        $qp = explode("&", $qp);
        $entityAsked = "";
        $this->queryParams = [];
        foreach($qp as $params) {
            $p = explode("=", $params);
            if($p[0] == "entity") {
                $entityAsked = $p[1];
            } else if( $p[0] == "schema") {
                $this->schema = true;
            } else {
                if(count($p) == 3) {
                    $this->queryParams[] = [$p[0],"=",$p[2]];
                } else {
                    $this->queryParams[] = [$p[0],$p[1]];
                }
            }
        }
        if ($entityAsked == "") {
            throw new Exception("Request is made without an entity", 400);
        }
        $this->entityName = $entityAsked;
    }

    public function have_query_param(string $param): bool {
        foreach($this->queryParams as $nest => $qp) {
            if ($qp[0] == $param) {
                return true;
            }
        }
        return false;
    }

    public function get_query_param(string $param): mixed {
        foreach($this->queryParams as $nest => $qp) {
            if ($qp[0] == $param) {
                if(count($qp) == 3) {
                    return $qp[2];
                } else {
                    return $qp[1];
                }
            }
        }
        throw new Error("No param $param in query parameters", 500);
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
                //throw new Error("No id can be given in request body", 500);
            }
            $this->body = $req_body;
        } else {
            $this->body = [];
        }
    }
}
