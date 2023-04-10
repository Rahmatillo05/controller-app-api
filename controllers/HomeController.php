<?php

namespace app\controllers;

use app\models\OldDebt;
use app\models\OtherSpent;
use app\models\PlasticCardTax;
use app\models\Product;
use app\models\Selling;
use app\models\Statistics;
use app\models\StatisticsDetail;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class HomeController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }
    public function actionIndex()
    {
        $all_sum = Product::find()->select(['SUM(all_amount * purchase_price) AS total_amount'])->scalar();
        $all_sum += OldDebt::find()->sum('amount');
        $data['product_sum'] = $all_sum;
        $data['other_spent'] = OtherSpent::find()->sum('sum') ?? 0;
        $data['selling_sum'] = Selling::find()->sum('sell_price') ?? 0;
        $data['plastic_card'] = round(StatisticsDetail::find()->sum('plastic_percent'), 1);
        $data['last_week_statistics'] = Statistics::find()
            ->where(['MONTH(period)' => date('m')])->orderBy(['id' => SORT_DESC])
            ->all();

        return $data;
    }
}
