<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "plastic_card_tax".
 *
 * @property int $id
 * @property float|null $tax_amount
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PlasticCardTax extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plastic_card_tax';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tax_amount'], 'number'],
            [['created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tax_amount' => 'Tax Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'tax_amount' => function () {
                return number_format($this->tax_amount, '0', '.', '');
            },
            'created_at',
            'updated_at'
        ];
    }

    public function calcSum()
    {
        $mixSelling = MixSelling::find()->sum('on_plastic' ?? 0);
        $selling = Selling::find()->where(['type_pay' => Selling::PAY_ONLINE])->sum('sell_price') ?? 0;
        return ($mixSelling + $selling) * ($this->tax_amount / 100);
    }
}
