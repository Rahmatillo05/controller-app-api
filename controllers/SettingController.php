<?php

namespace app\controllers;

use app\models\ChangePassword;
use app\models\User;
use yii\web\MethodNotAllowedHttpException;

class SettingController extends BaseController
{
	public $modelClass = User::class;

	public function actions ()
	{
		$actions = parent::actions();
		unset($actions[ 'index' ], $actions[ 'view' ], $actions[ 'update' ], $actions[ 'delete' ]);
		return $actions;
	}

	public function actionChangePassword (): bool
	{
		$model = new ChangePassword();
		if ( $this->request->isPost && $model->load($this->request->post(), '') ) {
			return $model->save();
		} else {
			throw new MethodNotAllowedHttpException();
		}
	}
}