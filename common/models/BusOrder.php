<?php

namespace common\models;

/**
 * This is the model class for table "bus_order".
 *
 * @property int $id
 * @property int $customer_id 会员ID
 * @property int $add_type 添加类型 1自动 2手动
 * @property string $order_no 订单编号
 * @property string $title 订单标题
 * @property string $mobile 手机号
 * @property string $use_people 用车人
 * @property int $user_number 用车人数
 * @property int $start_time 出发时间
 * @property int $end_time 起止时间
 * @property double $money 订单金额
 * @property double $pay_money 支付金额
 * @property double $commission 佣金
 * @property int $car_type 车辆类型: 1:小巴士、2:中巴士、3:大巴士
 * @property int $order_type 业务类型 1.  旅游包车：客户发起，从A-B，点对点，先付后用
 * 2. 站点叫车：公交线路上的点位叫车，针对客流高峰
 * 3. 定制班车：学校或者公司，固定地点和时间接送车
 * @property int $seat_type 座位数 1.5座、2. 7座、3. 7座以上
 * @property int $pay_time 支付时间
 * @property int $pay_method 支付方式  1微信支付 2支付宝
 * @property int $transaction_id 交易流水号
 * @property string $driver_id 司机ID
 * @property string $driver_name 服务司机
 * @property string $driver_phone 司机电话
 * @property int $status 订单状态
 * @property string $dispatch_start 派车起点
 * @property string $dispatch_end 派车终点
 * @property string $remark 备注信息
 * @property string $reason 叫车原因
 * @property int $create_time
 * @property int $update_time
 */
class BusOrder extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bus_order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_time', 'end_time', 'pay_time'], 'safe'],
            [['money', 'pay_money', 'commission'], 'number'],
            [['total_times', 'scan_times', 'customer_id', 'driver_id', 'add_type', 'car_type', 'user_number', 'order_type', 'seat_type', 'pay_method', 'status', 'create_time', 'update_time'], 'integer'],
            [['remark', 'dispatch_start', 'dispatch_end', 'start_point', 'end_point', 'reason', 'remark', 'title', 'transaction_id', 'bus_card'], 'string'],
            [['order_no', 'mobile', 'use_people', 'driver_name', 'driver_phone', 'pay_order_no'], 'string', 'max' => 255],
        ];
    }

    public function insert($runValidation = true, $attributes = null)
    {
        if ($this->order_no == '') {
            $this->order_no = 'BO' . date('YmdHis') . str_pad(rand(000000, 999999), 6, '0', STR_PAD_LEFT);
        }
        if ($this->title == '') {
            $detail = \Yii::$app->params['bus_order_type_list'][$this->order_type];

            if ($this->order_type == 1) {
                $detail .= date('m.d H:i ', $this->start_time) . ' ' . $this->dispatch_start . '至' . $this->dispatch_end;
            } else if ($this->order_type == 2) {
                $detail .= date('m.d H:i ', $this->start_time) . ' ' . $this->dispatch_start;
            } else {
                $detail .= date('m.d', $this->start_time) . '至' . date('m.d', $this->end_time) . ' ' . date('H:i', $this->start_time) . ' ' . $this->dispatch_start . '至' . $this->dispatch_end;
            }
            $this->title = $detail;
        }
        return parent::insert($runValidation, $attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_no' => '订单编号',
            'add_type' => '添加类型',
            'customer_id' => '会员ID',
            'title' => '订单标题',
            'use_people' => '用车人',
            'user_number' => '预计人数',
            'mobile' => '联系电话',
            'start_time' => '出发时间',
            'end_time' => '结束时间',
            'money' => '订单金额',
            'pay_money' => '支付金额',
            'commission' => '佣金',
            'car_type' => '车辆类型',
            'order_type' => '业务类型',
            'seat_type' => '座位数',
            'pay_time' => '支付时间',
            'pay_method' => '支付方式',
            'transaction_id' => '交易流水号',
            'driver_id' => '司机ID',
            'driver_name' => '服务司机',
            'driver_phone' => '司机电话',
            'status' => '订单状态',
            'dispatch_start' => '派车起点',
            'start_point' => '起点经纬度',
            'end_point' => '终点经纬度',
            'dispatch_end' => '派车终点',
            'reason' => '叫车原因',
            'remark' => '备注信息',
            'scan_times' => '扫码次数',
            'total_times' => '扫码总次数',
            'pay_order_no' => '交易号',
            'bus_card' => '车牌号码',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
