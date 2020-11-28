<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%customer_bill}}".
 *
 * @property int $id
 * @property int $bill_type 1:支付 2:退款
 * @property int $order_type 1客运订单
 * @property int $order_id 订单ID
 * @property string $order_no 订单编码
 * @property int $customer_id 会员ID
 * @property int $pay_method 支付方式 1:支付宝 2:微信
 * @property string $transaction_id 交易流水号
 * @property string $amount 账单金额
 * @property string $pay_money 支付金额
 * @property int $package_num 包裹数量
 * @property int $order_time 下单时间
 * @property int $pay_time 支付时间
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class CustomerBill extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer_bill}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bill_type', 'order_type', 'order_id', 'customer_id', 'pay_method', 'package_num', 'order_time', 'pay_time', 'create_time', 'update_time'], 'integer'],
            [['order_id', 'order_no'], 'required'],
            [['amount', 'pay_money'], 'number'],
            [['order_no', 'transaction_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bill_type' => 'Bill Type',
            'order_type' => 'Order Type',
            'order_id' => 'Order ID',
            'order_no' => 'Order No',
            'customer_id' => 'Customer ID',
            'pay_method' => 'Pay Method',
            'transaction_id' => 'Transaction ID',
            'amount' => 'Amount',
            'pay_money' => 'Pay Money',
            'package_num' => 'Package Num',
            'order_time' => 'Order Time',
            'pay_time' => 'Pay Time',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
