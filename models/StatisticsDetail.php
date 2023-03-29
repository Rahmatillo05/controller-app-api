<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

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
     * @return ActiveQuery
     */
    public function getPeriod(): ActiveQuery
    {
        return $this->hasOne(Statistics::class, ['id' => 'period_id']);
    }

    public function saved()
    {
        $this->product_sum = $this->productSum();
        $this->on_cash = $this->onCash();
    }

    private function productSum()
    {
        $lastDayUnix = strtotime('yesterday');

        return Product::find()
            ->select(['SUM(purchase_price * all_amount) as total'])
            ->where(['between', 'updated_at', $lastDayUnix, $lastDayUnix + 86399])
            ->scalar() ?? 0;
    }

    public function onCash()
    {
        $lastDayUnix = strtotime('yesterday');
        $selling = Selling::find()
            ->where(['between', 'created_at', $lastDayUnix, $lastDayUnix + 86399])
            ->andWhere(['type_pay' => Selling::PAY_CASH])
            ->sum('sell_price') ?? 0;
        $mix_selling = MixSelling::find()
            ->where(['between', 'created_at', $lastDayUnix, $lastDayUnix + 86399])
            ->sum('on_cash') ?? 0;
        return $selling + $mix_selling;
    }

}
