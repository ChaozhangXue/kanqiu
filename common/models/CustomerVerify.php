<?php

namespace common\models;

/**
 * This is the model class for table "{{%customer_verify}}".
 *
 * @property int $verify_id 认证ID
 * @property int $customer_id 会员ID
 * @property string $front_photo 正面照片
 * @property string $back_photo 反面照片
 * @property string $realname 用户真实姓名
 * @property string $idcard 身份证号
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $verify_status 认证状态
 */
class CustomerVerify extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer_verify}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id'], 'required'],
            [['customer_id', 'create_time', 'update_time', 'verify_status', 'gender'], 'integer'],
            [['front_photo', 'back_photo'], 'string', 'max' => 255],
            [['verify_response'], 'string'],
            [['realname'], 'string', 'max' => 64],
            [['idcard', 'mobile'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'verify_id' => 'ID',
            'customer_id' => '会员ID',
            'front_photo' => '身份证正面照',
            'back_photo' => '身份证反面照',
            'mobile' => '联系电话',
            'gender' => '性别',
            'realname' => '真实姓名',
            'idcard' => '身份证号',
            'verify_status' => '认证状态',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'verify_response' => '认证信息',
        ];
    }
}
