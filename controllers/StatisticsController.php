<?php

namespace app\controllers;

use app\models\Statistics;
use app\models\StatisticsDetail;
use Yii;
use yii\data\ActiveDataProvider;

class StatisticsController extends BaseController
{
    public $modelClass = Statistics::class;

    public function actions(): array
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['update'], $actions['delete'], $actions['view']);
        $actions['index']['prepareDataProvider'] = [$this, 'data'];
        return $actions;
    }

    public function data()
    {
        $monthNumber = Yii::$app->request->get('month');
        $query = Statistics::find();
        if ($monthNumber) {
            $query->where(['MONTH(period)' => $monthNumber]);
        }
        return new ActiveDataProvider([
            'query' => $query
        ]);

    }

    public function actionCreate(): bool
    {
        return (new Statistics())->saved();
    }

}