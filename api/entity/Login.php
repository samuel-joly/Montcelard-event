<?php

class Login extends CrudEntity implements CrudEntityInterface
{
    public string $name;
    public string $password;

    public function get_name(): string
    {
        return 'login';
    }

    public function check(array $data): bool
    {
        return true;
    }

    public function post(array $data): Response
    {
        $db_call = (new Mysql())->query("Select password from login where name = '".$this->name."';");
        if (sizeof($db_call) == 0) {
            return new Response([], "Wrong login or password", 500);
        }
        $db_password = $db_call[0]["password"];
        if (!password_verify($this->password,$db_password)) {
            return new Response([], "Wrong login or password", 500);
        }
        $jwt = $this->gen_JWT();
        return new Response(["jwt" => $jwt["header"].".".$jwt["payload"].".".$jwt["signature"] ], "login successfull", 200);
    }

    /**
     * @return array<string,string>
     */
    private function gen_JWT(): array
    {
        $header = '{"typ":"JWT","alg":"HS256"}';
        $payload = '{"iss":"'.$this->name.'","exp":'.strtotime("+1 day", (int)(new DateTime())->format("U")).'}';
        $encoded_header =  base64_encode($header);
        $encoded_payload = base64_encode($payload);
        return ["header" => $encoded_header,
                "payload" => $encoded_payload,
                "signature" =>
                hash_hmac(
                    "sha256",
                    $encoded_header.$encoded_payload,
                    getenv("JWT_SECRET")
                )
        ];
    }

    public static function validate_JWT(string $token): bool
    {
        $split_token = explode(".", $token);
        if(sizeof($split_token) != 3) {
            throw new Exception("Invalid token", 500);
        }

        $test_encrypt = hash_hmac(
            "sha256",
            $split_token[0].$split_token[1],
            getenv("JWT_SECRET")
        );
        $expiration_timestamp = (int)json_decode(base64_decode($split_token[1]), true)['exp'];
        $current_timestamp =  (int)(new DateTime())->format('U');
        if ($expiration_timestamp - $current_timestamp <= 0) { 
            throw new Exception("Token is expired, please renew it at /api/login", 500);
        } 
        if (hash_equals($test_encrypt, $split_token[2])) {
            return true;
        }
        return false;
    }

    public function put(array $data, int $id): Response
    {
        throw new Exception("Only POST request are accepted on /login", 400);
    }
    public function delete(int $id = null): Response
    {
        throw new Exception("Only POST request are accepted on /login", 400);
    }
    public function get(int $id = null): Response
    {
        throw new Exception("Only POST request are accepted on /login", 400);
    }
}
