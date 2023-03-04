<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $category_name
 * @property int|null $unit
 * @property int|null $created_at
 *
 * @property Product[] $products
 */
class Category extends \yii\db\ActiveRecord
{
    const UNIT_KG = 0;
    const UNIT_EACH = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
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
            [['category_name'], 'required'],
            [['unit', 'created_at'], 'integer'],
            [['category_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
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
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }
}
