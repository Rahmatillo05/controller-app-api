<?php

namespace app\modules\seller\controllers;

use app\models\DebtHistory;
use app\models\Debtor;
use app\models\PaymentHistory;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

class DebtorController extends \app\controllers\BaseController
{
    public $modelClass = Debtor::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);
        unset($actions['view']);

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

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $debtor = $this->findModel($id);
        $debt_history = DebtHistory::find()->orderBy(['id' => SORT_DESC])->all();
        $payment_history = PaymentHistory::find()->orderBy(['id' => SORT_DESC])->all();

        return [
            'debtor' => $debtor,
            'debt_history' => $debt_history,
            'payment_history' => $payment_history
        ];
    }

    private function findModel($id): ?Debtor
    {
        if ($id != null){
            return Debtor::findOne($id);
        }
        throw new NotFoundHttpException("Debtor is not found");
    }
}