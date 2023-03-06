<?php

namespace app\modules\seller\controllers;

use app\controllers\BaseController;
use app\models\Selling;
use Yii;
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

    /**
     * @throws MethodNotAllowedHttpException
     * @throws ServerErrorHttpException
     */
    public function actionSellingDebt()
    {
        $model = new Selling();
        if (Yii::$app->request->isPost) {
            $sellingList = $this->request->post('productList');
            $debtorData = $this->request->post('debtorData');
            $total_debt = $this->request->post('total_debt');
            $instant_payment = $this->request->post('instant_payment');
            $isCreate = $this->request->post('isCreate');
            if ($isCreate) {
                return $model->saveWithDebtor($sellingList, $debtorData, $total_debt, $instant_payment);
            }
            return $model->saveWithoutDebtor($sellingList, $debtorData, $total_debt, $instant_payment);
        } else {
            throw new MethodNotAllowedHttpException("Method Not Allowed. This URL can only handle the following request methods: POST.");
        }
    }
}