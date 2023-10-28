<?php

namespace app\controllers;

use app\models\Supplier;
use app\repositories\CommonRepository;
use yii\base\InvalidConfigException;
use yii\web\Request;

class SupplierController extends CommonController
{
    protected CommonRepository $commonRepository;
    public function init(): void
    {
        parent::init();
        $this->commonRepository = new CommonRepository(new Supplier());
    }

    public function actionIndex()
    {
        return $this->commonRepository->searchByCriteria();
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionCreate(Request $request)
    {
        $data = $request->getBodyParams();
        return $this->commonRepository->create($data);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionUpdate(int $id, Request $request)
    {
        $data = $request->getBodyParams();
        return $this->commonRepository->updateOneById($id, $data);
    }

    public function actionView(int $id)
    {
        return $this->commonRepository->findOneById($id);
    }

    public function actionDelete(int $id): bool|int
    {
        return $this->commonRepository->deleteOneById($id);
    }
}