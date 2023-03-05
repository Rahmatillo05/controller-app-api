<?php

namespace app\modules\seller\controllers;

use app\controllers\BaseController;
use yii\web\MethodNotAllowedHttpException;

class SellingController extends BaseController
{

    public function behaviors()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['delete'], $actions['view'], $actions['update']);

        return $actions;
    }

    public function actionSellingOnCash()
    {
        if ($this->request->isPost) {
            return $this->request->post();
        }
        throw new MethodNotAllowedHttpException();
    }

}