<?php

namespace app\modules\seller\controllers;

use app\models\Debtor;

class DebtorController extends \app\controllers\BaseController
{
    public $modelClass = Debtor::class;

    public function actions()
    {
        return parent::actions();
    }
}