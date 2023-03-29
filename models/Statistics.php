<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "statistics".
 *
 * @property int $id
 * @property string $period
 * @property float|null $total_spent
 * @property float|null $total_benefit
 * @property float|null $pure_benefit
 *
 * @property StatisticsDetail[] $statisticsDetails
 */
class Statistics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'statistics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['period'], 'required'],
            [['total_spent', 'total_benefit', 'pure_benefit'], 'number'],
            [['period'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'period' => 'Period',
            'total_spent' => 'Total Spent',
            'total_benefit' => 'Total Benefit',
            'pure_benefit' => 'Pure Benefit',
        ];
    }

    /**
     * Gets query for [[StatisticsDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatisticsDetails()
    {
        return $this->hasMany(StatisticsDetail::class, ['period_id' => 'id']);
    }
}
