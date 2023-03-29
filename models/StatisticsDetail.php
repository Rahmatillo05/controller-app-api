<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "statistics_detail".
 *
 * @property int $id
 * @property int|null $period_id
 * @property float|null $on_cash
 * @property float|null $on_plastic
 * @property float|null $on_debt
 * @property float|null $other_spent
 * @property float|null $plastic_percent
 * @property float|null $product_sum
 *
 * @property Statistics $period
 */
class StatisticsDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'statistics_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['period_id'], 'integer'],
            [['on_cash', 'on_plastic', 'on_debt', 'other_spent', 'plastic_percent', 'product_sum'], 'number'],
            [['period_id'], 'exist', 'skipOnError' => true, 'targetClass' => Statistics::class, 'targetAttribute' => ['period_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'period_id' => 'Period ID',
            'on_cash' => 'On Cash',
            'on_plastic' => 'On Plastic',
            'on_debt' => 'On Debt',
            'other_spent' => 'Other Spent',
            'plastic_percent' => 'Plastic Percent',
            'product_sum' => 'Product Sum',
        ];
    }

    /**
     * Gets query for [[Period]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriod()
    {
        return $this->hasOne(Statistics::class, ['id' => 'period_id']);
    }
}
