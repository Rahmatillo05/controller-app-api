<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property string|null $url
 * @property int|null $code
 * @property string|null $error_file
 * @property int|null $error_line
 * @property string|null $data
 * @property string|null $header
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'error_line', 'created_at', 'updated_at'], 'integer'],
            [['data', 'header'], 'string'],
            [['url', 'error_file'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'code' => 'Code',
            'error_file' => 'Error File',
            'error_line' => 'Error Line',
            'data' => 'Data',
            'header' => 'Header',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
