<?php

namespace app\controllers;

use app\models\DebtHistory;
use app\models\Debtor;
use app\models\PaymentHistoryList;
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

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $debtor = $this->findModel($id);
        $debt_history = DebtHistory::find()->where(['debtor_id' => $debtor->id])->orderBy(['id' => SORT_DESC])->all();
        $payment_history = PaymentHistoryList::find()->where(['debtor_id' => $debtor->id])->orderBy(['id' => SORT_DESC])->all();

        return [
            'debtor' => $debtor,
            'debt_stat' => $debtor->debtAmount(),
            'debt_history' => $debt_history,
            'payment_history' => $payment_history,
        ];
    }

    public function actionPayDebt($id)
    {
        $model = new PaymentHistoryList();
        if ($this->request->isPost) {
            if ($model->load($this->request->post(), '')) {
                return $model->save();
            } else {
                return $model->errors;
            }
        } else {
            throw new MethodNotAllowedHttpException();
        }
    }

    private function findModel($id): ?Debtor
    {
        if ($id != null) {
            return Debtor::findOne($id);
        }
        throw new NotFoundHttpException("Debtor is not found");
    }
}