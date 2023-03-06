<?php

namespace app\modules\seller\controllers;

use app\models\Debtor;

class DebtorController extends \app\controllers\BaseController
{
    public $modelClass = Debtor::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);

        return $actions;
    }
}