<?php

namespace common\services;

use common\models\DriverOrder;

class DriverOrderService extends BaseService
{

    const DRIVER_ORDER_STATUS_REJECT = 0;  //已拒绝/已取消
    const DRIVER_ORDER_STATUS_PENDING = 1; //未操作
    const DRIVER_ORDER_STATUS_ACCEPT = 2;     //已接单
    const DRIVER_ORDER_STATUS_CONFIRM = 3;  //配送中
    const DRIVER_ORDER_STATUS_COMPLETE = 4;  //已完成

    public $typeList = [
        'station-order' => 1,
        'bus-order' => 2
    ];

    /**
     * 推送给司机
     * @param $order
     * @param string $type
     * @throws \Exception
     */
    public function pushToDriver($order, $type = 'station-order')
    {
        if (!isset($this->typeList[$type])) {
            throw new \Exception('配置有误');
        }
        DriverOrder::updateAll(['status' => DriverOrderService::DRIVER_ORDER_STATUS_REJECT], ['order_type' => $this->typeList[$type], 'order_id' => $order['id']]);
        $data = [];
        if ($type == 'station-order') {
            DriverOrder::deleteAll(['order_type' => $this->typeList[$type], 'order_id' => $order['id']]);

            $data = [
                'order_type' => $this->typeList[$type],
                'order_id' => $order['id'],
                'order_no' => (string)$order['id'],
                'title' => '服务站点',
                'driver_id' => $order['driver_id'],
                'bus_id' => $order['bus_id'],
                'bus_line' => $order['bus_line'],
                'bus_card' => $order['bus_card'],
                'start_date' => date('Y-m-d H:i:s', time()),
                'commission' => $order['commission'],
                'package_num' => $order['package_num'],
                'send_station_id' => $order['send_station_id'],
                'send_station_name' => $order['send_station_name'],
                'receive_station_id' => $order['receive_station_id'],
                'receive_station_name' => $order['receive_station_name'],
                'type' => $order['type'],
            ];
        } else if ($type == 'bus-order') {
            $data = [
                'order_type' => $this->typeList[$type],
                'type' => $order['order_type'],
                'order_id' => $order['id'],
                'order_no' => $order['order_no'],
                'title' => $order['title'],
                'driver_id' => $order['driver_id'],
                'customer_id' => $order['customer_id'],
                'bus_id' => $order['bus_id'],
                'bus_card' => $order['bus_card'],
                'start_date' => date('Y-m-d H:i:s', $order['start_time']),
                'commission' => $order['commission'],
            ];
        }

        $model = new DriverOrder();
        $model->load(['DriverOrder' => $data]);
        $model->save();
    }
}