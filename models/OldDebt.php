<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "old_debt".
 *
 * @property int $id
 * @property int|null $debtor_id
 * @property float|null $amount
 * @property int|null $created_at
 *
 * @property Debtor $debtor
 */
class OldDebt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'old_debt';
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
            [['debtor_id', 'created_at'], 'integer'],
            [['amount'], 'number'],
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
            'amount' => 'Amount',
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
