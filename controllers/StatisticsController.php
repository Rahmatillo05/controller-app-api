<?php

namespace app\controllers;

use app\models\Statistics;
use yii\web\MethodNotAllowedHttpException;

class StatisticsController extends BaseController
{
	public $modelClass = Statistics::class;

	public function actions ()
	{
		$actions = parent::actions();
		unset($actions[ 'view' ], $actions[ 'delete' ], $actions[ 'update' ], $actions[ 'create' ]);

		return $actions;
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