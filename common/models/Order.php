<?php

namespace common\models;

/**
 * This is the model class for table "order".
 *
 * @property int $id 订单编号
 * @property string $package_id_list 包裹编号列表
 * @property int $receive_station_id 收货站点id
 * @property string $receive_station_name 收货站点名字
 * @property int $send_station_id 发货站点id
 * @property string $send_station_name 发货站点名字
 * @property int $driver_id 司机id
 * @property string $driver_name 司机姓名
 * @property string $driver_phone 司机电话
 * @property int $driver_accept_type 司机接受类型 0等待接受  1 接受 2 拒绝
 * @property string $bus_line 公交线路
 * @property int $bus_id 车辆id
 * @property string $bus_time 发车时间
 * @property string $card 车牌号码
 * @property string $deliver_time 配送时间
 * @property string $station_time 站点签收时间
 * @property string $receiver_time 收件签收时间
 * @property int $package_num 订单包裹数
 * @property int $source 订单来源 1总部、2站点
 * @property string $total_account 订单总价
 * @property string $yongjin 司机佣金
 * @property string $station_yongjin 站点佣金
 * @property string $detail 订单明细
 * @property int $status 订单状态 1:待指派 2代配送 3:配送中 4已签收 5已完成
 * @property int $type 1:发货订单 2: 取货订单
 * @property string $created_at 订单创建时间
 * @property string $updated_at
 */
class Order extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['package_id_list', 'receive_station_id', 'receive_station_name', 'send_station_id', 'send_station_name', 'package_num', 'status', 'created_at', 'updated_at'], 'required'],
            [['package_id_list', 'detail'], 'string'],
            [['receive_station_id', 'send_station_id', 'driver_id', 'driver_accept_type', 'bus_id', 'package_num', 'source', 'status', 'type'], 'integer'],
            [['deliver_time', 'station_time', 'receiver_time', 'created_at', 'updated_at'], 'safe'],
            [['total_account', 'yongjin', 'station_yongjin'], 'number'],
            [['receive_station_name', 'send_station_name', 'driver_name', 'driver_phone', 'bus_line', 'bus_time', 'card'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '订单编号',
            'package_id_list' => '包裹编号列表',
            'receive_station_id' => '收货站点id',
            'receive_station_name' => '收货站点名字',
            'send_station_id' => '发货站点id',
            'send_station_name' => '发货站点名字',
            'driver_id' => '司机id',
            'driver_name' => '司机姓名',
            'driver_phone' => '司机电话',
            'driver_accept_type' => '司机接受类型 0等待接受  1 接受 2 拒绝',
            'bus_line' => '公交线路',
            'bus_id' => '车辆id',
            'bus_time' => '发车时间',
            'card' => '车牌号码',
            'deliver_time' => '配送时间',
            'station_time' => '到达站点时间',
            'receiver_time' => '收件签收时间',
            'package_num' => '订单包裹数',
            'source' => '订单来源 1总部、2站点',
            'total_account' => '订单总价',
            'yongjin' => '司机佣金',
            'station_yongjin' => '站点佣金',
            'detail' => '订单明细',
            'status' => '订单状态 1:待指派 2代配送 3:配送中 4已签收 5已完成',
            'type' => '1:发货订单 2: 取货订单',
            'created_at' => '订单创建时间',
            'updated_at' => 'Updated At',
        ];
    }

    public function extraFields()
    {
        return [
            "trace" => function () {
                //先用package order的 id 然后换成order id 再去order trace 拿
//                $order = Order::find()
//                    ->select('id', 'package_id_list')
//                    ->where(" FIND_IN_SET(" . $this->id . ",package_id_list)")
//                    ->one();
//                if ($order->id) {
                $trace = OrderTrace::find()
                    ->where(['order_id' => $this->id])
                    ->orderBy('id desc')
                    ->asArray()
                    ->all();
//                }
                return $trace;
            },
            "package_order" => function () {
                return PackageOrder::find()->where(['in', 'package_list_id', explode(',', $this->package_id_list)])->asArray()->all();
            },
            "receive" => function () {
                return ServiceStation::find()->select(['telephone'])->where(['id' => $this->receive_station_id])->one();
            },
        ];
    }
}
