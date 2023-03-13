<?php

namespace app\models;


use Yii;
use yii\base\Model;

class ChangePassword extends Model
{

    public $old_password;
    public $new_password;
    public $confirm_password;

    public $new_username;

    public function rules()
    {
        return [
            [['old_password', 'new_password', 'confirm_password', 'new_username'], 'required'],
            [['old_password', 'new_password', 'confirm_password'], 'string', 'length' => [8, 16]],
            [['new_username'], 'string', 'min' => 3],
            [['confirm_password'], 'compare', 'compareAttribute' => 'new_password', 'message' => "{attribute} maydoni teng bo'lishi kerak {compareAttribute} maydoniga"]
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Foydalanuvchi nomi',
            'old_password' => 'Amaldagi parol',
            'new_password' => 'Yanngi parol',
            'confirm_password' => 'Parolni tasdiqlash',
        ];
    }

    public function save(): bool
    {
        $user = User::findOne(['id' => Yii::$app->user->id]);

        if ($user != null) {
            if ($user->validatePassword($this->old_password)) {
                $user->setPassword($this->new_password);
                $user->username = $this->new_username;
                return $user->save();
            } else {
                $this->addError($this->old_password, 'Parol noto\'g\'ri.');
            }
        }
        return false;
    }
}
