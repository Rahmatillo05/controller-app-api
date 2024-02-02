<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $category_name
 * @property int|null $unit
 * @property int|null $unit_id
 * @property int|null $created_at
 *
 * @property Product[] $products
 * @property Unit $unitModel
 */
class Category extends \yii\db\ActiveRecord
{
    const UNIT_KG = 0;
    const UNIT_EACH = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'category';
    }
    public function behaviors(): array
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
    public function rules(): array
    {
        return [
            [['category_name'], 'required'],
            [['unit', 'created_at', 'unit_id'], 'integer'],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::class, 'targetAttribute' => ['unit_id' => 'id']],
            [['category_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'category_name' => 'Category Name',
            'unit' => 'Unit',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return ActiveQuery
     */
    public function getProducts(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }

    public function getUnit(): ActiveQuery
    {
        return $this->hasOne(Unit::class, ['id' => 'unit_id']);
    }
    public function getStorageProducts(): ActiveQuery
    {
        return $this->hasMany(StorageProduct::class, ['product_id' => 'id']);
    }
}
