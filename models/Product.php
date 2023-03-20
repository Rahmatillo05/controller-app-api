<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int|null $category_id
 * @property string $product_name
 * @property int $amount
 * @property int $each_amount
 * @property int $all_amount
 * @property int $purchase_price
 * @property int $wholesale_price
 * @property int $retail_price
 * @property int|null $created_at
 *
 * @property Category $category
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'amount', 'each_amount', 'all_amount', 'purchase_price', 'wholesale_price', 'retail_price', 'created_at'], 'integer'],
            [['product_name', 'amount', 'each_amount', 'all_amount', 'purchase_price', 'wholesale_price', 'retail_price'], 'required'],
            [['product_name'], 'string', 'max' => 200],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
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
            'product_name' => 'Product Name',
            'amount' => 'Amount',
            'each_amount' => 'Each Amount',
            'all_amount' => 'All Amount',
            'purchase_price' => 'Purchase Price',
            'wholesale_price' => 'Wholesale Price',
            'retail_price' => 'Retail Price',
            'created_at' => 'Created At',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'category_id' => function () {
                return $this->category;
            },
            'product_name',
            'amount',
            'each_amount',
            'all_amount' => function () {
                return $this->productRemain();
            },
            'purchase_price',
            'wholesale_price',
            'retail_price',
            'created_at'
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * @return bool
     */
    public function addNewProductAmount()
    {
        $product_amount = new ProductAmount();
        $this->save();
        $product_amount->product_id = $this->id;
        $product_amount->has_came_product = $this->all_amount;
        $product_amount->sold_product = 0;
        $product_amount->remaining_product = $this->all_amount;

        return $product_amount->save();
    }

    public function updateProductAmount()
    {
        $product_amount = ProductAmount::findOne(['product_id' => $this->id]);
        $product_amount->has_came_product = $this->all_amount;
        $product_amount->remaining_product = $product_amount->has_came_product - $product_amount->sold_product;
        return $product_amount->save();
    }

    private function productRemain(): ?int
    {
        $productAmount = ProductAmount::findOne($this->id);
        return $productAmount->remaining_product;
    }
}
