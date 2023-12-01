<?php
enum RequestMethod
{
    case GET;
    case POST;
    case PUT;
    case DELETE;
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
        $this->setMethod($method);
        $this->setBody($body);
        if ($query_params["entity"] === "") {
            throw new Exception("Request is made without an entity", 400);
        }

        $this->options = $query_params;
        return $this;
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
        if ($this->method === RequestMethod::POST ||
            $this->method === RequestMethod::PUT) {
            $req_body = json_decode($body, true);
            if ($req_body !== null) {
                $this->body = $req_body;
            } else {
                throw new Exception("POST request was made without a body", 400);
            }
        }
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
