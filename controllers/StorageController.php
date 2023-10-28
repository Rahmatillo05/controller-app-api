<?php

namespace app\controllers;

use app\models\Log;
use app\repositories\StorageRepository;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Json;
use yii\rest\Controller;
use yii\rest\Serializer;

class StorageController extends Controller
{
    protected StorageRepository $storageRepository;

    public function init(): void
    {
        parent::init();
        $this->storageRepository = new StorageRepository();
    }

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
            'update' => ['PUT'],
            'index' => ['GET'],
            'view' => ['GET'],
        ];
    }

    public function actionIndex()
    {
        return $this->storageRepository;
    }

    public function actionCreate()
    {
        return $this->storageRepository->create();
    }
}