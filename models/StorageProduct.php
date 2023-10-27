<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "storage_product".
 *
 * @property int $id
 * @property int|null $category_id
 * @property int|null $product_id
 * @property int|null $unit_id
 * @property float|null $amount
 * @property float|null $price
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $product_list_id
 *
 * @property Category $category
 * @property Product $product
 * @property ProductList $productList
 * @property Unit $unit
 */
class StorageProduct extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'storage_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'product_id', 'unit_id', 'status', 'created_at', 'updated_at', 'product_list_id'], 'integer'],
            [['amount', 'price'], 'number'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
            [['product_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductList::class, 'targetAttribute' => ['product_list_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::class, 'targetAttribute' => ['unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'product_id' => 'Product ID',
            'unit_id' => 'Unit ID',
            'amount' => 'Amount',
            'price' => 'Price',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'product_list_id' => 'Product List ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return ActiveQuery
     */
    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[ProductList]].
     *
     * @return ActiveQuery
     */
    public function getProductList(): ActiveQuery
    {
        return $this->hasOne(ProductList::class, ['id' => 'product_list_id']);
    }

    /**
     * Gets query for [[Unit]].
     *
     * @return ActiveQuery
     */
    public function getUnit(): ActiveQuery
    {
        return $this->hasOne(Unit::class, ['id' => 'unit_id']);
    }
}
