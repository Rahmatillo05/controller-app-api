<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "debt_history_list".
 *
 * @property int $id
 * @property int|null $selling_id
 * @property int|null $history_id
 *
 * @property DebtHistory $history
 * @property Selling $selling
 */
class DebtHistoryList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'debt_history_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['selling_id', 'history_id'], 'integer'],
            [['history_id'], 'exist', 'skipOnError' => true, 'targetClass' => DebtHistory::class, 'targetAttribute' => ['history_id' => 'id']],
            [['selling_id'], 'exist', 'skipOnError' => true, 'targetClass' => Selling::class, 'targetAttribute' => ['selling_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'selling_id' => 'Selling ID',
            'history_id' => 'History ID',
        ];
    }
    public function fields()
    {
        return [
            'id',
            'selling_id' => function (){
                return $this->selling;
            }
        ];
    }

    /**
     * Gets query for [[History]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistory()
    {
        return $this->hasOne(DebtHistory::class, ['id' => 'history_id']);
    }

    /**
     * Gets query for [[Selling]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSelling()
    {
        return $this->hasOne(Selling::class, ['id' => 'selling_id']);
    }
}
