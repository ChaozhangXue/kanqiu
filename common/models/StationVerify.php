<?php

namespace common\models;

/**
 * This is the model class for table "{{%station_verify}}".
 *
 * @property int $verify_id 认证ID
 * @property int $customer_id 会员ID
 * @property int $station_id 站点ID
 * @property string $front_photo 正面照片
 * @property string $back_photo 反面照片
 * @property string $realname 用户真实姓名
 * @property string $idcard 身份证号
 * @property string $remark 备注
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $verify_status 认证状态
 * @property string $verify_response 认证返回信息
 */
class StationVerify extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%station_verify}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'station_id'], 'required'],
            [['customer_id', 'station_id', 'create_time', 'update_time', 'verify_status'], 'integer'],
            [['remark', 'verify_response'], 'string'],
            [['front_photo', 'back_photo'], 'string', 'max' => 255],
            [['realname'], 'string', 'max' => 64],
            [['idcard'], 'string', 'max' => 32],
        ];
    }

    public function getStation()
    {
        return $this->hasOne(ServiceStation::className(), ['id' => 'station_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'verify_id' => 'ID',
            'customer_id' => '站点用户ID',
            'station_id' => '站点ID',
            'front_photo' => '正面照片',
            'back_photo' => '反面照片',
            'realname' => '真实姓名',
            'idcard' => '身份证号',
            'remark' => '备注',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'verify_status' => '认证状态',
            'verify_response' => '认证信息',
        ];
    }
}
