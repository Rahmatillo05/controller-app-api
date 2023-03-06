<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment_history_list".
 *
 * @property int $id
 * @property int|null $history_id
 * @property int|null $pay_amount
 * @property int|null $created_at
 *
 * @property PaymentHistory $history
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['history_id', 'pay_amount', 'created_at'], 'integer'],
            [['history_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentHistory::class, 'targetAttribute' => ['history_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'history_id' => 'History ID',
            'pay_amount' => 'Pay Amount',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[History]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistory()
    {
        return $this->hasOne(PaymentHistory::class, ['id' => 'history_id']);
    }
}
