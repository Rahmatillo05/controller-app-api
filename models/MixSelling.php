<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "mix_selling".
 *
 * @property int $id
 * @property string $selling_ids
 * @property int $on_cash
 * @property int $on_plastic
 * @property int $created_at
 */
class MixSelling extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mix_selling';
    }

    public function behaviors()
    {
        return[
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
            [['selling_ids', 'on_cash', 'on_plastic'], 'required'],
            [['on_cash', 'on_plastic' ,'created_at'], 'integer'],
            [['selling_ids'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'selling_ids' => 'Selling Ids',
            'on_cash' => 'On Cash',
            'on_plastic' => 'On Plastic',
        ];
    }

    public function saved($selling_ids, $on_cash, $on_plastic)
    {
        $model = new $this;
        $ids = trim(implode(',', $selling_ids), ',');
        $model->selling_ids = $ids;
        $model->on_cash = $on_cash;
        $model->on_plastic = $on_plastic;

        return $model->save() ? true : $model->errors;
    }
}
