<?php

namespace app\modules\seller\controllers;

use app\models\Debtor;
use yii\web\MethodNotAllowedHttpException;

class DebtorController extends \app\controllers\BaseController
{
    public $modelClass = Debtor::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);

        return $actions;
    }

    public function actionCreate()
    {
        $model = new Debtor();
        if ($this->request->isPost) {
            if ($model->load($this->request->post(), '') && $model->addNewDebtor()) {
                return $model;
            }
        } else {
            throw new MethodNotAllowedHttpException();
        }
    }
}