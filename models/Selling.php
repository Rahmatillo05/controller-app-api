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

    public function setProductAmount($sold_product_amount, $product_id): bool
    {
        $product = ProductAmount::findOne(['product_id' => $product_id]);
        $product->sold_product += $sold_product_amount;
        if ($product->remaining_product >= $product->sold_product) {
            $product->remaining_product -= $product->sold_product;
            return $product->save();
        } else {
            throw new ServerErrorHttpException("Sotilayotgan mahsulot hajmi qolgan mahsulotdan ko'p!");
        }
    }
}
