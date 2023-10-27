<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_WAITING = 2;
    const TYPE_RETAIL = 0; # Optom
    const TYPE_GOOD = 10; # Chakana
    const PAY_ONLINE = 0; # Plastikka
    const PAY_DEBT = 5; # Qarzga
    const PAY_CASH = 10; # Naqd pulga
    const MIX_PAY = 15; # Plastic va naqd shakldagi to'lo'v

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
            ]
        ];
    }
    
}