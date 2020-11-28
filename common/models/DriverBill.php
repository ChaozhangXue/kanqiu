<?php

namespace common\models;

/**
 * This is the model class for table "{{%driver_bill}}".
 *
 * @property int $id
 * @property int $bill_type 订单类型 1:包裹订单 2:客运订单
 * @property int $order_id 订单ID
 * @property string $order_no 订单编码
 * @property int $driver_id 司机ID
 * @property string $driver_name 司机姓名
 * @property int $customer_id 会员ID
 * @property int $pay_method 支付方式 1:支付宝 2:微信
 * @property string $transaction_id 交易流水号
 * @property string $amount 账单金额
 * @property string $commission 佣金
 * @property int $package_num 包裹数量
 * @property int $status 状态 1:未结算 2:已结算
 * @property int $order_time 下单时间
 * @property int $verify_time 审核时间
 * @property int $pay_time 支付时间
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class DriverBill extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%driver_bill}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bill_type', 'order_id', 'driver_id', 'pay_method', 'package_num', 'status', 'order_time', 'verify_time', 'pay_time', 'create_time', 'update_time'], 'integer'],
            [['order_id', 'order_no', 'driver_id'], 'required'],
            [['amount', 'commission'], 'number'],
            [['order_no', 'driver_name', 'transaction_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bill_type' => '账单类型',
            'order_id' => '订单ID',
            'order_no' => '订单编号',
            'driver_id' => '司机ID',
            'driver_name' => '司机名称',
            'customer_id' => '会员ID',
            'pay_method' => '支付方式',
            'transaction_id' => '交易流水号',
            'amount' => '订单总额',
            'commission' => '佣金',
            'package_num' => '包裹数量',
            'status' => '结算状态',
            'order_time' => '下单时间',
            'verify_time' => '结算时间',
            'pay_time' => '支付时间',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
