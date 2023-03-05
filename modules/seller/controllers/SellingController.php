<?php

namespace app\modules\seller\controllers;

use app\controllers\BaseController;
use app\models\Selling;
use yii\web\MethodNotAllowedHttpException;
use yii\web\ServerErrorHttpException;

class SellingController extends BaseController
{
    public $modelClass = Selling::class;
    public $defaultAction = 'selling';

    /**
     * @throws ServerErrorHttpException
     * @throws MethodNotAllowedHttpException
     */
    public function actionSelling()
    {
        $model = new Selling();
        if ($this->request->isPost) {
            $productList = $this->request->post('productList');
            $type_pay = $this->request->post('type_pay');
            return $model->soldOnCash($productList, $type_pay);
        }
        throw new MethodNotAllowedHttpException();
    }

    public function actionSellingDebt()
    {

    }
}