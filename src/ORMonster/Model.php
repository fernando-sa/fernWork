<?php

namespace fernandoSa\ORMonster;

use fernandoSa\Database\Drivers\DriverStrategy;

class Model
{
    protected $driver;

    // TODO: Remove
    protected $table = "users";

    protected $primaryKey = "id";

    protected $modelQuery;

    public function setDriverAmbient(DriverStrategy $driver) : Model
    {
        $this->driver = $driver;
        $this->driver->setTable($this->table ?? $this->className());
        $this->driver->setPrimaryKey($this->primaryKey);
        return $this;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function save()
    {
        $this->driver
            ->save($this)
            ->execute();
    }

    public function findAll()
    {
        if(! $this->modelQuery)
            $this->modelQuery = new ModelQuery($this);

        return $this->modelQuery->findAll($this);
    }

    public function find(array $conditions = [], array $columns = ['*'])
    {
        if(! $this->modelQuery)
            $this->modelQuery = new ModelQuery($this);

        return $this->modelQuery->find($conditions, $columns);

    }

    public function delete()
    {
        $this->driver
            ->delete($this)
            ->execute();
    }

    public function className() : string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
