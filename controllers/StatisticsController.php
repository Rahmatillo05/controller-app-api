<?php

namespace app\controllers;

use app\models\Statistics;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\MethodNotAllowedHttpException;

class StatisticsController extends BaseController
{
	public $modelClass = Statistics::class;

	public function actions ()
	{
		$actions = parent::actions();
		unset($actions[ 'view' ], $actions[ 'delete' ], $actions[ 'update' ], $actions[ 'create' ]);
		$actions[ 'index' ][ 'prepareDataProvider' ] = [ $this, 'data' ];

		return $actions;
	}

	public function data ()
	{
		$period = (int)Yii::$app->request->get('period');
		return new ActiveDataProvider([
			'query' => Statistics::find()->where([ '>=', 'created_at', strtotime('+' . $period . 'days') ]),
			'pagination' => [
				'pageSize' => 50
			],
			'sort' => [
				'defaultOrder' => [
					'id' => SORT_DESC,
				]
			],
		]);
	}

	public function actionCreate ()
	{
		$model = new Statistics();
		if ( $this->request->isPost ) {
			return $model->saved();
		} else {
			throw new MethodNotAllowedHttpException();
		}
	}

}