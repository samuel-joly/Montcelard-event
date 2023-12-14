<?php
declare(strict_types=1);

class Mysql
{
    private string $servername ;
    private string $username   ;
    private string $password   ;
    private string $dbname     ;

    protected PDO $conn;

    public function __construct() {
        $this->servername = $_ENV["MYSQL_URL"];
        $this->username = $_ENV["MYSQL_USER"];
        $this->password = $_ENV["MYSQL_PASSWORD"];
        $this->dbname = $_ENV["MYSQL_DATABASE"];

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

    /**
     * @return array|boolean
     **/
    public function query(string $sql_query): array
    {
        $statement = $this->conn->prepare($sql_query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_BOTH);
        if ($data === false) {
            throw new Exception("PDO query \"$sql_query\" failed", 500);
        }
        return $data;
    }
}
