<?php

namespace app\controllers;

use app\models\AdminLoginForm;
use app\modules\seller\models\SellerLoginForm;
use Yii;
use yii\rest\Controller;
use yii\web\MethodNotAllowedHttpException;

class AuthController extends Controller
{
    public $defaultAction = 'admin-auth';

    public function actionAdminLogin()
    {
        $model = new AdminLoginForm();

        if ($this->request->isPost && $model->load(Yii::$app->request->post(), '')) {
            if ($user_data = $model->login()) {
                return $user_data;
            } else {
                return $model->errors;
            }
        } else {
            throw new MethodNotAllowedHttpException("Method Not Allowed. This URL can only handle the following request methods: POST.");
        }
    }
    public function actionSellerLogin()
    {
        $model = new SellerLoginForm();

        if ($this->request->isPost && $model->load(Yii::$app->request->post(), '')) {
            if ($user_data = $model->login()) {
                return $user_data;
            } else {
                return $model->errors;
            }
        } else {
            throw new MethodNotAllowedHttpException("Method Not Allowed. This URL can only handle the following request methods: POST.");
        }
    }
}
