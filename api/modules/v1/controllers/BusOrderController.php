<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\BusOrder;
use common\models\BusOrderOutInfo;
use common\services\BusOrderService;
use common\services\PayService;

class BusOrderController extends BaseController
{
    public $modelClass = 'common\models\BusOrder';

    /** @var BusOrderService $busOrderService */
    public $busOrderService;
    /** @var PayService $payService */
    public $payService;

    public function init()
    {
        $this->busOrderService = new BusOrderService();
        $this->payService = new PayService();
        parent::init();
    }

    public function actions()
    {
        $actions = parent::actions();
        // 禁用""index,delete" 和 "create" 操作
        unset($actions['index'], $actions['delete'], $actions['view'], $actions['create'], $actions['update']);
        return $actions;
    }

    /**
     * 订单列表
     */
    public function actionIndex()
    {
        try {
            $params = \Yii::$app->request->get();
            $res = $this->busOrderService->index(isset($params['status_type']) ? $params['status_type'] : 1, $this->getLimit());
            $this->success($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 开始行程
     */
    public function actionStart()
    {
        try {
            $post = \Yii::$app->request->post();
            if (!$post['order_no']) {
                throw new \Exception('参数有误');
            }
            $this->busOrderService->start($post['order_no']);
            $this->success();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 根据起止经纬度获取价格
     */
    public function actionGetPayMoney()
    {
        try {
            $post = \Yii::$app->request->post();
            if (!isset($post['car_type']) || !in_array($post['car_type'], [1, 2, 3])) {
                throw new \Exception('参数有误');
            }
            if (!isset($post['start_point']) || $post['start_point'] == '') {
                throw new \Exception('参数有误');
            }
            if (!isset($post['end_point']) || $post['end_point'] == '') {
                throw new \Exception('参数有误');
            }
            if (!isset($post['type']) || !in_array($post['type'], [1, 3])) {
                throw new \Exception('参数有误');
            }
            $times = 1;
            if ($post['type'] == 3) {
                if (!isset($post['start_time']) || $post['start_time'] == '') {
                    throw new \Exception('参数有误');
                }
                if (!isset($post['end_time']) || $post['end_time'] == '') {
                    throw new \Exception('参数有误');
                }
                $times = $this->busOrderService->getDays($post['start_time'], $post['end_time']);
            }
            $money = $this->busOrderService->getMoney($post['car_type'], $post['start_point'], $post['end_point'], $times);
            $this->success(['money' => $money]);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 下单
     */
    public function actionCreate()
    {
        try {
            $post = \Yii::$app->request->post();
            if (!in_array($post['type'], [1, 2, 3])) {
                throw new \Exception('参数有误');
            }
            $order = $this->busOrderService->saveApiOrder($post);
            if (in_array($order['order_type'], [1, 3])) {
                $order['pay_order_no'] = $this->payService->generateTradeNo();
                $order->save();
                $res = $this->payService->createPreOrder(\Yii::$app->params['bus_order_type_list'][$order['order_type']] . date('YmdHis'), $order['pay_order_no'], $order['money'], 'bus-order');
            }
            $res['order_no'] = $order['order_no'];
            $this->success($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 订单手动支付
     */
    public function actionPay()
    {
        try {
            $post = \Yii::$app->request->post();
            $order = BusOrder::findOne(['order_no' => $post['order_no']]);
            if ($order['customer_id'] != \Yii::$app->user->id) {
                throw new \Exception('参数有误');
            }
            if ($order['status'] != BusOrderService::BUS_ORDER_STATUS_PENDING) {
                throw new \Exception('订单状态有误');
            }
            $order['pay_order_no'] = $this->payService->generateTradeNo();
            $order->save();
            $res = $this->payService->createPreOrder(\Yii::$app->params['bus_order_type_list'][$order['order_type']] . date('YmdHis'), $order['pay_order_no'], $order['money'], 'bus-order');
            $this->success($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 订单详情
     */
    public function actionView()
    {
        if (\Yii::$app->request->isGet) {
            try {
                $order_no = \Yii::$app->request->get('order_no');
                if (!$order_no) {
                    throw new \Exception('参数有误');
                }
                $busOrder = BusOrder::find()->where(['order_no' => $order_no])->one();
                if (!$busOrder) {
                    throw new \Exception('订单有误');
                }
                $customer = \Yii::$app->user->identity;
                if (\Yii::$app->user->id != $busOrder['customer_id'] && \Yii::$app->user->id != $busOrder['driver_id']) {
                    throw new \Exception('订单有误');
                }
                $outInfos = BusOrderOutInfo::findAll([
                    'order_id' => $busOrder['id'],
                    'type' => $customer['type'] == 1 ? 1 : 2
                ]);
                $orderInfos = [];
                foreach ($outInfos as $v) {
                    $orderInfos[] = $v['detail'];
                }
                $res = [
                    'order_no' => $busOrder['order_no'],//订单号
                    'title' => $busOrder['title'],//标题
                    'money' => $busOrder['money'],//订单金额
                    'order_type' => $busOrder['order_type'],//订单金额
                    'travel_time' => date('Y.m.d H:i', $busOrder['start_time']),//出行时间
                    'status' => $busOrder['status'],//订单状态
                    'status_name' => $this->busOrderService->showStatusName($busOrder),//订单状态
                    'use_people' => $busOrder['use_people'],//包车人
                    'mobile' => $busOrder['mobile'],//联系电话
                    'seat_type' => $busOrder['seat_type'],//座位类型
                    'car_type' => $busOrder['car_type'],//车辆类型
                    'dispatch_start' => $busOrder['dispatch_start'],//起点
                    'start_point' => $busOrder['start_point'],//起点经纬度
                    'end_point' => $busOrder['end_point'],//起点
                    'dispatch_end' => $busOrder['dispatch_end'],//终点经纬度
                    'start_time' => date('Y.m.d H:i', $busOrder['start_time']),//起点
                    'end_time' => date('Y.m.d H:i', $busOrder['end_time']),//起点
                    'remark' => $busOrder['remark'],//备注
                    'reason' => $busOrder['reason'],
                    'show_cancel' => $this->busOrderService->showCancel($busOrder),
                    'order_info' => $orderInfos
                ];
                $this->success($res);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * 司机接单
     */
    public function actionReceive()
    {
        try {
            $post = \Yii::$app->request->post();
            if (!$post['order_no']) {
                throw new \Exception('参数有误');
            }
            $this->busOrderService->receiveOrder($post['order_no']);
            $this->success();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 司机拒绝接单
     */
    public function actionReject()
    {
        try {
            $post = \Yii::$app->request->post();
            if (!$post['order_no']) {
                throw new \Exception('参数有误');
            }
            $this->busOrderService->rejectOrder($post['order_no']);
            $this->success();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function actionScan()
    {
        try {
            $post = \Yii::$app->request->post();
            if (!$post['order_no']) {
                throw new \Exception('参数有误');
            }
            $this->busOrderService->scanOrder($post['order_no'], $post['type']);
            $this->success();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 会员完成订单，状态置为已完成
     */
    public function actionComplete()
    {
        try {
            $post = \Yii::$app->request->post();
            if (!isset($post['order_no']) || $post['order_no'] == '') {
                throw new \Exception('参数有误');
            }
            $this->busOrderService->complete($post['order_no']);
            $this->success();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 会员取消订单
     */
    public function actionCancel()
    {
        try {
            $post = \Yii::$app->request->post();
            if (!$post['order_no']) {
                throw new \Exception('参数有误');
            }
            $this->busOrderService->cancel($post['order_no']);
            $this->success();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

}
