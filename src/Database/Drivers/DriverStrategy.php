<?php

namespace fernandoSa\Database\Drivers;

use fernandoSa\ORMonster\Model;

interface DriverStrategy
{

    public function setTable(string $tableName);
    public function setPrimaryKey(string $primaryKey);
    public function save(Model $data) : DriverStrategy;
    public function insert(Model $data);
    public function update(Model $data);
    public function select(array $parameters = [], array $columns = ['*']) : DriverStrategy;
    public function delete(Model $data) : DriverStrategy;
    public function execute() : DriverStrategy;
    public function parameters($parameters) : string;
    public function bind($data);
}
