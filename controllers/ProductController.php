<?php

namespace app\controllers;

use app\models\Product;
use app\models\ProductAmount;
use app\models\Selling;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

class ProductController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = Product::class;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['view'], $actions['update']);

        return $actions;
    }

    /**
     * @return Product|array
     * @throws MethodNotAllowedHttpException
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($this->request->isPost) {
            if ($model->load($this->request->post(), '')) {
                if ($model->addNewProductAmount()) {
                    return $model;
                } else {
                    return $model->errors;
                }
            } else {
                return $model->errors;
            }
        }
        throw new MethodNotAllowedHttpException();
    }

    /**
     * @param $id
     * @return Product|array|void|null
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($this->request->isPut || $this->request->isPatch) {
            if ($model->load($this->request->post(), '')) {
                if ($model->updateProductAmount() && $model->save()) {
                    return $model;
                } else {
                    return $model->errors;
                }
            } else {
                return $model->errors;
            }
        }
    }


    /**
     * @param $id
     * @return string[]
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $product_data = $this->findModel($id);
        $product_amount = ProductAmount::findOne(['product_id' => $product_data->id]);
        $selling_list = Selling::find()->where(['product_id' => $product_data->id])->orderBy(['id' => SORT_DESC])->all();
        return [
            'product_data' => $product_data,
            'product_amount' => $product_amount,
            'selling_list' => $selling_list
        ];
    }

    /**
     * @param $id
     * @return Product|null
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        if ($id != null) {
            return Product::findOne($id);
        }
        throw new NotFoundHttpException("Product not found");
    }
}