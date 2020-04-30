<?php

namespace fernandoSa\Database;

class Connection
{

    private $pdo;

    public function __construct(String $databseType, String $host, String $dbName, String $user, String $password)
    {
        try {
            $this->pdo = new \PDO("{$databseType}:host={$host};dbname={$dbName}", $user, $password);
        } catch (\PDOException $exception) {
            echo "Error conecting with database! Message: {$exception->getMessage()}. Code: {$exception->getCode()}\n";
            exit();
        }
    }

    public function getPdo() : \Pdo
    {
        return $this->pdo;
    }
}
