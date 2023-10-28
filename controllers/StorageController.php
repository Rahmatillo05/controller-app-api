<?php

namespace app\controllers;

use app\models\StorageProduct;
use app\repositories\StorageRepository;
use yii\base\InvalidConfigException;
use yii\web\Request;

class StorageController extends CommonController
{
    protected StorageRepository $storageRepository;

    public function init(): void
    {
        parent::init();
        $this->storageRepository = new StorageRepository(new StorageProduct());
    }
    public function actionIndex()
    {
        return $this->storageRepository->searchByCriteria();
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionCreate(Request $request)
    {
        $data = $request->getBodyParams();
        return $this->storageRepository->create($data);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionUpdate(int $id, Request $request)
    {
        $data = $request->getBodyParams();
        return $this->storageRepository->updateOneById($id, $data);
    }
}