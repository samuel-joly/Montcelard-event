<?php
declare(strict_types=1);

class Response
{
    public int $code;
    public string $message;
    public array $data;

    /**
     * @param $data
     */
    public function __construct(array $data, string $message, int $code)
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    public function send(): void
    {
        header("Content-Type: application/json",true, $this->code);
        $response = json_encode([
            "data" => $this->data,
            "message" => $this->message,
        ]);
        echo $response;
    }
}
