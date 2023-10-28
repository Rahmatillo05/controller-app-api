<?php

namespace app\controllers;

use yii\rest\Controller;

class SiteController extends Controller
{
    public function actionIndex(): string
    {
        return "Welcome to Controller APP API System :)";
    }
}