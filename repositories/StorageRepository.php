<?php

namespace app\repositories;

use app\interfaces\iStorageRepository;
use app\models\BaseModel;
use app\models\ProductList;
use app\models\StorageProduct;
use mhndev\yii2Repository\AbstractSqlArRepository;
use yii\db\Exception;

class StorageRepository extends AbstractSqlArRepository implements iStorageRepository
{
    public function multiCreate(array $data): array
    {
        $products = $data['products'];
        $product_list = ProductList::findOne($data['product_list_id']);
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            foreach ($products as $product) {
                $storage_product = new StorageProduct();
                $storage_product->load($product, '');
                $storage_product->product_list_id = $product_list->id;
                $storage_product->status = $product_list->status;
                $storage_product->save();
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return ['message' => $e->getMessage(), 'code' => $e->getCode()];
        }
        return StorageProduct::findAll(['product_list_id' => $product_list->id]);
    }

    public function accept(object|array $data): array
    {
        $products = $data['products'];
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            foreach ($products as $product) {
                $storage_product = StorageProduct::findOne($product['id']);
                $storage_product->load($product, '');
                if ($storage_product->income_amount >= $storage_product->amount) {
                    $storage_product->status = BaseModel::STATUS_ACTIVE;
                }
                $storage_product->save();
            }
            if (!StorageProduct::find()
                ->andWhere(['product_list_id' => $data['product_list_id']])
                ->andWhere(['status' => BaseModel::STATUS_WAITING])
                ->exists()
            ) {
                $product_list = ProductList::findOne($data['product_list_id']);
                $product_list->status = BaseModel::STATUS_ACTIVE;
                $product_list->date = time();
                $product_list->save();
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return ['message' => $e->getMessage(), 'code' => $e->getCode()];
        }
        return StorageProduct::findAll(['product_list_id' => $data['product_list_id']]);
    }

    public function getAmountOfWaitingProducts()
    {
        $sql = <<<SQL
            SELECT sum(amount) FROM storage_product where status=2 group by product_id
        SQL;
        return \Yii::$app->db->createCommand($sql)->queryAll();
    }
}