<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "selling".
 *
 * @property int $id
 * @property int|null $category_id
 * @property int|null $product_id
 * @property int|null $worker_id
 * @property int $sell_price
 * @property int $sell_amount
 * @property int|null $type_sell
 * @property int|null $type_pay
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Category $category
 * @property Product $product
 * @property User $worker
 */
class Selling extends \yii\db\ActiveRecord
{
    const TYPE_RETAIL = 0; # Optom
    const TYPE_GOOD = 10; # Chakana
    const PAY_ONLINE = 0; # Plastikka
    const PAY_DEBT = 5; # Qarzga
    const PAY_CASH = 10; # Naqd pulga
    const MIX_PAY = 15; # Plastic va naqd shakldagi to'lo'v
    /**
     * @var mixed|null
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'selling';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
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
            [['category_id', 'product_id', 'worker_id', 'sell_price', 'sell_amount', 'type_sell', 'type_pay', 'created_at', 'updated_at'], 'integer'],
            [['sell_price', 'sell_amount'], 'required'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['worker_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['worker_id' => 'id']],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'sell_price',
            'product_id' => function () {
                return $this->product;
            },
            'sell_amount',
            'type_sell',
            'type_pay',
            'created_at',
            'worker_id' => function () {
                return $this->getWorkerData();
            },
            'category_id' => function () {
                return Category::findOne($this->category_id);
            },
        ];
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function saveThis($products, $type_pay): array
    {
        $r = [];
        foreach ($products as $product) {
            $model = new $this;
            $model->category_id = $product['category_id'];
            $model->product_id = $product['product_id'];
            $model->type_sell = $product['type_sell'];
            $model->sell_amount = $product['sell_amount'];
            $model->sell_price = $product['sell_price'];
            $model->type_pay = $type_pay;
            if ($this->setProductAmount($model->sell_amount, $model->product_id) && $model->save()) {
                $r[] = $model->id;
            } else {
                $r = false;
            }
        }
        return $r;
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function soldOnCash(array $products, $type_pay): bool
    {
        if ($this->saveThis($products, $type_pay)) {
            return true;
        }
        throw new ServerErrorHttpException("Saqlashda xatolik bor!");
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function mixedSold(array $products, $type_pay, $on_cash, $on_plastic): bool
    {
        $mixSelling = new MixSelling();
        if ($ids = $this->saveThis($products, $type_pay)) {
            return $mixSelling->saved($ids, $on_cash, $on_plastic);
        }
        throw new ServerErrorHttpException("Saqlashda xatolik bor!");
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function saveWithDebtor($sellingList, $debtorData, $total_debt, $instant_payment): bool
    {
        $type_pay = self::PAY_DEBT;
        if ($selling_id = $this->saveThis($sellingList, $type_pay)) {
            $debtor_id = $this->createDebtor($debtorData);
            $debt_history_id = $this->createDebtHistory($debtor_id, $total_debt, $instant_payment, $type_pay);
            $this->createDebtHistoryList($debt_history_id, $selling_id);
            return true;
        }
        throw new ServerErrorHttpException("Saqlashda xatolik bor!");
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function setProductAmount($sold_product_amount, $product_id): bool
    {
        $product = ProductAmount::findOne(['product_id' => $product_id]);
        $product->sold_product += $sold_product_amount;
        $product->remaining_product = $product->has_came_product - $product->sold_product;
        if ($product->remaining_product >= 0) {
            return $product->save();
        } else {
            throw new ServerErrorHttpException("Sotilayotgan mahsulot hajmi qolgan mahsulotdan ko'p!");
        }
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function saveWithoutDebtor($sellingList, $debtorData, $total_debt, $instant_payment): bool
    {
        $type_pay = self::PAY_DEBT;
        if ($selling_id = $this->saveThis($sellingList, $type_pay)) {
            $debtor = Debtor::findOne($debtorData['id']);
            $debt_history_id = $this->createDebtHistory($debtor->id, $total_debt, $instant_payment, $type_pay);
            $this->createDebtHistoryList($debt_history_id, $selling_id);
            return true;
        }
        throw new ServerErrorHttpException("Saqlashda xatolik bor!");
    }

    public function createDebtHistory($debtor, $total_debt, $instant_payment, $type_pay): int
    {
        $debt_history = new DebtHistory();
        $debt_history->debtor_id = $debtor;
        $debt_history->debt_amount = $total_debt;
        $debt_history->pay_amount = $instant_payment;
        $debt_history->type_pay = $type_pay;
        if ($debt_history->save()) {
            return $debt_history->id;
        } else {
            throw new ServerErrorHttpException("{$debt_history->errors}");
        }
    }

    public function createDebtHistoryList($history_id, $selling_id): bool
    {
        $result = false;
        for ($i = 0; $i < count($selling_id); $i++) {
            $list = new DebtHistoryList();
            $list->history_id = $history_id;
            $list->selling_id = $selling_id[$i];
            $result = $list->save();
        }
        return $result;
    }

    private function createDebtor($debtorData)
    {
        $debtor = new Debtor();
        $debtor->full_name = $debtorData['full_name'];
        $debtor->phone_number = $debtorData['phone_number'];
        if ($debtor->addNewDebtor()) {
            return $debtor->id;
        }
        return $debtor->errors;
    }


    public function getWorkerData()
    {
        $worker = User::findOne($this->worker_id);

        return [
            'id' => $worker->id,
            'first_name' => $worker->first_name,
            'last_name' => $worker->last_name,
            'phone_number' => $worker->phone_number,
            'address' => $worker->address
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[Worker]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorker()
    {
        return $this->hasOne(User::class, ['id' => 'worker_id']);
    }
}
