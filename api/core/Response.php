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
        $this->makeHeader();
        $response = json_encode([
            "data" => $this->data,
            "message" => $this->message,
        ]);
        echo $response;
    }

    private function makeHeader():void {
        $header_string = "Content-Type: application/json";
        $append_string = "";
        if ($this->code == 401) {
            $append_string = ";WWW-Authenticate: Bearer";
        }
        header($header_string.$append_string, true, $this->code);
    }
}
