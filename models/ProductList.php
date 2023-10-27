<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_list".
 *
 * @property int $id
 * @property int|null $supplier_id
 * @property int|null $date
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property StorageProduct[] $storageProducts
 * @property Supplier $supplier
 */
class ProductList extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplier_id', 'date', 'status', 'created_at', 'updated_at'], 'integer'],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::class, 'targetAttribute' => ['supplier_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_id' => 'Supplier ID',
            'date' => 'Date',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[StorageProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStorageProducts()
    {
        return $this->hasMany(StorageProduct::class, ['product_list_id' => 'id']);
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::class, ['id' => 'supplier_id']);
    }
}
