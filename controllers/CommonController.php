<?php

namespace app\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\rest\Serializer;

class CommonController extends Controller
{

    public $repository;

    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'items',
    ];


    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['index', 'view']
        ];

        return $behaviors;
    }

    protected function verbs(): array
    {
        return [
            'create' => ['POST'],
            'delete' => ['DELETE'],
            'update' => ['PUT', 'PATCH'],
            'index' => ['GET'],
            'view' => ['GET'],
            'multi-create' => ['POST'],
            'accept' => ['POST']
        ];
    }


}