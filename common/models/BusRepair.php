<?php

namespace common\models;

/**
 * This is the model class for table "bus_repair".
 *
 * @property int $id
 * @property int $customer_id 司机ID
 * @property string $name 报修人
 * @property string $phone 电话号码
 * @property string $repair_card 报修车牌
 * @property string $reason 报修原因
 * @property string $pic 照片
 * @property string $remark 备注
 * @property string $feedback_msg 回复信息
 * @property string $status 受理状态 1:未受理 2 已受理
 * @property int $create_time
 * @property int $update_time
 */
class BusRepair extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bus_repair';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reason', 'pic', 'remark', 'feedback_msg'], 'string'],
            [['customer_id', 'create_time', 'update_time', 'status'], 'integer'],
            [['name', 'phone', 'repair_card'], 'string', 'max' => 50],
            [['feedback_msg', 'remark'], 'default', 'value' => ''],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => '司机ID',
            'name' => '报修人',
            'phone' => '电话号码',
            'repair_card' => '报修车牌',
            'reason' => '报修原因',
            'pic' => '照片',
            'remark' => '备注',
            'feedback_msg' => '回复',
            'status' => '受理状态',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
