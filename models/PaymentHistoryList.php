<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "payment_history_list".
 *
 * @property int $id
 * @property int|null $debtor_id
 * @property int|null $pay_amount
 * @property int|null $created_at
 * @property int $type_pay
 * @property Debtor $debtor
 */
class PaymentHistoryList extends \yii\db\ActiveRecord
{
    const PAY_ONLINE = 0; # Plastikka
    const PAY_CASH = 10; # Naqd pulga
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_history_list';
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
            [['debtor_id', 'pay_amount', 'created_at', 'type_pay'], 'integer'],
            [['debtor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Debtor::class, 'targetAttribute' => ['debtor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'debtor_id' => 'Debtor ID',
            'pay_amount' => 'Pay Amount',
            'type_pay' => 'Type Pay',
            'created_at' => 'Created At',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'debtor_id',
            'pay_amount',
            'type_pay',
            'created_at'
        ];
    }

    /**
     * Gets query for [[Debtor]].
     *
     * @return ActiveQuery
     */
    public function getDebtor(): ActiveQuery
    {
        return $this->hasOne(Debtor::class, ['id' => 'debtor_id']);
    }
}
