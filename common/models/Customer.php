<?php

namespace common\models;

use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%customer}}".
 *
 * @property int $customer_id
 * @property int $type 1司机账号 2会员账号
 * @property string $username 用户名
 * @property string $password 用户密码
 * @property string $realname 真实姓名
 * @property int $gender 性别 1男 2女
 * @property string $mobile 手机号
 * @property int $status 账号状态
 * @property int $last_login_time 最后登录时间
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $verify_status 认证状态 0未认证 1认证中 2已认证 3认证失败
 */
class Customer extends BaseModel implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mobile'], 'required'],
            [['type', 'gender', 'relation_id', 'status', 'last_login_time', 'create_time', 'update_time', 'verify_status'], 'integer'],
            [['username', 'password', 'realname', 'birth_date', 'realname', 'birth_date', 'nickname', 'avatar'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 36],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => 'ID',
            'type' => '用户类型',
            'nickname' => '昵称',
            'username' => '用户名',
            'password' => '密码',
            'realname' => '真实姓名',
            'gender' => '性别',
            'mobile' => '手机号',
            'avatar' => '头像',
            'token' => 'token',
            'openid' => '微信小程序openid',
            'birth_date' => '出生日期',
            'status' => '状态',
            'last_login_time' => '最后登录时间',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'relation_id' => '关联ID 如果type =3 的话 填对应的站点id',
            'verify_status' => '认证状态',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['customer_id' => $id, 'status' => 1]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        throw new NotSupportedException('"getAuthKey" is not implemented.');
    }

    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException('"validateAuthKey" is not implemented.');
    }


    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }

}
