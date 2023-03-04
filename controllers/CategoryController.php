<?php

namespace app\controllers;

use app\models\Category;
use yii\web\NotFoundHttpException;

/**
 *
 */
class CategoryController extends BaseController
{
    public $modelClass = Category::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['view']);

        return $actions;
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $category_data = $this->findModel($id);
        $products_in_category = $category_data->products;

        return [
            'category' => $category_data,
            'product' => $products_in_category
        ];
    }

    /**
     * @param $id
     * @return Category|null
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        if ($id != null) {
            return Category::findOne($id);
        }

        throw new NotFoundHttpException("Category not found");
    }
}