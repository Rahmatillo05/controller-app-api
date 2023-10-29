<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

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
    public static function tableName(): string
    {
        return 'product_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['supplier_id', 'date', 'status', 'created_at', 'updated_at'], 'integer'],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::class, 'targetAttribute' => ['supplier_id' => 'id']],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
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

    public function extraFields(): array
    {
        return [
          'storageProducts',
          'supplier'
        ];
    }

    /**
     * Gets query for [[StorageProducts]].
     *
     * @return ActiveQuery
     */
    public function getStorageProducts(): ActiveQuery
    {
        return $this->hasMany(StorageProduct::class, ['product_list_id' => 'id']);
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return ActiveQuery
     */
    public function getSupplier(): ActiveQuery
    {
        return $this->hasOne(Supplier::class, ['id' => 'supplier_id']);
    }
}
