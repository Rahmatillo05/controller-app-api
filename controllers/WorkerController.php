<?php

namespace app\controllers;

use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\MethodNotAllowedHttpException;

class WorkerController extends BaseController
{
    public $modelClass = User::class;

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'setDataProvider'];
        unset($actions['create']);
        return $actions;
    }

    public function setDataProvider()
    {
        $model = new ActiveDataProvider([
            'query' => User::find()->where(['user_role' => User::ROLE_SELLER])
        ]);

        return $model;
    }

    public function actionCreate()
    {
        $model = new User();
        if ($this->request->isPost) {
            if ($model->load($this->request->post(), '')) {
                $model->username = strtolower($model->first_name);
                $model->user_role = User::ROLE_SELLER;
                $model->setPassword($model->username);
                $model->generateAuthKey();
                return $model->save() ? $model : $model->errors;
            }
        }

        throw new MethodNotAllowedHttpException("Method not allowed");
    }

}