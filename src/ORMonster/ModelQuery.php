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

            
        $newModel = new Model;

        foreach ($result as $field => $value) {
            $newModel->$field = $value;
        }
        $newModel->setDriverAmbient($this->model->getDriver());

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
            
            $newModels[] = new Model;

            foreach ($record as $field => $attribute) {
                end($newModels)->$field = $attribute;
            }
            end($newModels)->setDriverAmbient($this->model->getDriver());
        }

        return $newModels;
    }

    
}