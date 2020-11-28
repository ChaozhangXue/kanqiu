<?php

namespace common\models;

/**
 * This is the model class for table "{{%bus_order_out_info}}".
 *
 * @property int $id
 * @property int $order_id 订单ID
 * @property string $order_no 订单号
 * @property string $detail
 * @property int $type 1给司机看 2给会员看
 * @property int $create_time
 */
class BusOrderOutInfo extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bus_order_out_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'type', 'create_time'], 'integer'],
            [['order_no'], 'string', 'max' => 64],
            [['detail'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'order_no' => 'Order No',
            'detail' => 'Detail',
            'type' => 'Type',
            'create_time' => 'Create Time',
        ];
    }
}
