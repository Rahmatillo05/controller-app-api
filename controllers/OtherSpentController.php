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
        return PlasticCardTax::find()->orderBy(['id' => SORT_DESC])->one();
    }

    public function actionPlasticCardTaxUpdate($id)
    {
        $model = PlasticCardTax::find()->where(['id' => $id])->one();
        if ($this->request->isPut || $this->request->isPatch && $model->load($this->request->post(), '')) {
            $model->tax_amount = $this->request->post('tax_amount');
            return $model->save() ? $model : $model->errors;
        } else {
            throw new MethodNotAllowedHttpException();
        }
    }
}