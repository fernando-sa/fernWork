<?php

namespace fernandoSa\ORMonster;

class ModelQuery
{
    private $model;
    private $query;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function find(array $conditions = [], array $columns = ['*'])
    {
        $result = $this->model->getDriver()
            ->select($conditions, $columns)
            ->execute()
            ->first();

            
        $newModel = new \ReflectionClass($this->model);
        $newModel = $newModel->newInstance();

        foreach ($result as $field => $value) {
            $newModel->$field = $value;
        }

        return $newModel;
    }

    public function findAll()
    {
        $results = $this->model->getDriver()
            ->select()
            ->execute()
            ->all();

        $newModels = [];
        // 100% there is a better way of doing this.
        foreach ($results as $record) {
            $newModel = new \ReflectionClass($this->model);
            $newModels[] = $newModel->newInstance();

            foreach ($record as $field => $attribute) {
                end($newModels)->$field = $attribute;
            }
        }

        return $newModels;
    }
}
