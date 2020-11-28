<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "package_order".
 *
 * @property int $id
 * @property int $package_list_id 包裹码
 * @property int $station_check 服务站确认 1：未操作 2：确认 3：退回
 * @property string $delivery_num 快递编号
 * @property string $pay_order_no 微信的订单id
 * @property string $transaction_id
 * @property string $order_num 订单编号
 * @property int $customer_id 寄件的用户id
 * @property string $sender 寄件人
 * @property string $sender_phone 寄件电话
 * @property string $send_address 寄件地址
 * @property string $sender_point 寄件的地址经纬度，英文逗号隔开   经度,纬度
 * @property string $receiver 收件人
 * @property string $receiver_phone 收件电话
 * @property string $receive_address 收件地址
 * @property string $receive_point 收件地址经纬度，英文逗号隔开   经度,纬度
 * @property string $type 物品类型
 * @property int $weight 重量 (1: 0-10kg, 2: 11-15kg 3: 16-20kg)
 * @property int $size 物品体积 (1: ≤0.03m³  2: ≤0.045m³ 3: ≤0.06m³)
 * @property int $distance 里程 (1: 15㎞ 2:16-50㎞  3:51㎞以上)
 * @property string $express_company 快递公司
 * @property int $is_on_door 是否送货上门 1：不是 2:是
 * @property string $sender_backup 寄件备注
 * @property string $deliver_time 配送时间
 * @property string $station_time 站点签收时间
 * @property string $receiver_time 收件人签收时间
 * @property int $source 订单来源 1手动录入、2站点提交
 * @property string $total_account 包裹的总价
 * @property int $receive_station_id 收货的服务站点id
 * @property int $status 包裹订单状态 1:待付款 2:待发货 3贴了标签的待发货 4 生成订单的待发货  5 配送中 6 已签收 7 已完成
 * @property int $submit_station_id 提交的服务站点的id
 * @property int $pay_method 1 微信 2 支付宝
 * @property int $pay_time 支付时间
 * @property string $submit_station_name 提交的服务站点的名
 * @property string $submit_station_phone 提交站点电话
 * @property string $receive_station_name 收货的服务站点名
 * @property string $receive_station_phone 收获站的电话
 * @property string $created_at 包裹订单创建时间
 * @property string $updated_at
 */
class PackageOrder extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'package_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['package_list_id', 'station_check', 'customer_id', 'weight', 'size', 'distance', 'is_on_door', 'source', 'receive_station_id', 'status', 'submit_station_id', 'pay_method', 'pay_time'], 'integer'],
            [['weight', 'size', 'distance'], 'required'],
            [['sender_backup'], 'string'],
            [['deliver_time', 'station_time', 'receiver_time', 'created_at', 'updated_at'], 'safe'],
            [['total_account'], 'number'],
            [['delivery_num', 'pay_order_no', 'order_num', 'sender', 'sender_phone', 'send_address', 'sender_point', 'receiver', 'receiver_phone', 'receive_address', 'receive_point', 'type', 'express_company', 'submit_station_name', 'submit_station_phone', 'receive_station_name', 'receive_station_phone'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'package_list_id' => '包裹码',
            'station_check' => '服务站确认 1：未操作 2：确认 3：退回',
            'delivery_num' => '快递编号',
            'pay_order_no' => '微信的订单id',
            'transaction_id' => 'Transaction ID',
            'order_num' => '订单编号',
            'customer_id' => '寄件的用户id',
            'sender' => '寄件人',
            'sender_phone' => '寄件电话',
            'send_address' => '寄件地址',
            'sender_point' => '寄件的地址经纬度，英文逗号隔开   经度,纬度',
            'receiver' => '收件人',
            'receiver_phone' => '收件电话',
            'receive_address' => '收件地址',
            'receive_point' => '收件地址经纬度，英文逗号隔开   经度,纬度',
            'type' => '物品类型',
            'weight' => '重量 (1: 0-10kg, 2: 11-15kg 3: 16-20kg)',
            'size' => '物品体积 (1: ≤0.03m³  2: ≤0.045m³ 3: ≤0.06m³)',
            'distance' => '里程 (1: 15㎞ 2:16-50㎞  3:51㎞以上)',
            'express_company' => '快递公司',
            'is_on_door' => '是否送货上门 1：不是 2:是',
            'sender_backup' => '寄件备注',
            'deliver_time' => '配送时间',
            'station_time' => '站点签收时间',
            'receiver_time' => '收件人签收时间',
            'source' => '订单来源 1手动录入、2站点提交',
            'total_account' => '包裹的总价',
            'receive_station_id' => '收货的服务站点id',
            'status' => '包裹订单状态 1:待付款 2:待发货 3贴了标签的待发货 4 生成订单的待发货  5 配送中 6 已签收 7 已完成',
            'submit_station_id' => '提交的服务站点的id',
            'pay_method' => '1 微信 2 支付宝',
            'pay_time' => '支付时间',
            'submit_station_name' => '提交的服务站点的名',
            'submit_station_phone' => '提交站点电话',
            'receive_station_name' => '收货的服务站点名',
            'receive_station_phone' => '收获站的电话',
            'created_at' => '包裹订单创建时间',
            'updated_at' => 'Updated At',
        ];
    }



    public function extraFields()
    {
        return [
            "trace" => function () {
//            print_r($this->id   );die;
                $trace = [];

                if (empty($this->id)) {
                    $trace = PackageTrace::find()->where(['package_id' => $this->id])->orderBy('id desc')->asArray()->all();
                }
                return $trace;

                //先用package order的 id 然后换成order id 再去order trace 拿
//                $order = Order::find()
//                    ->select('id', 'package_id_list')
//                    ->where(" FIND_IN_SET(" . $this->id . ",package_id_list)")
//                    ->orderBy('id desc')
//                    ->one();
//
//                if (!empty($order)) {
//                    $trace = OrderTrace::find()->where(['order_id' => $order->id])
//                        ->orderBy('id desc')
//                        ->asArray()
//                        ->all();
//                }
//                return $trace;
            },
            "order" => function () {
                //小程序  获取托运信息 司机 号码 车牌
                //先用package order的 id 然后换成order id 再去order trace 拿

                $order = [];
                if(!empty($this->package_list_id) ) {
                    $order = Order::find()
                        ->where(" FIND_IN_SET(" . $this->package_list_id . ",package_id_list)")
                        ->orderBy('id desc')
                        ->all();
                }
                return $order;
            },
            'entity' => function (){
//                重量 * @property int $weight 重量 (1: 0-10kg, 2: 11-15kg 3: 16-20kg)
//体积* @property int $size 物品体积 (1: ≤0.03m³  2: ≤0.045m³ 3: ≤0.06m³)

                if($this->weight == 1){
                    $weight = '0-10kg';
                }elseif ($this->weight == 2){
                    $weight = '11-15kg';
                }elseif($this->weight == 3){
                    $weight = '16-20kg';
                }else{
                    $weight = '0';
                }

                if($this->size == 1){
                    $size = '≤0.03m³';
                }elseif ($this->size == 2){
                    $size = '≤0.045m³';
                }elseif($this->size == 3){
                    $size = '≤0.06m³';
                }else{
                    $size = '0';
                }

                return ['weight' => $weight, 'size' => $size];

            }
        ];
    }
}
