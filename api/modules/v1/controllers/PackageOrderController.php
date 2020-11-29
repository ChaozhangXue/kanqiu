<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\Order;
use common\models\PackageList;
use common\models\PackageOrder;
use common\models\PackageTrace;
use common\models\ServiceStation;
use common\services\BillService;
use common\services\MapService;
use common\services\OrderService;
use common\services\PackageOrderService;
use common\services\PackageTraceService;
use common\services\PayService;
use common\services\ServiceStationService;
use Yii;
use yii\base\InvalidConfigException;

class PackageOrderController extends BaseController
{
    public $modelClass = 'common\models\PackageOrder';

    /** @var PackageOrderService $packageOrderService */
    public $packageOrderService;
    /** @var MapService $mapService */
    public $mapService;

    /** @var OrderService $orderService */
    public $orderService;

    /** @var ServiceStationService $serviceStationService */
    public $serviceStationService;

    /** @var PackageTraceService $packageTraceService */
    public $packageTraceService;

    public function init()
    {
        $this->packageOrderService = new PackageOrderService();
        $this->mapService = new MapService();
        $this->orderService = new OrderService();
        $this->serviceStationService = new ServiceStationService();
        $this->packageTraceService = new PackageTraceService();
        try {
            parent::init();
        } catch (InvalidConfigException $e) {
            $this->error($e->getMessage());
        }
    }


    /**
     * @throws \Exception
     */
    public function actionRejectPackage()
    {
        $package_id = \Yii::$app->request->post('package_id');
        $order = PackageOrder::findOne(['id' => $package_id]);
//        $order->station_check = 3;

        $pay_service = new PayService();
        $pay_service->refund($order,1);
        file_put_contents('/tmp/123.txt', print_r($order,true));
        $order->delete();

        $this->success();
    }
    /**
     * 订单手动支付
     */
    public function actionPay()
    {
        try {
            $post = \Yii::$app->request->post();
            $order = PackageOrder::findOne(['id' => $post['order_no']]);
//            if ($order['customer_id'] != \Yii::$app->user->id) {
//                throw new \Exception('参数有误');
//            }
            if ($order['status'] != Yii::$app->params['package_status']['unpay']) {
                throw new \Exception('订单状态有误');
            }
            $payService = new PayService();
            $order['pay_order_no'] = $payService->generateTradeNo();
            $order->save();
            $res = $payService->createPreOrder("包裹订单" . $order['id'], $order['pay_order_no'], $order['total_account'], 'package-order');
            $this->success($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function actionSearchPackage(){
        $package_list_id = Yii::$app->request->get('package_list_id');//总部生成的快递单号
        $customer_id = Yii::$app->user->getIdentity()->customer_id;

        $search = PackageOrder::find()->where(['customer_id' => $customer_id])->andWhere(['package_list_id' =>$package_list_id])->asArray()->one();

        if(empty($search)){
            $this->error('没有找到对应的包裹信息');
        }

        $search['trace'] = PackageTrace::find()->where(['package_id' => $search['id']])->all();
        $this->success($search);
    }

    /**
     * 服务站把包裹和总站发来的条码绑定  贴一个绑定一个
     */
    public function actionBindList()
    {
        //双向绑定
        $package_id = Yii::$app->request->post('package_id');//用户的包裹订单id
        $package_list_id = Yii::$app->request->post('package_list_id');//总部生成的快递单号
        $package_list = PackageList::findOne($package_list_id);
        if(empty($package_list)){
            $this->error('包裹码错误');
        }

        if(empty($package_list->package_id)){
            //修改package_order
            $package = PackageOrder::findOne($package_id);
            $package->package_list_id = $package_list_id;
            $package->status = Yii::$app->params['package_status']['wait_after_bind'];
            $package->save();

            $package_list->package_id = $package_id;
            $package_list->save();

            $this->packageTraceService->add(['package_id' => $package->id, 'detail' => $package->submit_station_name . "服务站已揽收"]);

            $this->success([], '成功');
        }else{
            $this->error('包裹码已被绑定');
        }
    }

    /**
     * 获取订单的价格
     */
    public function actionGetPrice()
    {
        //通过经纬度来计算价格
        $start = Yii::$app->request->post('start');
        $end = Yii::$app->request->post('end');
        $size_type = Yii::$app->request->post('size_type');
        $weight_type = Yii::$app->request->post('weight_type');
//        0-10kg	≤0.03m³	15㎞	3.00
//11-15kg	≤0.045m³	16-50㎞	5.00
//16-20kg	≤0.06m³	51㎞以上	6.00

//        $distance = $this->mapService->getDirection("$l1,$g1", "$l2,$g2");//返回的是公里数
        $distance = $this->mapService->getDirection($start, $end);//返回的是公里数
        if ($distance <= 15) {
            $distance_type = 1;
        } elseif ($distance > 15 && $distance <= 50) {
            $distance_type = 2;
        } elseif ($distance > 50) {
            $distance_type = 3;
        }
        /** @var int $distance_type */
        $max_type = max($size_type,$weight_type,$distance_type);

        $this->success(Yii::$app->params['money'][$max_type]);
    }

    public function actions()
    {
        $actions = parent::actions();
        // 禁用""index,delete" 和 "create" 操作
        unset($actions['create']);
        return $actions;
    }

    public function actionCreate()
    {
        if (\Yii::$app->request->isPost) {
            try {
                $data = \Yii::$app->request->post();

                $ret = $this->mapService->getPosition($data['receive_point']);
                $data['receive_station_id'] = $ret['nearest_station_id'];
                $data['receive_station_name'] = $ret['nearest_station_name'];

                //2.计算两个服务站点的里程
                $receive = $this->serviceStationService->getWhereOne(['id' => $data['receive_station_id']]);
                $data['receive_station_phone'] = $receive['telephone'];

                $send = $this->serviceStationService->getWhereOne(['id' => $data['submit_station_id']]);
                $data['submit_station_phone'] = $send['telephone'];

                $distance = $this->mapService->getDirection($receive['longitude'].",". $receive['latitude'], $send['longitude'].",". $send['latitude']);//返回的是公里数

                if ($distance <= 15) {
                    $distance_type = 1;
                } elseif ($distance > 15 && $distance <= 50) {
                    $distance_type = 2;
                } elseif ($distance > 50) {
                    $distance_type = 3;
                }
                /** @var INT $distance_type */
                $data['distance'] = $distance_type;
                $data['status'] = Yii::$app->params['package_status']['unpay'];

                $package = $this->packageOrderService->add($data);
                $this->success($package->id, 'success');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * 服务站点提交包裹订单
     */
    public function actionBatchUpdate()
    {
        $id_list = Yii::$app->request->post('id_list');
        $update = Yii::$app->request->post('update');

        $this->packageOrderService->updateAll(['in', 'id', explode(',', $id_list)], $update);
        $this->success();
    }

    /**
     * 用户取包裹的扫码
     * @throws \Exception
     */
    public function actionPickupScan()
    {
//        package 9,//已完成用户取货     只到最后一个包裹 6,//订单完成  所有的包裹都被用户收了之后

        $package_id = Yii::$app->request->post('package_id', '');
        $customer_id = Yii::$app->request->post('customer_id', '');
        // 更新包裹状态 已完成
        $package = PackageOrder::find()->where(['id' => $package_id])->one();
        if($package->status != Yii::$app->params['package_status']['arrive']){
            $this->error();
        }
        $package->status = Yii::$app->params['package_status']['complete'];
        $package->save();
        //更新包裹的轨迹 收件人已签收
        $this->packageTraceService->add(['package_id' => $package->id, 'detail' => '收件人已签收']);

        //判断订单是否是最后一个包裹  是的话  更新订单状态为完成
        $last_package = $this->packageOrderService->getWhere(['and',['order_num' => $package->order_num], ['<', 'status', Yii::$app->params['package_status']['complete']]]);
        if(empty($last_package)){
            $order = Order::find()->where(['id' => $package->order_num])->one();
            $order->status = Yii::$app->params['order_status']['complete'];
            $order->receiver_time = date("Y-m-d H:i:s");
            $order->save();
            //如果没有未完成的订单的话, 则更新order的状态为完成
//            $this->orderService->update($package->order_num, ['status' => Yii::$app->params['order_status']['complete'], ['receiver_time' => date("Y-m-d H:i:s")]]);
            $billService = new BillService();
//            'type' => 1,
            //增加司机结算  2表示包裹订单
            $billService->saveDriverBill(2, $order, $type = 'single');
            //  增加站点结算
            $billService->saveStationBill($order);
        }

        $this->success();
    }
}
