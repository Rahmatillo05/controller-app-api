<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "payment_history_list".
 *
 * @property int $id
 * @property int|null $debtor_id
 * @property int|null $pay_amount
 * @property int|null $created_at
 *
 * @property Debtor $debtor
 */
class PaymentHistoryList extends \yii\db\ActiveRecord
{
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
            [['debtor_id', 'pay_amount', 'created_at'], 'integer'],
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
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Debtor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDebtor()
    {
        return $this->hasOne(Debtor::class, ['id' => 'debtor_id']);
    }
}
