<?php

namespace app\controllers;

use app\models\OtherSpent;
use app\models\Product;
use app\models\Selling;
use app\models\Statistics;
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
        $data['product_sum'] = Product::find()->sum('purchase_price') ?? 0;
        $data['other_spent'] = OtherSpent::find()->sum('sum') ?? 0;
        $data['selling_sum'] = Selling::find()->sum('sell_price') ?? 0;
        $data['last_week_statistics'] = Statistics::find()->where(['>=', 'period', date('Y-m-d H:i', strtotime("-7 days"))])->all();

        return $data;
    }
}
