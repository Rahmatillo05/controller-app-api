<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "supplier".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $phone
 * @property string|null $address
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property ProductList[] $productLists
 */
class Supplier extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'supplier';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'phone', 'address'], 'string', 'max' => 255],
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
            'phone' => 'Phone',
            'address' => 'Address',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[ProductLists]].
     *
     * @return ActiveQuery
     */
    public function getProductLists(): ActiveQuery
    {
        return $this->hasMany(ProductList::class, ['supplier_id' => 'id']);
    }
}
