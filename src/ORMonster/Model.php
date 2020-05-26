<?php

namespace fernandoSa\ORMonster;

use fernandoSa\App;
use fernandoSa\Singleton;
use fernandoSa\Database\Drivers\DriverStrategy;

abstract class Model
{
    protected $driver;

    protected $primaryKey = "id";

    protected $modelQuery;

    public function __construct()
    {
        $this->driver = Singleton::instance(App::class)->getConnection()->getSqlPdo();
        $this->driver->setTable($this->table ?? $this->className());
        $this->driver->setPrimaryKey($this->primaryKey);
    }

    public function getDriver() : DriverStrategy
    {
        return $this->driver;
    }

    public function save() : void
    {
        $this->driver
            ->save($this)
            ->execute();
    }

    public function findAll() : array
    {
        if (! $this->modelQuery) {
            $this->modelQuery = new ModelQuery($this);
        }

        return $this->modelQuery->findAll($this);
    }

    public function find(array $conditions = [], array $columns = ['*']) : Model
    {
        if (! $this->modelQuery) {
            $this->modelQuery = new ModelQuery($this);
        }

        return $this->modelQuery->find($conditions, $columns);
    }

    public function delete() : void
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
