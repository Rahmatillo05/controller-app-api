<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\web\UnauthorizedHttpException;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $password
 * @property string $phone_number
 * @property string|null $auth_key
 * @property int|null $user_role
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    const ROLE_ADMIN = 10;
    const ROLE_SELLER = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name',  'phone_number'], 'required'],
            [['user_role', 'status', 'created_at', 'updated_at'], 'integer'],
            [['first_name', 'last_name', 'username', 'password', 'phone_number'], 'string', 'max' => 150],
            [['auth_key'], 'string', 'max' => 250],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'Ism',
            'last_name' => 'Familiya',
            'username' => 'Foydalanuvchi nomi',
            'password' => 'Parol',
            'phone_number' => 'Telefon raqami',
            'auth_key' => 'Auth Key',
            'user_role' => 'User Role',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * findAdminByUsername
     *
     * @param  mixed $username
     * @return User|null
     */
    public static function findAdminByUsername($username)
    {
        return self::findOne(['username' => $username, 'user_role' => self::ROLE_ADMIN]);
    }


    /**
     * @param $username
     * @return User|null
     */
    public static function findSellerByUsername($username)
    {
        return self::findOne(['username' => $username, 'user_role' => self::ROLE_SELLER]);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = UserToken::findOne(['token' => $token]);
        if (!empty($user)) {
            return self::findOne(['id' => $user->user_id]);
        } else{
            throw new UnauthorizedHttpException("Sizning yuborgan token eskirgan yoki mavjud emas!");
        }
    }
    public function getId()
    {
        return $this->id;
    }
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function fields()
    {
        return [
            'id',
            'first_name',
            'last_name',
            'username',
            'password' => function () {
                return $this->username;
            },
            'phone_number',
            'user_role',
            'created_at',
            'updated_at'
        ];
    }
}
