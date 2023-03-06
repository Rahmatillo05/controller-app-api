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

    public function addNewDebtor($debt_amount = 0, $paid_amount = 0)
    {
        $payment = new PaymentHistory();
        if ($this->save()) {
            $payment->debtor_id = $this->id;
            $payment->debt_amount = $debt_amount;
            $payment->paid_amount = $paid_amount;
            $payment->remaining_amount = $payment->debt_amount - $payment->paid_amount;
            return $payment->save();
        }
        return $this->errors;
    }
}
