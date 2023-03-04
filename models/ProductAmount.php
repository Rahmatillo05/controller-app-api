<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_amount".
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $has_came_product
 * @property int|null $sold_product
 * @property int|null $remaining_product
 *
 * @property Product $product
 */
class ProductAmount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_amount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'has_came_product', 'sold_product', 'remaining_product'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'has_came_product' => 'Has Came Product',
            'sold_product' => 'Sold Product',
            'remaining_product' => 'Remaining Product',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}
