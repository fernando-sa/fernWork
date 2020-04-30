<?php

namespace fernandoSa\Database\Drivers;

use Exception;
use fernandoSa\ORMonster\Model;
use PDOException;

class MySqlPdo implements DriverStrategy{

    private $pdo;
    private $tableName;
    private $query;
    private $primaryKeyName;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function setTable(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function setPrimaryKey(string $primaryKeyName)
    {
        $this->primaryKeyName = $primaryKeyName;
    }

    public function save(Model $data) : DriverStrategy
    {
        $primaryKeyName = $this->primaryKeyName;
        
        if(empty($data->$primaryKeyName))
            $this->insert($data);
        else
            $this->update($data);

        return $this;
    }

    public function insert(Model $data)
    {
        $fields = [];
        $fieldsToBind  = [];
        foreach ($data as $field => $value) {
            $fields[] = $field;
            $fieldsToBind[] = ":" . $field;
        }

        $fields = implode(', ', $fields);
        $fieldsToBind = implode(', ', $fieldsToBind);

        $query = "insert into {$this->tableName} ({$fields}) VALUES ({$fieldsToBind})";
        
        $this->query = $this->pdo->prepare($query);

        $this->bind($data);
    }

    public function update(Model $data) : DriverStrategy
    {
        $primaryKeyName = $this->primaryKeyName;

        if(empty($data->$primaryKeyName))
            throw new Exception("Model need a primary key to be updated");

        $updateData =  $this->parameters($data);

        $query = "UPDATE {$this->tableName} SET {$updateData} WHERE {$primaryKeyName}={$data->$primaryKeyName}";

        $this->query = $this->pdo->prepare($query);


        $this->bind($data);

        return $this;
    }

    public function select(array $parameters = [], array $columns = ['*']) : DriverStrategy
    {

        $conditions =  $this->parameters($parameters);

        if(in_array("*", $columns))
            $columns = "*";
        else
            $columns = implode(",", $columns);

        $query = "SELECT {$columns} FROM {$this->tableName}";

        if($conditions)
            $query .= " WHERE {$conditions}";

        $this->query = $this->pdo->prepare($query);

        $this->bind($parameters);

        return $this;
    }

    public function delete(Model $data) : DriverStrategy
    {
        $primaryKeyName = $this->primaryKeyName;

        if(empty($data->$primaryKeyName))
            throw new Exception("Model need a primary key to be updated");

        $query = "DELETE FROM {$this->tableName} WHERE {$primaryKeyName} = {$data->$primaryKeyName}";

        $this->query = $this->pdo->prepare($query);

        $this->bind($data);

        return $this;
    }

    public function execute(string $query = null) : DriverStrategy
    {
        if($query)
            $this->query = $this->pdo->prepare($query);

        $this->query->execute();
        return $this;
    }


    public function first()
    {
        return $this->query->fetch(\PDO::FETCH_OBJ);
    }

    public function all()
    {
        return $this->query->fetchAll(\PDO::FETCH_OBJ);
    }

    public function parameters($parameters) : string
    {
        $fields = [];
        foreach ($parameters as $field => $value) {
            $fields[] = $field. '=:' . $field;
        }

        return implode(', ', $fields);        
    }

    public function bind($data)
    {
        try {
            foreach ($data as $field => $value) {
                $this->query->bindValue($field, $value);
            }
        } catch (PDOException $exception) {
            echo("Error binding query values. Message: {$exception->getMessage()}. Code: {$exception->getCode()}.");
        }

    }
}