<?php
enum RequestMethod: string
{
    case GET="GET";
    case POST="POST";
    case PUT="PUT";
    case DELETE="DELETE";
}

class Request
{
    private RequestMethod $method;
    private array $body;
    private array $options;

    /**
     * @param $query_params
     */
    public function __construct(
        string $method,
        string $body,
        array $query_params,
    ) {
        if ($query_params["entity"] === "") {
            throw new Exception("Request is made without an entity", 400);
        }
        if($query_params["entity"] != "login") {
            if (!array_key_exists("Bearer", getallheaders())) {
                    throw new Exception("Request must have a JWT",500);
            } 
            $bearer = getallheaders()["Bearer"];
            if(!Login::validate_JWT($bearer)) {
                throw new Exception("Request must have a valid JWT",500);
            }
        }

        $this->setMethod($method);
        $this->setBody($body);
        $this->options = $query_params;
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
                $this->body = $req_body;
            } else {
                $this->body = [];
            }
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
