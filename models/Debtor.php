<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "debtor".
 *
 * @property int $id
 * @property string $full_name
 * @property string|null $phone_number
 * @property int|null $worker_id
 * @property int|null $created_at
 *
 * @property DebtHistory[] $debtHistories
 * @property PaymentHistoryList[] $paymentHistoryLists
 * @property User $worker
 */
class Debtor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'debtor';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false
            ],
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
                'createdByAttribute' => 'worker_id'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name'], 'required'],
            [['worker_id', 'created_at'], 'integer'],
            [['full_name', 'phone_number'], 'string', 'max' => 255],
            [['worker_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['worker_id' => 'id']],
        ];
    }
    public function fields()
    {
        return [
            'id',
            'full_name',
            'phone_number',
            'worker_id' => function () {
                return $this->worker;
            },
            'debt_amount' => function(){
                return $this->allDebt();
            },
            'created_at',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Full Name',
            'phone_number' => 'Phone Number',
            'worker_id' => 'Worker ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[DebtHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDebtHistories()
    {
        return $this->hasMany(DebtHistory::class, ['debtor_id' => 'id']);
    }

    /**
     * Gets query for [[PaymentHistoryLists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentHistoryLists()
    {
        return $this->hasMany(PaymentHistoryList::class, ['debtor_id' => 'id']);
    }

    public function addNewDebtor()
    {
        return $this->save() ?? $this->errors;
    }

    public function getWorker()
    {
        $worker = User::findOne($this->worker_id);

        return [
            'id' => $worker->id,
            'first_name' => $worker->first_name,
            'last_name' => $worker->last_name,
            'address' => $worker->address,
            'phone_number' => $worker->phone_number,

        ];
    }

    public function debtAmount(): array
    {
        $all_debt_amount = DebtHistory::find()->where(['debtor_id' => $this->id])->sum('debt_amount') ?? 0;
        $paid_debt1 = DebtHistory::find()->where(['debtor_id' => $this->id])->sum('pay_amount');
        $paid_debt2 = PaymentHistoryList::find()->where(['debtor_id' => $this->id])->sum('pay_amount');
        $remaining_debt = $all_debt_amount - ($paid_debt1 + $paid_debt2);
        return [
            'all_debt_amount' => $all_debt_amount,
            'paid_debt' => $paid_debt1 + $paid_debt2,
            'remaining_debt_amount' => $remaining_debt,
        ];
    }

    public function allDebt()
    {
        return $this->debtAmount()['remaining_debt_amount'];
    }
}
