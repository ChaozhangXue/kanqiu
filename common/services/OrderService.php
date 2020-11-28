<?php

namespace common\services;

use common\models\BusLine;
use common\models\DriverOrder;
use common\models\Order;
use common\models\ServiceStation;

class OrderService extends BaseService
{
    public $driveTrackService;
    public $model;

//    const STATUS_PENDING = 1;//待指派
//    const STATUS_WAIT_SEND = 2;//待配送
//    const STATUS_SENDING_BIND = 3;//绑定后的 配送中
//    const STATUS_SENDING = 4;//点击开始行程的 配送中
//    const STATUS_ARRIVE = 5;//服务站 已签收
//    const STATUS_COMPLETE = 6;

    public function __construct()
    {
        $this->model = new Order();
        $this->driveTrackService = new DriveTrackService();
    }

    /**
     * @param $data
     * @return Order
     * @throws \Exception
     */
    public function add($data)
    {
        $model = new Order();

        if (!$model->load($data, '')) {
            throw new \Exception(array_values($model->firstErrors)[0]);
        }
        if (!$model->save()) {
            throw new \Exception(array_values($model->firstErrors)[0]);
        }
        return $model;
    }

    /**
     * @param $id
     * @param $data
     * @return Order|null
     * @throws \Exception
     */
    public function update($id, $data)
    {
        $model = Order::findOne([
            'id' => $id
        ]);

        foreach ($data as $key => $val) {
            $model->$key = $val;
        }

        if (!$model->save()) {
            throw new \Exception(array_values($model->firstErrors)[0]);
        }
        return $model;
    }

    /**
     * 根据收件的站点id来获取订单信息
     * @param $receive_station_id
     * @return
     * @throws \Exception
     */
    public function getOrderByStationId($receive_station_id)
    {

        $model = new Order();
        $data = $model::find()->where(['receive_station_id' => $receive_station_id, 'status' => 4])->asArray()->all();
        if (empty($data)) {
            $data = $model::find()->where(['send_station_id' => $receive_station_id, 'status' => 4])->asArray()->all();
            if (empty($data)) {
                return [];
            }
        }
        foreach ($data as $key => $order_data) {
            //去获取司机轨迹的最后一个点
            $track = $this->driveTrackService->getTraceByOrderId($order_data['id']);

            //如果没有信息的话 则取发送站点的经纬度
            if (empty($track)) {
                $send = $order_data['send_station_id'];
                $start_station = ServiceStation::find()->where(['id' => $send])->asArray()->one();
                $start = [$start_station['longitude'], $start_station['latitude']];
            } else {
                $start = [$track['longitude'], $track['latitude']];
            }

            $end_station = ServiceStation::find()->where(['id' => $receive_station_id])->asArray()->one();
            $end = [$end_station['longitude'], $end_station['latitude']];

            $distance = $this->getDistance($start, $end);
            $data[$key]['need_time'] = $distance / 40 * 60;//默认公交车速40km/h  算出结果是需要多少 分钟
            $data[$key]['receive_station_phone'] = $end_station['telephone'];

//            获取公交线路信息
            $line = BusLine::find()->where(['station_name' => $order_data['bus_line']])->asArray()->one();
            $data[$key]['bus_line_start'] = isset($line['start_point']) ? $line['start_point'] : '';
            $data[$key]['bus_line_end'] = isset($line['end_point']) ? $line['end_point'] : '';
        }
        return $data;
    }

    /**
     * 接单
     * @param $order_no
     * @throws \Exception
     */
    public function rejectOrder($order_no)
    {
        $order = Order::find()->where(['id' => $order_no])->one();
        if (!$order) {
            throw new \Exception('数据有误,请刷新重试');
        }
        $order['driver_accept_type'] = 2;
        $order->save();
//        $this->trace($order->id, '司机拒绝接单');
        $driverOrder = DriverOrder::findOne(['order_type' => 1, 'order_id' => $order['id']]);
        if($driverOrder) {
            $driverOrder->status = 2;
            $driverOrder->save();
        }
    }
}