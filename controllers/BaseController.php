<?php

namespace app\controllers;

use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

class BaseController extends ActiveController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => Cors::class,

        ];

        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        return $behaviors;
    }
}
