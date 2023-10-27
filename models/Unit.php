<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "unit".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $short_name
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Category[] $categories
 * @property Product[] $products
 * @property StorageProduct[] $storageProducts
 */
class Unit extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'unit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'short_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'short_name' => 'Short Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return ActiveQuery
     */
    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['unit_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return ActiveQuery
     */
    public function getProducts(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['unit_id' => 'id']);
    }

    /**
     * Gets query for [[StorageProducts]].
     *
     * @return ActiveQuery
     */
    public function getStorageProducts(): ActiveQuery
    {
        return $this->hasMany(StorageProduct::class, ['unit_id' => 'id']);
    }
}
