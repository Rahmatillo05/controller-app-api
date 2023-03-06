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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'product_id' => 'Product ID',
            'worker_id' => 'Worker ID',
            'sell_price' => 'Sell Price',
            'sell_amount' => 'Sell Amount',
            'type_sell' => 'Type Sell',
            'type_pay' => 'Type Pay',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'sell_price',
            'product_id',
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

    /**
     * @throws ServerErrorHttpException
     */
    public function soldOnCash(array $products, $type_pay)
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
                $r = true;
            } else {
                $r = $model->errors;
            }
        }
        return $r;
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function saveWithDebtor($sellingList, $debtorData, $total_debt, $instant_payment)
    {
        $r = false;
        $selling_id = [];
        foreach ($sellingList as $item) {
            $this->category_id = $item['category_id'];
            $this->product_id = $item['product_id'];
            $this->type_sell = $item['type_sell'];
            $this->sell_amount = $item['sell_amount'];
            $this->sell_price = $item['sell_price'];
            $this->type_pay = self::PAY_DEBT;
            if ($this->setProductAmount($this->sell_amount, $this->product_id)) {
                $r = $this->save();
                $selling_id[] = $this->id;
            } else {
                throw new ServerErrorHttpException("Ma'lumotlarni saqlashda xatolik!");
            }
        }
        $debtor_id = $this->createDebtor($debtorData, $total_debt, $instant_payment);
        $debt_history_id = $this->createDebtHistory($debtor_id, $total_debt, $instant_payment);
        $this->createDebtHistoryList($debt_history_id, $selling_id);
        return $r;
    }

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

    public function saveWithoutDebtor($sellingList, $debtorData, $total_debt, $instant_payment)
    {
        $r = false;
        $selling_id = [];
        $debtor = Debtor::findOne($debtorData['id']);
        $payment_history = PaymentHistory::findOne(['debtor_id' => $debtorData['id']]);
        foreach ($sellingList as $item) {
            $this->category_id = $item['category_id'];
            $this->product_id = $item['product_id'];
            $this->type_sell = $item['type_sell'];
            $this->sell_amount = $item['sell_amount'];
            $this->sell_price = $item['sell_price'];
            $this->type_pay = self::PAY_DEBT;
            if ($this->setProductAmount($this->sell_amount, $this->product_id)) {
                $r = $this->save();
                $selling_id[] = $this->id;
            } else {
                throw new ServerErrorHttpException("Ma'lumotlarni saqlashda xatolik!");
            }
        }
        $payment_history->updateDebtAmount($total_debt, $instant_payment);
        $debt_history_id = $this->createDebtHistory($debtor->id, $total_debt, $instant_payment);
        $this->createDebtHistoryList($debt_history_id, $selling_id);
        return $r;
    }

    public function createDebtHistory($debtor, $total_debt, $instant_payment): int
    {
        $debt_history = new DebtHistory();
        $debt_history->debtor_id = $debtor;
        $debt_history->debt_amount = $total_debt;
        $debt_history->pay_amount = $instant_payment;
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

    private function createDebtor($debtorData, $total_debt, $instant_payment)
    {
        $debtor = new Debtor();
        $debtor->full_name = $debtorData['full_name'];
        $debtor->address = $debtorData['address'];
        $debtor->phone_number = $debtorData['phone_number'];
        if ($debtor->addNewDebtor($total_debt, $instant_payment)) {
            return $debtor->id;
        }
        return $debtor->errors;
    }
}
