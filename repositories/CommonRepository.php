<?php

namespace app\repositories;

use app\interfaces\iStorageRepository;
use mhndev\yii2Repository\AbstractSqlArRepository;

class CommonRepository extends AbstractSqlArRepository implements iStorageRepository
{
    public function findOneById($id, $returnArray = false)
    {
        if (!$id || !($model = $this->model::findOne($id))){
            \Yii::$app->response->statusCode = 404;
            return [
                'message' => 'Object not found'
            ];
        } else{
            return  $model;
        }
    }
}