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

    public function fields()
    {
        return [
            'period',
            'total_spent',
            'total_benefit',
            'pure_benefit',
            'detail' => function () {
                return $this->getDetail();
            }
        ];
    }

    public function getDetail(): ?StatisticsDetail
    {
        return StatisticsDetail::findOne(['period_id' => $this->id]);
    }

    public function saved(): bool
    {
        $this->total_spent = $this->totalSpent();
        $this->total_benefit = $this->totalBenefit();
        $this->pure_benefit = $this->total_benefit - $this->total_spent;
        $this->period = date('Y-m-d H:i');
        $this->save();
        return (new StatisticsDetail())->saved($this->id);
    }

    public function totalSpent()
    {
        $lastDayUnix = time() - 86400;
        return Product::find()
            ->select(['SUM(purchase_price) as total'])
            ->where(['created_at' => date('Y-m-d', $lastDayUnix)])
            ->scalar();
    }

    private function totalBenefit()
    {

    }
}
