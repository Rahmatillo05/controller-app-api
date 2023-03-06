<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment_history".
 *
 * @property int $id
 * @property int|null $debtor_id
 * @property int $debt_amount
 * @property int $paid_amount
 * @property int|null $remaining_amount
 *
 * @property Debtor $debtor
 * @property PaymentHistoryList[] $paymentHistoryLists
 */
class PaymentHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['debtor_id', 'debt_amount', 'paid_amount', 'remaining_amount'], 'integer'],
            [['debt_amount', 'paid_amount'], 'required'],
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
            'debt_amount' => 'Debt Amount',
            'paid_amount' => 'Paid Amount',
            'remaining_amount' => 'Remaining Amount',
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

    /**
     * Gets query for [[PaymentHistoryLists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentHistoryLists()
    {
        return $this->hasMany(PaymentHistoryList::class, ['history_id' => 'id']);
    }

    public function updateDebtAmount($debt_amount, $paid_amount)
    {
        $this->debt_amount += $debt_amount;
        $this->paid_amount += $paid_amount;
        $this->remaining_amount = $this->debt_amount - $this->paid_amount;
        return $this->save();
    }
}
