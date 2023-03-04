<?php

namespace app\controllers;

use app\models\User;
use yii\data\ActiveDataProvider;

class WorkerController extends BaseController
{
    public $modelClass = User::class;

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'setDataProvider'];

        return $actions;
    }

    public function setDataProvider()
    {
        $model = new ActiveDataProvider([
            'query' => User::find()->where(['user_role' => User::ROLE_SELLER])
        ]);

        return $model;
    }

}