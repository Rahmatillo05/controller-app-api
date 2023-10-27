<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "plastic_card_tax".
 *
 * @property int $id
 * @property float|null $tax_amount
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PlasticCardTax extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plastic_card_tax';
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
                return round($this->tax_amount, 1);
            },
            'created_at',
            'updated_at'
        ];
    }

    public function calcSum()
    {
        $mixSelling = MixSelling::find()->sum('on_plastic') ?? 0;
        $selling = Selling::find()->where(['type_pay' => Selling::PAY_ONLINE])->sum('sell_price') ?? 0;
        $taxAmount = self::find()->orderBy(['id' => SORT_DESC])->one();
        $debtInstantPayment = DebtHistory::find()->where(['type_pay' => DebtHistory::PAY_ONLINE])->sum('pay_amount') ?? 0;
        $payHistory = PaymentHistoryList::find()->where(['type_pay' => PaymentHistoryList::PAY_ONLINE])->sum('pay_amount') ?? 0;
        $plasticPay = $mixSelling + $selling + $debtInstantPayment + $payHistory;
        return $plasticPay * ($taxAmount->tax_amount / 100);
    }
}
