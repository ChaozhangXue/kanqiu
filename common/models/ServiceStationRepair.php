<?php

namespace common\models;

/**
 * This is the model class for table "service_station_repair".
 *
 * @property int $id
 * @property string $name 报修人
 * @property string $phone 电话号码
 * @property string $time 报修时间
 * @property string $repair_station 报修站点
 * @property string $reason 报修原因
 * @property string $pic 照片
 * @property string $remark 备注
 * @property string $feedback_msg 备注
 * @property int $status 受理状态 1:未受理  2已受理
 * @property int $create_time
 * @property int $update_time
 */
class ServiceStationRepair extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_station_repair';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reason', 'pic', 'remark'], 'string'],
            [['status', 'create_time', 'update_time', 'customer_id'], 'integer'],
            [['name', 'phone', 'repair_station'], 'string', 'max' => 50],
            [['feedback_msg', 'remark'], 'default', 'value' => ''],
        ];
    }


    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => '站点用户ID',
            'name' => '报修人',
            'phone' => '电话号码',
            'time' => '报修时间',
            'repair_station' => '报修站点',
            'reason' => '报修原因',
            'pic' => '照片',
            'remark' => '备注',
            'feedback_msg' => '回复信息',
            'status' => '受理状态',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
