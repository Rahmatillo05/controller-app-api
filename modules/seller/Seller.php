<?php

namespace app\modules\seller;

use app\models\User;
use Yii;
use yii\web\User as WebUser;

/**
 * seller module definition class
 */
class Seller extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\seller\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::$app->set('user', [
            'class' => WebUser::class,
            'enableAutoLogin' => true,
            'identityClass' => User::class,
            'loginUrl' => null,
            'identityCookie' => ['name' => 'seller', 'httpOnly' => true],
            'idParam' => 'seller'
        ]);
        // custom initialization code goes here
    }
}
