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
    public static function tableName(): string
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

    private function totalSpent()
    {
        $lastDayUnix = strtotime('today');
        $sales = Selling::find()
            ->where(['between', 'created_at', $lastDayUnix, $lastDayUnix + 86399])
            ->all();
        $total_spent = 0;
        foreach ($sales as $sale) {
            $total_spent += $this->productSum($sale->product_id, $sale->sell_amount);
            if ($sale->type_pay == Selling::PAY_DEBT) {
                $total_spent += $this->debt($sale->id);
            }
        }

        return $total_spent;
    }

    private function totalBenefit()
    {
        return (new StatisticsDetail())->getSelling();
    }

    private function productSum($product_id, $amount)
    {
        $product = Product::findOne($product_id);

        return $product->purchase_price * $amount;
    }

    public function debt($selling_id)
    {
        $debt_history = DebtHistoryList::findOne(['selling_id' => $selling_id]);

        return $debt_history->history->debt_amount - $debt_history->history->pay_amount;
    }

    public function pureBenefit()
    {
        return $this->totalBenefit() - $this->totalSpent();
    }


}
