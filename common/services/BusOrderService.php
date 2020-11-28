<?php

namespace common\services;

use common\models\Bus;
use common\models\BusMoneyRule;
use common\models\BusOrder;
use common\models\BusOrderOutInfo;
use common\models\BusOrderTrace;
use common\models\BusStation;
use common\models\Customer;
use common\models\DriverOrder;

class BusOrderService extends BaseService
{
    /** @var MessageService $messageService */
    public $messageService;
    /** @var BillService $billService */
    public $billService;
    /** @var MapService $mapService */
    public $mapService;
    /** @var DriverOrderService $driverOrderService */
    public $driverOrderService;

    public function __construct()
    {
        $this->messageService = new MessageService();
        $this->billService = new BillService();
        $this->mapService = new MapService();
        $this->driverOrderService = new DriverOrderService();
    }

    const BUS_ORDER_STATUS_CANCEL = 0;  //已取消
    const BUS_ORDER_STATUS_PENDING = 1; //待付款
    const BUS_ORDER_STATUS_PAY = 2;     //已支付
    const BUS_ORDER_STATUS_ASSIGN = 3;  //已派单
    const BUS_ORDER_STATUS_REJECT = 4;  //已拒绝
    const BUS_ORDER_STATUS_ACCEPT = 5;  //已接单
    const BUS_ORDER_STATUS_CONFIRM = 6; //已确认
    const BUS_ORDER_STATUS_GOING = 7; //出行中
    const BUS_ORDER_STATUS_COMPLETE = 8;//已完成
    const BUS_ORDER_STATUS_REFUND = 9;  //待退款

    /**
     * 小程序列表
     * @param $statusType
     * @param $limit
     * @return array
     * @throws \Exception
     */
    public function index($statusType, $limit)
    {
        /** @var Customer $customer */
        $customer = \Yii::$app->user->identity;
        $res = [];
        if ($customer['type'] == 2) {//会员
            $selector = BusOrder::find()
                ->where(['customer_id' => $customer['customer_id']]);
            switch ($statusType) {
                case 1://全部订单
                    break;
                case 2://待出行订单
                    $selector->andWhere(['not in', 'status', [
                        BusOrderService::BUS_ORDER_STATUS_CANCEL,
                        BusOrderService::BUS_ORDER_STATUS_REFUND,
                        BusOrderService::BUS_ORDER_STATUS_COMPLETE
                    ]]);
                    break;
                case 3://已完成订单
                    $selector->andWhere(['status' => [
                        BusOrderService::BUS_ORDER_STATUS_CANCEL,
                        BusOrderService::BUS_ORDER_STATUS_REFUND,
                        BusOrderService::BUS_ORDER_STATUS_COMPLETE
                    ]]);
                    break;
            }
            $list = $selector->orderBy('id desc')
                ->offset($limit[0])
                ->limit($limit[1])
                ->all();
            foreach ($list as $v) {
                $arr = [];
                $arr['order_no'] = $v['order_no'];
                $arr['title'] = $v['title'];
                $arr['status'] = $v['status'];
                $arr['status_name'] = $this->showStatusName($v);
                $arr['order_type'] = $v['order_type'];
                $arr['car_type'] = $v['car_type'];
                $arr['using_length'] = $this->showTimeLength($v['end_time'] - $v['start_time']);
                $arr['driver_name'] = $v['driver_name'];
                $arr['driver_phone'] = $v['driver_phone'];
                $arr['bus_card'] = $v['bus_card'];
                $arr['start_point'] = $v['start_point'];
                $arr['dispatch_start'] = $v['dispatch_start'];
                $arr['dispatch_end'] = $v['dispatch_end'];
                $arr['end_point'] = $v['end_point'];
                $arr['money'] = $v['money'];
                $arr['show_cancel'] = $this->showCancel($v);
                $res[] = $arr;
            }
        } else if ($customer['type'] == 1) {//司机
            $selector = BusOrder::find()
                ->where(['driver_id' => $customer['customer_id']]);
            if (!isset($statusType)) {
                $statusType = 1;
            }
            switch ($statusType) {
                case 1://全部订单
                    break;
                case 2://待出行订单
                    $selector = $selector->andWhere(['not in', 'status', [
                        BusOrderService::BUS_ORDER_STATUS_CANCEL,
                        BusOrderService::BUS_ORDER_STATUS_PENDING,
                        BusOrderService::BUS_ORDER_STATUS_REJECT,
                        BusOrderService::BUS_ORDER_STATUS_REFUND,
                        BusOrderService::BUS_ORDER_STATUS_COMPLETE

                    ]]);
                    break;
                case 3://已完成订单
                    $selector = $selector->andWhere(['status' => [
                        BusOrderService::BUS_ORDER_STATUS_CANCEL,
                        BusOrderService::BUS_ORDER_STATUS_REFUND,
                        BusOrderService::BUS_ORDER_STATUS_COMPLETE
                    ]]);
                    break;
            }
            $list = $selector->orderBy('id desc')
                ->offset($limit[0])
                ->limit($limit[1])
                ->all();
            foreach ($list as $v) {
                $arr = [];
                $arr['order_no'] = $v['order_no'];
                $arr['title'] = $v['title'];
                $arr['status'] = $v['status'];
                $arr['status_name'] = $this->showStatusName($v);
                $arr['order_type'] = $v['order_type'];
                $arr['car_type'] = $v['car_type'];
                $arr['tips'] = '';
                if ($v['order_type'] == 1) {
                    $arr['tips'] = \Yii::$app->params['car_type_list'][$v['car_type']] . '/' . $v['bus_card'];
                } else if ($v['order_type'] == 3) {
                    $arr['tips'] = \Yii::$app->params['car_type_list'][$v['car_type']] . '/' . $v['bus_card'] . '/包车' . $this->showTimeLength($v['end_time'] - $v['start_time']);
                } else {
                    if ($v['bus_id']) {
                        $arr['tips'] = \Yii::$app->params['car_type_list'][$v['car_type']] . '/' . $v['bus_card'] . '/' . $v['reason'];
                    }
                }
                $arr['using_length'] = $this->showTimeLength($v['end_time'] - $v['start_time']);
                $arr['use_people'] = $v['use_people'];
                $arr['mobile'] = $v['mobile'];
                $arr['time_quantum'] = date('Y.m.d', $v['start_time']) . '-' . date('Y.m.d', $v['end_time']);
                $arr['money'] = $v['money'];
                $arr['start_point'] = $v['start_point'];
                $arr['end_point'] = $v['end_point'];
                $arr['dispatch_start'] = $v['dispatch_start'];
                $arr['dispatch_end'] = $v['dispatch_end'];
                $res[] = $arr;
            }
        } else {
            throw new \Exception('用户信息有误');
        }
        return $res;
    }

    public function showCancel($busOrder)
    {
        if (!in_array($busOrder['status'], [
            BusOrderService::BUS_ORDER_STATUS_PENDING,
            BusOrderService::BUS_ORDER_STATUS_PAY,
            BusOrderService::BUS_ORDER_STATUS_REJECT,
        ])) {
            return 0;
        }
        if (time() > $busOrder['start_time'] - 4 * 3600) {
            return 0;
        }
        if ($busOrder['scan_times'] != 0) {
            return 0;
        }
        return 1;
    }

    public function showStatusName($busOrder)
    {
        $customer = \Yii::$app->user->identity;
        $statusList = $customer['type'] == 2 ? \Yii::$app->params['bus_order_status_list_customer'] : \Yii::$app->params['bus_order_status_list_driver'];
        $statusName = $statusList[$busOrder['status']];
        if ($customer['type'] == 2 && $busOrder['status'] == BusOrderService::BUS_ORDER_STATUS_ASSIGN && $busOrder['scan_times'] > 0) {
            $statusName = '重新派单中';
        }
        return $statusName;
    }

    /**
     * 展示时长
     */
    private function showTimeLength($time)
    {
        return (int)ceil($time / (24 * 3600)) . '天';
        $days = (int)floor($time / (24 * 3600));
        $hours = (int)floor(($time - $days * 24 * 3600) / 3600);
        $minutes = (int)floor(($time - $days * 24 * 3600 - $hours * 3600) / 60);
        $str = ($days ? $days . '天' : '') . ($hours ? $hours . '小时' : '') . ($minutes ? $minutes . '分钟' : '');
        return $str;
    }

    /**
     * 保存客运订单
     * @param $data
     * @return BusOrder
     * @throws \Exception
     */
    public function saveApiOrder($data)
    {
        $customer = \Yii::$app->user->identity;
        if ($customer['type'] != 2) {
            throw new \Exception('只有会员用户可以创建用车订单');
        }
        $times = 1;//包车次数
        if ($data['type'] == 1) {//旅游包车
            $order = [
                'customer_id' => \Yii::$app->user->id,
                'order_type' => $data['type'],
                'use_people' => $data['use_people'],
                'mobile' => $data['mobile'],
                'car_type' => $data['car_type'],
                'start_time' => strtotime($data['start_time']),
                'end_time' => strtotime($data['end_time']),
                'dispatch_start' => $data['dispatch_start'],
                'dispatch_end' => $data['dispatch_end'],
                'start_point' => $data['start_point'],
                'end_point' => $data['end_point'],
                'remark' => $data['remark'],
            ];
        } else if ($data['type'] == 2) {//站点叫车
            if (!isset($data['start_id'])) {
                throw new \Exception('参数有误');
            }
            $station = BusStation::findOne($data['start_id']);
            if (!$station) {
                throw new \Exception('站点信息有误');
            }
            $order = [
                'customer_id' => \Yii::$app->user->id,
                'use_people' => $customer['verify_status'] == 2 ? $customer['realname'] : '手机用户' . $customer['mobile'],
                'mobile' => $customer['mobile'],
                'order_type' => $data['type'],
                'dispatch_start' => $station['station_name'],
                'start_time' => time(),
                'start_point' => $station['up_point'],
                'user_number' => $data['user_number'],
                'reason' => $data['reason'],
                'status' => 2,
                'remark' => $data['remark'],
            ];
        } else if ($data['type'] == 3) {//定制班车
            $order = [
                'customer_id' => \Yii::$app->user->id,
                'order_type' => $data['type'],
                'use_people' => $customer['verify_status'] == 2 ? $customer['realname'] : '手机用户' . $customer['mobile'],
                'mobile' => $customer['mobile'],
                'car_type' => $data['car_type'],
                'start_time' => strtotime($data['start_time']),
                'end_time' => strtotime($data['end_time']),
                'dispatch_start' => $data['dispatch_start'],
                'dispatch_end' => $data['dispatch_end'],
                'start_point' => $data['start_point'],
                'end_point' => $data['end_point'],
                'reason' => $data['reason'],
                'remark' => $data['remark'],
            ];
            $times = $this->getDays($data['start_time'], $data['end_time']);
        }
        $order['total_times'] = $times * 2;
        if ($data['type'] == 1 || $data['type'] == 3) {
            $order['money'] = $this->getMoney($data['car_type'], $data['start_point'], $data['end_point'], $times);
            if ($order['money'] != $data['money']) {
                throw new \Exception('价格信息不匹配，请重新获取价格');
            }
            $order['commission'] = 0;//todo 客运订单无佣金
        }
        $busOrder = new BusOrder();
        $busOrder->load(['BusOrder' => $order]);
        if (!$busOrder->save()) {
            throw new \Exception(array_values($busOrder->firstErrors)[0]);
        }
        $this->trace($busOrder->id, '创建订单');
        return $busOrder;
    }

    /**
     * 后台保存订单
     * @param $data
     * @return BusOrder
     */
    public function saveOrder($data)
    {
        $data = $data['BusOrder'];
        $customer = [];
        if ($data['customer_id']) {
            $customer = Customer::findOne($data['customer_id']);
        }
        $times = 1;//包车次数
        if ($data['order_type'] == 1) {//旅游包车
            $order = [
                'customer_id' => $data['customer_id'],
                'order_type' => $data['order_type'],
                'use_people' => $data['use_people'],
                'mobile' => $data['mobile'],
                'car_type' => $data['car_type'],
                'start_time' => strtotime($data['start_time']),
                'end_time' => strtotime($data['end_time']),
                'dispatch_start' => $data['dispatch_start'],
                'start_point' => $data['start_point'],
                'dispatch_end' => $data['dispatch_end'],
                'end_point' => $data['end_point'],
                'remark' => $data['remark'],
                'money' => $data['money']
            ];
        } else if ($data['order_type'] == 2) {//站点叫车
            $order = [
                'customer_id' => $data['customer_id'],
                'order_type' => $data['order_type'],
                'use_people' => isset($customer['verify_status']) && $customer['verify_status'] == 2 ? $customer['realname'] : '手机用户' . $customer['mobile'],
                'mobile' => isset($customer['mobile']) ? $customer['mobile'] : '',
                'dispatch_start' => $data['dispatch_start'],
                'start_time' => time(),
                'start_point' => $data['start_point'],
                'user_number' => $data['user_number'],
                'reason' => $data['reason'],
                'status' => 2,
                'remark' => $data['remark'],
            ];
        } else if ($data['order_type'] == 3) {//定制班车
            $order = [
                'customer_id' => $data['customer_id'],
                'order_type' => $data['order_type'],
                'use_people' => isset($customer['verify_status']) && $customer['verify_status'] == 2 ? $customer['realname'] : '手机用户' . $customer['mobile'],
                'mobile' => isset($customer['mobile']) ? $customer['mobile'] : '',
                'car_type' => $data['car_type'],
                'start_time' => strtotime($data['start_time']),
                'end_time' => strtotime($data['end_time']),
                'dispatch_start' => $data['dispatch_start'],
                'start_point' => $data['start_point'],
                'dispatch_end' => $data['dispatch_end'],
                'end_point' => $data['end_point'],
                'reason' => $data['reason'],
                'remark' => $data['remark'],
                'money' => $data['money']
            ];
            $times = $this->getDays($data['start_time'], $data['end_time']);
        }
        $order['total_times'] = $times * 2;
        $order['commission'] = 0;//客运订单无佣金
        $order['add_type'] = 2;
        $busOrder = new BusOrder();
        if (isset($data['id']) && $data['id']) {
            $busOrder = BusOrder::findOne($data['id']);
        }
        $busOrder->load(['BusOrder' => $order]);
        if (isset($order['start_time']) && !$order['start_time']) {
            $busOrder->addError('start_time', '出发时间有误');
        }
        if (isset($order['end_time']) && !$order['end_time']) {
            $busOrder->addError('end_time', '结束时间有误');
        }
        if ($data['order_type'] == 1 || $data['order_type'] == 3) {
            if (!isset($data['start_point']) || $data['start_point'] == '') {
                $busOrder->addError('dispatch_start', '请在起始地点下拉选项中选择');
            }
            if (!isset($data['end_point']) || $data['end_point'] == '') {
                $busOrder->addError('dispatch_end', '请在结束地点下拉选项中选择');
            }
        } else if ($data['order_type'] == 2) {
            if (!isset($data['start_point']) || $data['start_point'] == '') {
                $busOrder->addError('dispatch_start', '请在开始地点下拉选项中选择');
            }
        }
        !$busOrder->hasErrors() && $busOrder->save();
        return $busOrder;
    }

    /**
     * 订单指派及转派
     * @param $data
     * @throws \Exception
     */
    public function assignOrder($data)
    {
        $order = BusOrder::findOne($data['id']);
        if (!$order) {
            throw new \Exception('数据有误,请刷新重试');
        }
        if ($data['status'] != $order->status) {
            throw new \Exception('操作已完成，请刷新界面');
        }
        if (!in_array($order->status, [
            BusOrderService::BUS_ORDER_STATUS_PAY,
            BusOrderService::BUS_ORDER_STATUS_ASSIGN,
            BusOrderService::BUS_ORDER_STATUS_REJECT,
            BusOrderService::BUS_ORDER_STATUS_ACCEPT,

        ])) {
            throw new \Exception('订单状态有误，请刷新界面');
        }
        $driver = Customer::findOne($data['driver_id']);
        if (!$driver) {
            throw new \Exception('司机信息有误，请刷新界面');
        }
        $bus = Bus::findOne($data['bus_id']);
        if (!$bus) {
            throw new \Exception('车辆信息有误，请刷新界面');
        }
        $order['driver_id'] = $driver['customer_id'];
        $order['driver_name'] = $driver['realname'];
        $order['driver_phone'] = $driver['mobile'];
        $order['bus_id'] = $bus['id'];
        $order['bus_card'] = $bus['card'];
        if ($order->order_type == 2) {
            $order['car_type'] = $bus['car_type'];
        }
        $oldStatus = $order['status'];
        $order['status'] = BusOrderService::BUS_ORDER_STATUS_ASSIGN;
        $order->save();

        $msg = '后台订单指派给司机：' . $driver['realname'] . ' 车牌号：' . $bus['card'];
        if ($oldStatus != BusOrderService::BUS_ORDER_STATUS_PAY) {
            $msg = '后台订单转派给司机：' . $driver['realname'] . ' 车牌号：' . $bus['card'];
        }
        $this->driverOrderService->pushToDriver($order, 'bus-order');
        $this->messageService->sendMessage('调度消息', '订单' . $order['order_no'] . ' 车辆 ' . $order['bus_card'], $order['driver_id'], ['type' => 'receive']);
        $this->trace($order->id, $msg);
    }

    /**
     * 司机拒绝接单
     * @param $order_no
     * @throws \Exception
     */
    public function rejectOrder($order_no)
    {
        /** @var BusOrder $order */
        $order = BusOrder::find()->where(['order_no' => $order_no])->one();
        if (!$order) {
            throw new \Exception('数据有误,请刷新重试');
        }
        $customer = \Yii::$app->user->identity;
        if ($customer['type'] != 1) {
            throw new \Exception('数据有误');
        }
        if ($order['driver_id'] != $customer['customer_id']) {
            throw new \Exception('数据有误,请刷新重试');
        }
        if ($order['status'] != BusOrderService::BUS_ORDER_STATUS_ASSIGN) {
            throw new \Exception('订单状态有误,请刷新重试');
        }
        $order['status'] = BusOrderService::BUS_ORDER_STATUS_REJECT;
        $order->save();
        $this->trace($order->id, '司机拒绝接单');
        $this->updateDriverOrder($order);
    }

    /**
     * 司机接单
     * @param $order_no
     * @throws \Exception
     */
    public function receiveOrder($order_no)
    {
        /** @var BusOrder $order */
        $order = BusOrder::find()->where(['order_no' => $order_no])->one();
        if (!$order) {
            throw new \Exception('数据有误,请刷新重试');
        }
        $customer = \Yii::$app->user->identity;
        if ($customer['type'] != 1) {
            throw new \Exception('数据有误');
        }
        if ($order['driver_id'] != $customer['customer_id']) {
            throw new \Exception('数据有误,请刷新重试');
        }
        if ($order['status'] != BusOrderService::BUS_ORDER_STATUS_ASSIGN) {
            throw new \Exception('订单状态有误,请刷新重试');
        }
        $order['status'] = BusOrderService::BUS_ORDER_STATUS_ACCEPT;
        $order->save();
        $this->trace($order->id, '司机接单');
        $this->updateDriverOrder($order);
    }

    /**
     * 司机扫码条码确认订单
     * @param $order_no
     * @throws \Exception
     */
    public function scanOrder($order_no, $type)
    {
        /** @var BusOrder $order */
        $order = BusOrder::find()->where(['order_no' => $order_no])->one();
        if (!$order) {
            throw new \Exception('数据有误');
        }
        $customer = \Yii::$app->user->identity;
        if ($customer['type'] != 1) {
            throw new \Exception('数据有误');
        }
        if (\Yii::$app->user->id != $order['driver_id']) {
            throw new \Exception('数据有误');
        }
        if ($type == 'start' && $order['status'] != BusOrderService::BUS_ORDER_STATUS_ACCEPT) {
            throw new \Exception('订单状态有误');
        }
        if ($type == 'end' && $order['status'] != BusOrderService::BUS_ORDER_STATUS_GOING) {
            throw new \Exception('订单状态有误');
        }

        if ($type == 'end') {
            $order['scan_times'] += 1;
            if ($order['scan_times'] == $order['total_times']) {
                $detail = '司机扫码完成订单';
                $order['status'] = BusOrderService::BUS_ORDER_STATUS_COMPLETE;
            } else {
                $detail = '司机扫码完成当天行程';
                $order['status'] = BusOrderService::BUS_ORDER_STATUS_ACCEPT;
            }
        } else {
            $detail = '司机扫码确认';
            $order['status'] = BusOrderService::BUS_ORDER_STATUS_CONFIRM;
        }
        $order['driver_id'] = $customer['customer_id'];
        $order['driver_name'] = $customer['realname'];
        $order['driver_phone'] = $customer['mobile'];
        $order->save();
        $this->outInfo($order);
        $this->trace($order->id, $detail);
        $this->updateDriverOrder($order);
    }


    /**
     * @param $order_no
     */
    public function start($order_no)
    {
        /** @var BusOrder $order */
        $order = BusOrder::find()->where(['order_no' => $order_no])->one();
        if (!$order) {
            throw new \Exception('数据有误');
        }
        $customer = \Yii::$app->user->identity;
        if ($customer['type'] != 1) {
            throw new \Exception('数据有误');
        }
        if (\Yii::$app->user->id != $order['driver_id']) {
            throw new \Exception('数据有误');
        }
        if ($order['status'] != BusOrderService::BUS_ORDER_STATUS_CONFIRM) {
            throw new \Exception('订单状态有误');
        }
        $order['scan_times'] += 1;
        $order['status'] = BusOrderService::BUS_ORDER_STATUS_GOING;
        $order->save();
        $this->outInfo($order);
        $this->trace($order->id, '司机开始行程');
        $this->updateDriverOrder($order);
    }

    /**
     * 会员完成订单，状态更改为已完成
     * @param $order_no
     * @throws \Exception
     */
    public function complete($order_no, $is_auto = 0)
    {
        /** @var BusOrder $order */
        $order = BusOrder::findOne(['order_no' => $order_no]);
        if (!$order) {
            throw new \Exception('数据有误');
        }
        if ($order['customer_id'] != \Yii::$app->user->id) {
            throw new \Exception('数据有误');
        }
        if ($order['status'] != BusOrderService::BUS_ORDER_STATUS_CONFIRM) {
            throw new \Exception('数据有误');
        }
        $order->status = BusOrderService::BUS_ORDER_STATUS_COMPLETE;
        $order->save();
        $this->billService->saveDriverBill($order['order_type'], $order);
        $this->outInfo($order);
        $msg = '会员完成订单';
        if ($is_auto == 1) {
            $msg = '系统自动完成订单';
        }
        $this->trace($order->id, $msg);
        $this->messageService->sendMessage('完成订单', $order['order_no'] . '订单已完成', $order['driver_id'], ['type' => 'complete']);
        $this->updateDriverOrder($order);
    }

    /**
     * 会员取消订单
     * @param $order_no
     * @param int $user_type 1User 2Customer
     * @param int $is_auto
     * @throws \Exception
     */
    public function cancel($order_no, $user_type = 2, $is_auto = 0)
    {
        /** @var BusOrder $order */
        $order = BusOrder::findOne(['order_no' => $order_no]);
        $status = $order['status'];
        if (!$order) {
            throw new \Exception('数据有误');
        }
        if ($user_type == 2) {
            if ($order['customer_id'] != \Yii::$app->user->id) {
                throw new \Exception('数据有误');
            }
            if ($order['start_time'] - 4 * 3600 < time()) {
                throw new \Exception('订单超时，请联系客服取消～');
            }
        }
        if (!in_array($status, [
            BusOrderService::BUS_ORDER_STATUS_PENDING,
            BusOrderService::BUS_ORDER_STATUS_PAY,
            BusOrderService::BUS_ORDER_STATUS_ASSIGN,
            BusOrderService::BUS_ORDER_STATUS_REJECT,
            BusOrderService::BUS_ORDER_STATUS_ACCEPT,
        ])) {
            throw new \Exception('订单状态有误');
        }
        //手动取消订单状态直接置为0
        $order->status = BusOrderService::BUS_ORDER_STATUS_REFUND;
        if ($status == BusOrderService::BUS_ORDER_STATUS_PENDING || $order['money'] == 0) {
            $order->status = BusOrderService::BUS_ORDER_STATUS_CANCEL;
        }
        if ($user_type == 1 && $is_auto == 0) {
            $msg = $order['status'] == BusOrderService::BUS_ORDER_STATUS_REFUND ? '管理员取消订单，退款中' : '管理员取消订单';
        } else {
            $msg = $order['status'] == BusOrderService::BUS_ORDER_STATUS_REFUND ? '会员取消订单，申请退款' : '会员取消订单';
            if ($is_auto) {
                $msg = '超时未支付，订单自动取消';
            }
        }
        $order->save();
        $this->outInfo($order);
        $this->trace($order->id, $msg);
        if ($order['driver_id']) {
            $this->messageService->sendMessage('订单取消', $order['order_no'] . '订单已取消', $order['driver_id'], ['type' => 'cancel']);
        }
        if (!in_array($status, [BusOrderService::BUS_ORDER_STATUS_PENDING, BusOrderService::BUS_ORDER_STATUS_PAY])) {
            $this->updateDriverOrder($order);
        }
    }

    /**
     * 保存计费规则
     */
    public function saveMoneyRule($data)
    {
        BusMoneyRule::deleteAll();
        foreach ($data['start'] as $key => $v) {
            for ($i = 1; $i <= 3; $i++) {
                $model = new BusMoneyRule();
                $model->start = $v;
                $model->end = $data['end'][$key];
                $model->money = $data['money' . $i][$key];
                $model->car_type = $i;
                $model->save();
            }
        }
    }

    /**
     * 根据起止地点经纬度获取订单金额
     * @param $car_type
     * @param $start_point
     * @param $end_point
     * @return mixed
     * @throws \Exception
     */
    public function getMoney($car_type, $start_point, $end_point, $day = 1)
    {
        //地图api获取距离,不足一公里按一公里算
        $distance = $this->mapService->getDirection($start_point, $end_point);
        $ruleList = BusMoneyRule::find()->where(['car_type' => $car_type])->asArray()->all();
        foreach ($ruleList as $v) {
            if ($v['start'] <= $distance && ($v['end'] == 0 || $v['end'] >= $distance)) {
                return $v['money'] * $day;
            }
        }
        throw new \Exception('当前距离 ' . $distance . '公里 未配置价格！');
    }

    public function outInfo($order)
    {
        $model = new BusOrderOutInfo();
        $model['order_no'] = $order['order_no'];
        $model['order_id'] = $order['id'];
        $model['type'] = 1;
        switch ($order['status']) {
            case BusOrderService::BUS_ORDER_STATUS_ACCEPT:
                $model['detail'] = date('Y.m.d H:i') . ' 当天行程已结束';
                break;
            case BusOrderService::BUS_ORDER_STATUS_CONFIRM:
                $model['detail'] = \Yii::$app->params['car_type_list'][$order['car_type']] . '/' . $order['bus_card'];
                break;
            case BusOrderService::BUS_ORDER_STATUS_GOING:
                $model['detail'] = date('Y.m.d H:i') . ' 开始行程';
                break;
            case BusOrderService::BUS_ORDER_STATUS_COMPLETE:
                $model['detail'] = date('Y.m.d H:i') . ' 完成订单';
                break;
            case BusOrderService::BUS_ORDER_STATUS_REFUND:
            case BusOrderService::BUS_ORDER_STATUS_CANCEL:
                $model['detail'] = date('Y.m.d H:i') . ' 取消';
                break;
            default:
                throw new \Exception('订单状态有误');
        }
        $model->save();
        $model = new BusOrderOutInfo();
        $model['order_no'] = $order['order_no'];
        $model['order_id'] = $order['id'];
        $model['type'] = 2;
        switch ($order['status']) {
            case BusOrderService::BUS_ORDER_STATUS_ACCEPT:
                $model['detail'] = date('Y.m.d H:i') . ' 当天行程已结束';
                break;
            case BusOrderService::BUS_ORDER_STATUS_CONFIRM:
                $model['detail'] = $order['driver_name'] . '/' . $order['driver_phone'] . '/' . $order['bus_card'];
                break;
            case BusOrderService::BUS_ORDER_STATUS_GOING:
                $model['detail'] = date('Y.m.d H:i') . ' 开始行程';
                break;
            case BusOrderService::BUS_ORDER_STATUS_COMPLETE:
                $model['detail'] = date('Y.m.d H:i') . ' 行程结束，完成订单';
                break;
            case BusOrderService::BUS_ORDER_STATUS_REFUND:
            case BusOrderService::BUS_ORDER_STATUS_CANCEL:
                $model['detail'] = date('Y.m.d H:i') . ' 取消';
                break;
            default:
                throw new \Exception('订单状态有误');
        }
        $model->save();
    }

    /**
     * 日志记录
     * @param $order_ids
     * @param $detail
     * @throws \Exception
     */
    public function trace($idList, $detail)
    {
        if (!is_array($idList)) {
            $idList = explode(',', $idList);
        }

        if (\Yii::$app->user->isGuest) {
            $operator = '系统管理员';
        } else {
            $user = \Yii::$app->user->identity;
            $operator = $user['realname'] ? $user['realname'] : '用户昵称：' . $user['nickname'];
        }
        $traceList = [];
        $time = time();
        foreach ($idList as $id) {
            $traceList[] = [
                'order_id' => $id,
                'operator' => $operator,
                'detail' => $detail,
                'create_time' => $time
            ];
        }
        \Yii::$app->db->createCommand()
            ->batchInsert(BusOrderTrace::tableName(), ['order_id', 'operator', 'detail', 'create_time'], $traceList)
            ->execute();
    }

    public function getDays($start_time, $end_time)
    {
        $start = strtotime(date('Y-m-d', strtotime($start_time)));
        $end = strtotime(date('Y-m-d', strtotime($end_time)));
        if ($end < $start) {
            throw new \Exception('结束时间不能小于开始时间');
        }
        return (int)(($end - $start) / (24 * 3600)) + 1;
    }

    /**
     * @param BusOrder $order
     */
    public function updateDriverOrder($order)
    {
        switch ($order['status']) {
            case BusOrderService::BUS_ORDER_STATUS_ASSIGN:
                $status = DriverOrderService::DRIVER_ORDER_STATUS_PENDING;
                break;
            case BusOrderService::BUS_ORDER_STATUS_ACCEPT:
                $status = DriverOrderService::DRIVER_ORDER_STATUS_ACCEPT;
                break;
            case BusOrderService::BUS_ORDER_STATUS_REJECT:
                $status = DriverOrderService::DRIVER_ORDER_STATUS_REJECT;
                break;
            case BusOrderService::BUS_ORDER_STATUS_CONFIRM:
                $status = DriverOrderService::DRIVER_ORDER_STATUS_CONFIRM;
                break;
            case BusOrderService::BUS_ORDER_STATUS_GOING:
                $status = DriverOrderService::DRIVER_ORDER_STATUS_CONFIRM;
                break;
            case BusOrderService::BUS_ORDER_STATUS_COMPLETE:
                $status = DriverOrderService::DRIVER_ORDER_STATUS_COMPLETE;
                break;
            case BusOrderService::BUS_ORDER_STATUS_CANCEL:
                $status = DriverOrderService::DRIVER_ORDER_STATUS_REJECT;
                break;
            default:
                return;
        }
        DriverOrder::updateAll(['status' => $status, 'update_time' => time()], ['and', ['order_type' => 2, 'order_no' => $order['order_no'], 'driver_id' => $order['driver_id']], ['!=', 'status', '0']]);
    }
}