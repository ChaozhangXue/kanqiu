<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\BusLine;
use common\models\Customer;
use common\models\DriverOrder;
use common\models\Order;
use common\models\PackageOrder;
use common\models\PackageTrace;
use common\models\ServiceStation;
use common\services\BillService;
use common\services\BusOrderService;
use common\services\DriverOrderService;
use common\services\DriverService;
use common\services\MessageService;
use common\services\OrderService;
use common\services\OrderTraceService;
use common\services\PackageOrderService;
use common\services\PackageTraceService;
use common\services\ServiceStationService;
use Yii;
use yii\base\InvalidConfigException;

class OrderController extends BaseController
{
    public $modelClass = 'common\models\Order';
    /** @var OrderService $orderService */
    public $orderService;
    /** @var PackageOrderService $packageOrderService */
    public $packageOrderService;
    /** @var OrderTraceService $orderTraceService */
    public $orderTraceService;

    /** @var DriverService $driverService */
    public $driverService;
    /** @var ServiceStationService $serviceStationService */
    public $serviceStationService;

    /** @var PackageTraceService $packageTraceService */
    public $packageTraceService;
    /** @var DriverOrderService $driverOrderService */
    public $driverOrderService;

    /** @var messageService $messageService */
    public $messageService;

//
//    const STATUS_PENDING = 1;//待指派
//    const STATUS_WAIT_SEND = 2;//待配送
//    const STATUS_SENDING_BIND = 3;//绑定后的 配送中
//    const STATUS_SENDING = 4;//点击开始行程的 配送中
//    const STATUS_ARRIVE = 5;//服务站 已签收
//    const STATUS_COMPLETE = 6;//订单完成

    public function init()
    {
        $this->orderService = new OrderService();
        $this->packageOrderService = new PackageOrderService();
        $this->orderTraceService = new OrderTraceService();
        $this->driverService = new DriverService();
        $this->serviceStationService = new ServiceStationService();
        $this->packageTraceService = new PackageTraceService();
        $this->driverOrderService = new DriverOrderService();
        $this->messageService = new MessageService();

        try {
            parent::init();
        } catch (InvalidConfigException $e) {
            $this->error($e->getMessage());
        }
    }

    public function actions()
    {
        $actions = parent::actions();
        // 禁用""index,delete" 和 "create" 操作
        unset($actions['create']);
        return $actions;
    }

    /**
     * 小程序的pending
     */
    public function actionPending()
    {
        $phone = Yii::$app->request->get('phone');
//        $station_id = Yii::$app->request->post('customer_id');
        $busOrderService = new BusOrderService();
        $bus_order = $busOrderService->index(2, $this->getLimit());

        $receive = PackageOrder::find()->where("receiver_phone = $phone and status <= 9")->orderBy('id desc')->asArray()->all();

        $send = PackageOrder::find()->where("sender_phone = $phone and status <= 9")->orderBy('id desc')->asArray()->all();

        if (!empty($send)) {
            foreach ($send as $key => $val) {
                $trace_data = PackageTrace::find()->where(['package_id' => $val['id']])->orderBy('id desc')->one();
                if (empty($trace_data)) {
                    $trace = '';
                } else {
                    $trace = $trace_data->detail;
                }
                $send[$key]['trace'] = $trace;
            }
        }
        $this->success(['bus_order' => $bus_order, 'receive' => $receive, 'send' => $send]);
    }

    /**
     * 服务站在上一步绑定了包裹码吼啊，  点击按钮 然后触发生成取货订单,  然后可以让司机来件
     */
    public function actionGenerateGetOrder()
    {
        //把这个submit_station_id  = station_id  状态为3的 都更新为四  然后生成订单
        $station_id = Yii::$app->request->post('station_id');

        $packages = PackageOrder::find()
            ->where(['submit_station_id' => $station_id, 'status' => Yii::$app->params['package_status']['wait_after_bind']])
            ->andWhere(['NOT', ['package_list_id' => null]])
            ->asArray()
            ->all();
        if (empty($packages)) {
            $this->success();
        }

        $order = new Order();
        $package_id_list = implode(',', array_column($packages, 'package_list_id'));
        $order->package_id_list = $package_id_list;
//        $order->receive_station_id = $packages[0]['receive_station_id'];
//        $order->receive_station_name = $packages[0]['receive_station_name'];
        $order->receive_station_id = 0;
        $order->receive_station_name = "总站";
        $order->send_station_id = $packages[0]['submit_station_id'];
        $order->send_station_name = $packages[0]['submit_station_name'];
        $order->package_num = count($packages);
        $order->total_account = array_sum(array_column($packages, 'total_account'));
        $order->status = Yii::$app->params['order_status']['pending'];
        $order->source = 1;//1:站点 2:总站
        $order->type = 2;//1:发货订单 2: 取货订单
        $order->save();
        PackageOrder::updateAll(['status' => Yii::$app->params['package_status']['wait_get']], ['submit_station_id' => $station_id, 'status' => Yii::$app->params['package_status']['wait_after_bind']]);

        //将包裹订单里面的 order_num 赋值
        PackageOrder::updateAll(['order_num' => $order->id], ['in', 'package_list_id', array_column($packages, 'package_list_id')]);

        $this->success();
    }

    /**
     * 司机扫码 去总站接单 扫后台的二维码绑定
     * @throws \Exception
     */
    public function actionDriverScan()
    {
        //绑定当前司机的信息和订单的信息
        $driver_id = \Yii::$app->request->post('driver_id');
        $order_id = \Yii::$app->request->post('order_id');
        $driver = Customer::findOne($driver_id);

        //更新订单表        状态变成 'wait_bind' => 3,//配送中  司机到总站 扫码 绑定后的  和司机信息
        $order_data = Order::findOne($order_id);

        if ($order_data->status == Yii::$app->params['order_status']['wait_send']) {
            //更新订单表        状态变成 'wait_bind' => 3,//配送中  司机到总站 扫码 绑定后的  和司机信息
            //表示司机到总站 然后扫码接单
            $order_data->driver_name = $driver->realname;
            $order_data->driver_phone = $driver->mobile;
            $order_data->driver_phone = $driver->mobile;
            $order_data->status = Yii::$app->params['order_status']['wait_bind'];
            $order_data->save();
        }

        $this->success();
    }

    /**
     * 司机点击开始行程
     * @throws \Exception
     */
    public function actionDriverPressStart()
    {
        $order_id = \Yii::$app->request->post('order_id');

        //订单状态改成         'sending' => 4,//配送中   司机 点击开始行程的
        $order_data = Order::findOne($order_id);
        if (!empty($order_data)) {
            $order_data->status = Yii::$app->params['order_status']['sending'];
            $order_data->deliver_time = date("Y-m-d H:i:s");
            $order_data->save();
        } else {
            $this->error('订单未找到');
        }


        //driver-order状态改成        3:配送中   需要更新状态为2 的订单  不然会把拒单的也改了
        $driver_order = DriverOrder::find()->where(['order_id' => $order_id, 'status' => 2])->one();
        if (!empty($driver_order)) {
            $driver_order->status = 3; //状态 1:未操作 2:已接单 0已拒绝 3:配送中 4:已完成
            $driver_order->save();
        } else {
            $this->error('司机订单未找到');
        }

        //订单trace 增加 快件从A服务站已发出,下一站某某站点,联系电话:xxx


        //如果是送件包裹  package 更新 状态         'sending' => 7,//配送中
        $customer = Customer::find()->where(['relation_id' => $order_data->receive_station_id, 'type' => 3])->all();

        if ($order_data->type == 1) {
            PackageOrder::updateAll(['status' => Yii::$app->params['package_status']['sending']], ['and', ['in', 'package_list_id', explode(',', $order_data->package_id_list)]]);

            if (!empty($customer) && $order_data->receive_station_id != 0) {
                foreach ($customer as $val) {
                    //如果是送件订单  增加给服务站的推送
                    $this->messageService->sendMessage('调度消息', '包裹订单' . $order_data->id . '已送出，即将到达本站', $val->customer_id, ['type' => 'wait_arrive']);
                }
            }
            $send_station = ServiceStation::findOne($order_data->send_station_id);
            $receive_station = ServiceStation::findOne($order_data->receive_station_id);
            $this->orderTraceService->add(
                [
                    'order_id' => $order_id,
                    'detail' => "快件从" . $send_station->station_name . "已发出,下一站" . $receive_station->station_name . "站点,联系电话:" . $receive_station->telephone
                ]);
        } else {
//            取件订单
            if (!empty($customer) && $order_data->receive_station_id != 0) {
                foreach ($customer as $val) {
                    //如果是送件订单  增加给服务站的推送
                    $this->messageService->sendMessage('调度消息', '即将会有司机来取包裹订单' . $order_data->id, $val->customer_id, ['type' => 'wait_arrive']);
                }
            }
        }
        $bus_line = BusLine::find()->where(['station_name' => $order_data->bus_line])->asArray()->one();

        $this->success($bus_line);
    }

    /**
     * 司机将包裹订单送达总站
     * @throws \Exception
     */
    public function actionArriveCenter()
    {
        $order_id = \Yii::$app->request->post('order_id');
        //更新 driver-order  4已完成
        $driver_order = DriverOrder::find()->where(['order_id' => $order_id, 'status' => 3])->one();
        $driver_order->status = 4; //状态 1:未操作 2:已接单 0已拒绝 3:配送中 4:已完成
        $driver_order->save();

        //更新订单         'complete' => 6,//订单完成  所有的包裹都被用户收了之后
        $order_data = Order::findOne($order_id);
        $order_data->status = Yii::$app->params['order_status']['complete'];
        $order_data->receiver_time = date("Y-m-d H:i:s");
        $order_data->save();

        //更新包裹          'wait_arrive_center' => 5,//到达总站的待发货
        $list_id = explode(',', $order_data->package_id_list);
        PackageOrder::updateAll(['status' => Yii::$app->params['package_status']['wait_arrive_center']], ['in', 'package_list_id', $list_id]);

        $order = Order::find()->where(['id' => $order_id])->one();
        //增加司机结算  2表示包裹订单
        $billService = new BillService();
        $billService->saveDriverBill(2, $order, $type = 'single');
        //增加站点结算
        $billService->saveStationBill($order);

        $this->success();
    }

    /**
     * 服务站点扫码获取司机的货物
     * @throws \Exception
     */
    public function actionStationScan()
    {
        $order_id = \Yii::$app->request->post('order_id');

        $order = Order::findOne($order_id);
        if ($order->type == 1) {
            if ($order->status != Yii::$app->params['order_status']['sending']) {
                $this->error();
            }
            //发货订单
            //        driver-order 4  已完成    order   5,//已签收 服务站  服务站扫描二维码    package 8,//已签收  服务站已签收
            //更新订单状态
            $order_data = $this->orderService->update($order_id, ['status' => Yii::$app->params['order_status']['arrive'], 'station_time' => date("Y-m-d H:i:s")]);

            //更新driver 订单状态
            $driver_order = DriverOrder::find()->where(['order_id' => $order_id])->one();
            $driver_order->status = 4; //状态 1:未操作 2:已接单 0已拒绝 3:配送中 4:已完成
            $driver_order->save();

            //更新包裹状态
            $list_id = explode(',', $order_data->package_id_list);
            PackageOrder::updateAll(['status' => Yii::$app->params['package_status']['arrive']], ['in', 'package_list_id', $list_id]);

            //更新订单轨迹
            $station_id = $order_data->receive_station_id;
            $station_data = $this->serviceStationService->getWhereOne(['id' => $station_id]);
            $this->orderTraceService->add(['order_id' => $order_id, 'detail' => "包裹已到达" . $station_data['station_name'] . "站点,联系电话:" . $station_data['telephone']]);


            //需要发送短息给用户
            //拿到所有的包裹的用户的手机号 然后循环调用发送接口
            $packages = PackageOrder::find()->where(['in', 'package_list_id', $list_id])->asArray()->all();

            if (!empty($packages)) {
                foreach ($packages as $val) {
                    $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                    $this->messageService->sendSms($val['receiver_phone'], $code, 7, ['name' => $val['receiver'], 'store' => $order->receive_station_name]);
                    $this->packageTraceService->add(['package_id' => $val['id'], 'detail' => "快件已到达" . $val['receive_station_name'] . "站点，联系电话：" . $val['receive_station_phone']]);
                }
            }

        } else {
            //取货订单

            //        driver-order 3  已完成    order   5,//已签收 服务站  服务站扫描二维码    package  'arrive_to_get' => 5,//司机到达服务站 服务站扫码 然后让司机拿货

            if ($order->status != Yii::$app->params['order_status']['sending']) {
                $this->error();
            }
            $list_id = explode(',', $order->package_id_list);

            // 需要加上这个轨迹
            $packages = PackageOrder::find()->where(['in', 'package_list_id', $list_id])->asArray()->all();

            if (!empty($packages)) {
                foreach ($packages as $val) {
                    $this->packageTraceService->add(['package_id' => $val['id'], 'detail' => "已从" . $order->send_station_name . "服务点服务站寄出"]);
                }
            }

            //更新订单状态
            $order_data = $this->orderService->update($order_id, ['status' => Yii::$app->params['order_status']['arrive'], 'station_time' => date("Y-m-d H:i:s")]);

//            $this->packageTraceService->add(['package_id' => $val['id'], 'detail' =>"已从张菊平农村物流服务点服务站寄出"]);

            //更新driver 订单状态
//            $driver_order = DriverOrder::find()->where(['order_id' => $order_id])->one();
//            $driver_order->status = 3; //状态 1:未操作 2:已接单 0已拒绝 3:配送中 4:已完成
//            $driver_order->save();


            //更新包裹状态
            $list_id = explode(',', $order_data->package_id_list);
            PackageOrder::updateAll(['status' => Yii::$app->params['package_status']['arrive_to_get']], ['in', 'package_list_id', $list_id]);
        }

        $this->success();
    }


    /**
     * 获取服务站点发货订单
     */
    public function actionGetStationSend()
    {
        $limit = $this->getLimit();

    }

    /**
     * 获取服务站点收货订单
     */
    public function actionGetStationReceive()
    {

    }

    /**
     * 获取服务站点完成订单
     */
    public function actionGetStationComplete()
    {
        $limit = $this->getLimit();
        $station_id = \Yii::$app->request->get('station_id');

        $order = Order::find()->where(['status' => 6])
            ->andWhere(['or', ['receive_station_id' => $station_id], ['send_station_id' => $station_id]])
            ->asArray()
            ->offset($limit[0])
            ->limit($limit[1])
            ->all();

        $this->success(['item' => $order]);

        //收货订单  状态为6的 receive = station id 的
        //发货订单  状态为6 send = station id
//        $list = $selector->orderBy('id desc')
//            ->offset($limit[0])
//            ->limit($limit[1])
//            ->all();
    }

//    /**
//     * 生成送货订单
//     * @throws \Exception
//     */
//    public function actionGenerate()
//    {
//        $package = $this->packageOrderService->getWhere(['status' => Yii::$app->params['package_status']['wait_arrive_center']]);
//
//        if (empty($package)) {
//            $this->success();
//        }
//        $generate_order = [];
//
//        foreach ($package as $val) {
//            $generate_order[$val['receive_station_id']][] = $val;
//        }
//
//        $order_info = [];
//        foreach ($generate_order as $receive_station_id => $package_data) {
//            $package_id_list = implode(',', array_column($package_data, 'package_list_id'));// 取快递单号的id吧
//            $order_info[] = [
//                'package_id_list' => $package_id_list,
//                'receive_station_id' => $package_data[0]['receive_station_id'],
//                'receive_station_name' => $package_data[0]['receive_station_name'],
//                'send_station_id' => $package_data[0]['submit_station_id'],
//                'send_station_name' => $package_data[0]['submit_station_name'],
//                'package_num' => count($package_data),
//                'total_account' => array_sum(array_column($package_data, 'total_account')),
//                'status' => Yii::$app->params['order_status']['pending'],
//                'source' => 2,//1:站点 2:总站
//            ];
//
//        }
//
//        foreach ($order_info as $value) {
//            $order_model = $this->orderService->add($value);
//            //增加order trace  A点服务站已揽收
//            //然后更新订单id          'wait_generate' => 6,//生成送货 订单的待发货
//            $this->packageOrderService->updateAll(['in', 'package_list_id', explode(',', $value['package_id_list'])],
//                ['status' => Yii::$app->params['package_status']['wait_generate'], 'order_num' => $order_model->id]);
//        }
//        $this->success();
//    }

//

    /**
     * @throws \Exception
     */
    public function actionDriverAccept()
    {
        $order_id = \Yii::$app->request->post('order_id');
        $order_data = $this->orderService->getWhereOne(['id' => $order_id], false);


        if ($order_data->type == 2) {
            //取货流程  跳过扫码的步骤  直接进到司机点击开始行程按钮

            $driver = Customer::findOne(\Yii::$app->user->id);

            //更新订单表        状态变成 'wait_bind' => 3,//配送中  司机到总站 扫码 绑定后的  和司机信息
            $order_data = Order::findOne($order_id);

//            if ($order_data->status == Yii::$app->params['order_status']['wait_send']) {
            //更新订单表        状态变成 'wait_bind' => 3,//配送中  司机到总站 扫码 绑定后的  和司机信息
            //表示司机到总站 然后扫码接单
            $order_data->driver_name = $driver->realname;
            $order_data->driver_phone = $driver->mobile;
            $order_data->status = Yii::$app->params['order_status']['wait_bind'];
            $order_data->save();
            
        } else {
            $order_data->driver_accept_type = 1;//司机接受类型 0等待接受  1 接受 2 拒绝
            $order_data->save();
        }
        //driver order里面内容也要修改
        $driverOrder = DriverOrder::findOne(['order_type' => 1, 'order_id' => $order_id]);
        if ($driverOrder) {
            $driverOrder->status = 2;
            $driverOrder->save();
        }

        $this->success();
    }

    /**
     * 根据收获的站点id来获取首页信息
     * @throws \Exception
     */
    public function actionGetByReceiveId()
    {
        $receive_station_id = \Yii::$app->request->get('station_id');

        $ret = $this->orderService->getOrderByStationId($receive_station_id);
        $this->success($ret);
    }



//    public function actionAssign()
//    {
//        //driver_id driver_name driver_phone driver_accept_type bus_line card
//        $driver_id = \Yii::$app->request->post('driver_id');
//        $order_id = \Yii::$app->request->post('order_id');
//        $bus_id = \Yii::$app->request->post('bus_id');
//
//        $bus = Bus::findOne($bus_id);
//        if (!$bus) {
//            throw new \Exception('车辆信息有误，请刷新界面');
//        }
//        //获取司机信息
//        $driver = $this->driverService->getWhereOne(['customer_id' => $driver_id], false);
//        $order = $this->orderService->getWhereOne(['id' => $order_id], false);
//        $order->driver_id = $driver_id;
//        $order->driver_name = $driver->realname;
//        $order->driver_phone = $driver->mobile;
//        $order->driver_accept_type = 0;
//        $order->bus_line = '';
//        $order->card = $bus->card;
//        $order->yongjin = 1;
//        if (!$order->save()) {
//            throw new \Exception(array_values($order->firstErrors)[0]);
//        }
//
//        //指派的时候  需要再加到driver order里面   ===  就恶心点单独拉出来吧
//        $orders['driver_id'] = $driver_id;
//        $orders['driver_name'] = $driver->realname;
//        $orders['driver_phone'] = $driver->mobile;
//        $orders['bus_id'] = $bus_id;
//        $orders['bus_card'] = $bus->card;
//        $orders['car_type'] = $bus['car_type'];
//        $orders['status'] = 1;
//        $orders['id'] = $order->id;
//        $orders['commission'] = 1;//佣金
//        $orders['package_num'] = $order->package_num;//佣金
//        $orders['send_station_id'] = $order->send_station_id;
//        $orders['send_station_name'] = $order->send_station_name;
//        $orders['receive_station_id'] = $order->receive_station_id;
//        $orders['receive_station_name'] = $order->receive_station_name;
//        $this->driverOrderService->pushToDriver($orders, 'station-order');
//
//        $this->success();
//
//    }
}
