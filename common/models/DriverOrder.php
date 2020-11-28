<?php

namespace common\models;

use common\services\BusOrderService;

/**
 * This is the model class for table "driver_order".
 *
 * @property int $id
 * @property int $order_type 订单类型 1:站点订单 2:客运订单
 * @property int $type 订单子类型
 * @property int $order_id 订单ID
 * @property string $order_no 订单号
 * @property string $title 订单标题
 * @property int $driver_id 司机ID
 * @property int $customer_id 会员ID
 * @property int $package_num 包裹数
 * @property int $bus_id 车辆ID
 * @property string $bus_card 车牌
 * @property string $bus_line 公交线路名
 * @property string $start_date 发车时间
 * @property string $commission 佣金
 * @property int $status 状态 1:未操作 2:已接单 0已拒绝 3:配送中 4:已完成
 * @property int $send_station_id 包裹订单用的-发件的服务站点
 * @property string $send_station_name 包裹订单用的-发件的服务站点名
 * @property int $receive_station_id 包裹订单用的-收件的服务站点id
 * @property string $receive_station_name 包裹订单用的-收件的服务站点name
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class DriverOrder extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'driver_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_type', 'type', 'order_id', 'driver_id', 'customer_id', 'package_num', 'bus_id', 'status', 'send_station_id', 'receive_station_id', 'create_time', 'update_time'], 'integer'],
            [['order_id', 'driver_id', 'bus_id', 'start_date'], 'required'],
            [['start_date'], 'safe'],
            [['commission'], 'number'],
            [['order_no', 'title'], 'string', 'max' => 255],
            [['bus_card'], 'string', 'max' => 64],
            [['bus_line', 'send_station_name', 'receive_station_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_type' => '订单类型 1:站点订单 2:客运订单',
            'type' => '订单子类型',
            'order_id' => '订单ID',
            'order_no' => '订单号',
            'title' => '订单标题',
            'driver_id' => '司机ID',
            'customer_id' => '会员ID',
            'package_num' => '包裹数',
            'bus_id' => '车辆ID',
            'bus_card' => '车牌',
            'bus_line' => '公交线路名',
            'start_date' => '发车时间',
            'commission' => '佣金',
            'status' => '状态 1:未操作 2:已接单 0已拒绝 3:配送中 4:已完成',
            'send_station_id' => '包裹订单用的-发件的服务站点',
            'send_station_name' => '包裹订单用的-发件的服务站点名',
            'receive_station_id' => '包裹订单用的-收件的服务站点id',
            'receive_station_name' => '包裹订单用的-收件的服务站点name',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }

    public function extraFields()
    {
        return [
            "detail" => function () {
                //订单类型 1:站点订单 2:客运订单
                if ($this->order_type == 1) {
                    $ret = [];
                    $res = Order::find()->where(['id' => $this->order_id])->asArray()->all();

                    foreach ($res as $key => $val) {
                        $ret['receive_point'] = 0;
                        $ret['send_point'] = 0;
                        if (!empty($val['receive_station_id'])) {
                            $receive = ServiceStation::find()->where(['id' => $val['receive_station_id']])->asArray()->one();
                            $ret['receive_point'] = $receive['longitude'] . ',' . $receive['latitude'];
                        }

                        if (!empty($val['send_station_id'])) {
                            $send = ServiceStation::find()->where(['id' => $val['send_station_id']])->asArray()->one();
                            $ret['send_point'] = $send['longitude'] . ',' . $send['latitude'];
                        }

                        $res[$key]['send_point'] = $ret['send_point'];
                        $res[$key]['receive_point'] = $ret['receive_point'];
                    }
                    return $res;

                } else {
                    $res = BusOrder::find()->where(['id' => $this->order_id])->asArray()->all();
                    if (isset($res[0])) {
                        $busOrder = $res[0];
                        $busOrder['status_name'] = (new BusOrderService())->showStatusName($busOrder);
                        $detail = \Yii::$app->params['bus_order_type_list'][$busOrder['order_type']];
                        if ($busOrder['order_type'] == 1) {
                            $detail .= date('m.d H:i', $busOrder['start_time']);
                        } else if ($busOrder['order_type'] == 2) {
                            $detail .= date('m.d H:i', $busOrder['start_time']);
                        } else {
                            $detail .= date('m.d', $busOrder['start_time']) . '至' . date('m.d', $busOrder['end_time']) . ' ' . date('H:i', $busOrder['start_time']);
                        }
                        $detail .= ' ' . $busOrder['bus_card'];
                        $busOrder['detail_title'] = $detail;
                        $res[0] = $busOrder;
                    }
                    return $res;
                }
            },
        ];
    }
}
