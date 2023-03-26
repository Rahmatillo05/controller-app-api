<?php

namespace app\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "user_token".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $token
 * @property int|null $created_at
 *
 * @property User $user
 */
class UserToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at'], 'integer'],
            [['token'], 'string', 'max' => 150],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'token' => 'Token',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @throws Exception
     */
    public function generateToken($user_id): ?string
    {
        $token = self::findOne(['user_id' => $user_id]);
        if (!$token) {
            $token = new $this;
            $token->user_id = $user_id;
            $token->token = Yii::$app->security->generateRandomString(42);
            $token->created_at = time();
            $token->save();
        }
        return $token->token;
    }
}
