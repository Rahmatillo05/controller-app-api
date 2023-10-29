<?php

namespace app\controllers;

use app\models\BaseModel;
use app\models\StorageProduct;
use app\repositories\StorageRepository;
use Yii;
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
    public function actionMultiCreate(Request $request): array
    {
        $data = $request->getBodyParams();
        if (!isset($data['product_list_id'])) {
            Yii::$app->response->statusCode = 422;
            return ['message' => '"product_list_id" cannot be blank'];
        }

        return $this->storageRepository->multiCreate($data);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionAccept(Request $request)
    {
        $data = $request->getBodyParams();
        if (!isset($data['product_list_id'])) {
            Yii::$app->response->statusCode = 422;
            return ['message' => '"product_list_id" cannot be blank'];
        }
        return $this->storageRepository->accept($data);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionUpdate(int $id, Request $request)
    {
        $data = $request->getBodyParams();
        return $this->storageRepository->updateOneById($id, $data);
    }

    public function actionView(int $id)
    {
        return $this->storageRepository->findOneById($id);
    }

    public function actionDelete(int $id): bool|int
    {
        return $this->storageRepository->deleteOneById($id);
    }
}