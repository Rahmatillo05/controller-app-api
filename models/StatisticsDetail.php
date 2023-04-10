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

    public function saved($period_id): bool
    {
        $this->period_id = $period_id;
        $this->product_sum = $this->productSum();
        $this->on_cash = $this->onCash();
        $this->on_plastic = $this->onPlastic();
        $this->on_debt = $this->onDebt();
        $this->other_spent = $this->otherSpent();
        $this->plastic_percent = $this->plasticPercent();
        return $this->save();
    }

    public function productSum()
    {
        $benefit = (new Statistics())->pureBenefit();
        $benefit -= $this->otherSpent() + $this->plasticPercent();

        return $benefit;
    }

    private function onCash()
    {
        $lastDayUnix = strtotime('today');
        $selling = Selling::find()
            ->where(['between', 'created_at', $lastDayUnix, $lastDayUnix + 86399])
            ->andWhere(['type_pay' => Selling::PAY_CASH])
            ->sum('sell_price') ?? 0;
        $mix_selling = MixSelling::find()
            ->where(['between', 'created_at', $lastDayUnix, $lastDayUnix + 86399])
            ->sum('on_cash') ?? 0;
        $payHistory = PaymentHistoryList::find()
            ->where(['between', 'created_at', $lastDayUnix, $lastDayUnix + 86399])
            ->andWhere(['type_pay' => PaymentHistoryList::PAY_CASH])
            ->sum('pay_amount') ?? 0;
        $debtInstantPay = DebtHistory::find()
            ->where(['between', 'created_at', $lastDayUnix, $lastDayUnix + 86399])
            ->andWhere(['type_pay' => DebtHistory::PAY_CASH])
            ->sum('pay_amount') ?? 0;
        return $selling + $mix_selling + $payHistory + $debtInstantPay;
    }

    private function onPlastic()
    {
        $lastDayUnix = strtotime('today');
        $selling = Selling::find()
            ->where(['between', 'created_at', $lastDayUnix, $lastDayUnix + 86399])
            ->andWhere(['type_pay' => Selling::PAY_ONLINE])
            ->sum('sell_price') ?? 0;
        $mix_selling = MixSelling::find()
            ->where(['between', 'created_at', $lastDayUnix, $lastDayUnix + 86399])
            ->sum('on_plastic') ?? 0;
        $payHistory = PaymentHistoryList::find()
            ->where(['between', 'created_at', $lastDayUnix, $lastDayUnix + 86399])
            ->andWhere(['type_pay' => PaymentHistoryList::PAY_ONLINE])
            ->sum('pay_amount') ?? 0;
        $debtInstantPay = DebtHistory::find()
            ->where(['between', 'created_at', $lastDayUnix, $lastDayUnix + 86399])
            ->andWhere(['type_pay' => DebtHistory::PAY_ONLINE])
            ->sum('pay_amount') ?? 0;
        return $selling + $mix_selling + $payHistory + $debtInstantPay;
    }

    private function onDebt()
    {
        $lastDayUnix = strtotime('today');
        return Selling::find()
            ->where(['between', 'created_at', $lastDayUnix, $lastDayUnix + 86399])
            ->andWhere(['type_pay' => Selling::PAY_DEBT])
            ->sum('sell_price') ?? 0;
    }

    private function otherSpent()
    {
        $lastDayUnix = strtotime('today');
        return OtherSpent::find()
            ->where(['between', 'created_at', $lastDayUnix, $lastDayUnix + 86399])
            ->sum('sum') ?? 0;
    }

    private function plasticPercent()
    {
        $tax_amount = (PlasticCardTax::find()->orderBy(['id' => SORT_DESC])->one())->tax_amount;
        return $this->onPlastic() * ($tax_amount / 100);
    }

    public function getPlastic()
    {
        return $this->plasticPercent();
    }

    public function getSelling()
    {
        $lastDayUnix = strtotime('today');
        return Selling::find()
            ->select(['SUM(sell_price) as total'])
            ->where(['between', 'created_at', $lastDayUnix, $lastDayUnix + 86399])
            ->scalar() ?? 0;
    }

}
