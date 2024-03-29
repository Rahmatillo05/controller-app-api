<?php

namespace app\controllers;

use app\models\Statistics;
use Yii;
use yii\data\ActiveDataProvider;

class StatisticsController extends BaseController
{
    public $modelClass = Statistics::class;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
    ];

    public function actions(): array
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['update'], $actions['delete'], $actions['view']);
        $actions['index']['prepareDataProvider'] = [$this, 'data'];
        return $actions;
    }

    public function data(): ActiveDataProvider
    {
        $monthNumber = Yii::$app->request->get('month') ?? date('m');
        $query = Statistics::find();
        if ($monthNumber) {
            $query->where(['MONTH(period)' => $monthNumber]);
        }
        return new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
            'pagination' => false
        ]);

    }

    public function actionCreate(): bool
    {

        return (new Statistics())->saved();
    }

}