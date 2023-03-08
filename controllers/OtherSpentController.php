<?php

namespace app\controllers;

use app\models\OtherSpent;
use app\models\PlasticCardTax;
use yii\web\MethodNotAllowedHttpException;

class OtherSpentController extends BaseController
{
    public $modelClass = OtherSpent::class;

    public function actionPlasticCardTax()
    {
        if (!PlasticCardTax::find()->all()) {
            $model = new PlasticCardTax();
        } else {
            $model = PlasticCardTax::find()->orderBy(['id' => SORT_DESC])->one();
        }
        if ($this->request->isPost && $model->load($this->request->post(), '')) {
            return $model->save() ? $model : $model->errors;
        } else {
            throw new MethodNotAllowedHttpException();
        }
    }

    public function actionPlasticCardTaxUpdate($id)
    {
        $model = PlasticCardTax::find()->where(['id' => $id])->one();
        if ($this->request->isPut || $this->request->isPatch && $model->load($this->request->post())) {
            return $model->save() ? $model : $model->errors;
        } else {
            throw new MethodNotAllowedHttpException();
        }
    }
}