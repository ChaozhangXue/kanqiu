<?php

namespace common\models;

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
class SmsVerifyCode extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sms_verify_code%}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mobile', 'code'], 'required'],
            [['create_time', 'expire_time', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '用户类型',
            'mobile' => '手机号',
            'code' => '验证码',
            'create_time' => '创建时间',
            'expire_time' => '过期时间',
            'status' => '状态',
        ];
    }

}
