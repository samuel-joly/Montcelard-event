<?php

declare(strict_types=1);

class Mysql
{
    private string $servername ;
    private string $username   ;
    private string $password   ;
    private string $dbname     ;

    protected PDO $conn;

    public function __construct()
    {
        $this->servername = getenv("MYSQL_URL");
        $this->username = getenv("MYSQL_USER");
        $this->password = getenv("MYSQL_PASSWORD");
        $this->dbname = getenv("MYSQL_DATABASE");

        try {
            $this->connect();
        } catch(PDOException $e) {
            throw new Exception("PDO Connection error", 500, $e);
        }
    }

    private function connect(): void
    {
        $this->conn = new PDO(
            "mysql:host=$this->servername;dbname=$this->dbname",
            $this->username,
            $this->password
        );
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function query(string $sql_query, bool $fetch=true): array|bool
    {
        $statement = $this->conn->prepare($sql_query);
        $res = $statement->execute();
        if (!$res) {
            throw new Exception("Failed PDO query \"$sql_query\"", 500);
        }

        if ($fetch) {
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }
        return $res;
    }
}
