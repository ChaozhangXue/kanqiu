<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_info".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $phone
 * @property string $create_time
 * @property string $update_time
 */
class UserInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['username', 'email'], 'string', 'max' => 255],
            [['password', 'phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
            'phone' => 'Phone',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
