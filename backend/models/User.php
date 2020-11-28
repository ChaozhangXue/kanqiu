<?php

namespace backend\models;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $create_time
 * @property int $updated_at
 * @property string $verification_token
 */
class User extends \common\models\User
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'realname', 'dept', 'job_position', 'mobile'], 'required'],
            [['status',], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token', 'dept', 'job_position'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['created_at', 'updated_at', 'avatar'], 'safe'],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'realname' => '真实姓名',
            'mobile' => '联系电话',
            'password_hash' => '密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'dept' => '部门',
            'job_position' => '岗位',
            'status' => '状态',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'verification_token' => 'Verification Token',
        ];
    }
}
