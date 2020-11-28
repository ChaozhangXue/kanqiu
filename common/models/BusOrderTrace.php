<?php

namespace common\models;

/**
 * This is the model class for table "{{%bus_order_trace}}".
 *
 * @property int $id
 * @property int $order_id 订单ID
 * @property string $operator 操作人
 * @property string $detail 操作内容
 * @property int $create_time
 */
class BusOrderTrace extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bus_order_trace}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'create_time'], 'integer'],
            [['operator', 'detail'], 'string', 'max' => 255],
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
            'operator' => 'Operator',
            'detail' => 'Detail',
            'create_time' => 'Create Time',
        ];
    }
}
