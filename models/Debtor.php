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
 * @property string $address
 * @property string|null $phone_number
 * @property int|null $worker_id
 * @property int|null $created_at
 */
class Debtor extends \yii\db\ActiveRecord
{
    /**
     * @var mixed|null
     */
    /**
     * @var mixed|null
     */

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
            [['full_name', 'address'], 'required'],
            [['worker_id', 'created_at'], 'integer'],
            [['full_name', 'address', 'phone_number'], 'string', 'max' => 255],
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
            'address' => 'Address',
            'phone_number' => 'Phone Number',
            'worker_id' => 'Worker ID',
            'created_at' => 'Created At',
        ];
    }

    public function addNewDebtor()
    {
        return $this->save() ?? $this->errors;
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
}
