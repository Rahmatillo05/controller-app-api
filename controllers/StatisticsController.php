<?php

namespace app\controllers;

use app\models\Statistics;

class StatisticsController extends BaseController
{
    public $modelClass = Statistics::class;

    public function actions(): array
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['update'], $actions['delete'], $actions['view']);

        return $actions;
    }

    public function actionCreate()
    {
        return "Save";
    }

}