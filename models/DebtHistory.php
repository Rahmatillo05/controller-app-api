<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "debt_history".
 *
 * @property int $id
 * @property int|null $worker_id
 * @property int|null $debtor_id
 * @property int $debt_amount
 * @property int|null $pay_amount
 * @property int $type_pay
 * @property int|null $created_at
 *
 * @property DebtHistoryList[] $debtHistoryLists
 * @property Debtor $debtor
 * @property User $worker
 */
class DebtHistory extends \yii\db\ActiveRecord
{

    const PAY_ONLINE = 0; # Plastikka
    const PAY_CASH = 10; # Naqd pulga

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'debt_history';
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
                'createdByAttribute' => 'worker_id',
                'updatedByAttribute' => false
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['worker_id', 'debtor_id', 'type_pay', 'debt_amount', 'pay_amount', 'created_at'], 'integer'],
            [['debt_amount'], 'required'],
            [['debtor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Debtor::class, 'targetAttribute' => ['debtor_id' => 'id']],
            [['worker_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['worker_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'worker_id' => 'Worker ID',
            'debtor_id' => 'Debtor ID',
            'type_pay' => 'Type Pay',
            'debt_amount' => 'Debt Amount',
            'pay_amount' => 'Pay Amount',
            'created_at' => 'Created At',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'worker_id' => function () {
                return $this->worker;
            },
            'debt_amount',
            'pay_amount',
            'type_pay',
            'created_at',
            'history_list' => function () {
                return $this->debtHistoryLists;
            }
        ];
    }

    /**
     * Gets query for [[DebtHistoryLists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDebtHistoryLists()
    {
        return $this->hasMany(DebtHistoryList::class, ['history_id' => 'id']);
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
     * Gets query for [[Worker]].
     *
     * @return array
     */
    public function getWorker()
    {
        $worker = User::findOne($this->worker_id);

        return [
            'first_name' => $worker->first_name,
            'last_name' => $worker->last_name,
            'id' => $worker->id
        ];
    }
}
