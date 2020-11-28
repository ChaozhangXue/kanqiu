<?php

namespace backend\modules\erp\controllers;

use common\models\Bus;
use common\models\BusLine;
use common\models\Customer;
use common\models\OrderMoneyRule;
use common\models\PackageOrder;
use common\services\DriverOrderService;
use common\services\DriverService;
use common\services\OrderService;
use common\services\OrderTraceService;
use common\services\PackageOrderService;
use common\services\PackageTraceService;
use common\services\ServiceStationService;
use Yii;
use common\models\Order;
use backend\models\search\OrderSearch;
use backend\controllers\BaseController;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for Order model.
 */
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

    public function init()
    {
        $this->orderService = new OrderService();
        $this->packageOrderService = new PackageOrderService();
        $this->orderTraceService = new OrderTraceService();
        $this->driverService = new DriverService();
        $this->serviceStationService = new ServiceStationService();
        $this->packageTraceService = new PackageTraceService();
        $this->driverOrderService = new DriverOrderService();

        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $driverList = Customer::find()->select(['customer_id', 'realname'])->where(['type' => 1, 'status' => 1])->asArray()->all();
        $busList = Bus::find()->select(['id', 'card', 'car_type'])->asArray()->all();

        //获取进行中的订单的司机和车辆信息  去除
        $on_order = Order::find()->where(['in', 'status', ['3', '4']])->asArray()->all();
        $driver_ary = array_unique(array_column($on_order, 'driver_id'));
        $bus_ary = array_unique(array_column($on_order, 'bus_id'));

        if(!empty($driverList)){
            foreach($driverList as $key => $val){
                if(in_array($val['customer_id'], $driver_ary))
                {
                    unset($driverList[$key]);
                }
            }
        }

        if(!empty($busList)) {
            foreach ($busList as $key => $val) {
                if (in_array($val['id'], $bus_ary)) {
                    unset($busList[$key]);
                }
            }
        }

        $busLine = BusLine::find()->select(['id', 'station_name'])->asArray()->all();
        return $this->render('index', [
            'driverList' => $driverList,
            'busList' => $busList,
            'busLine' => $busLine,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    /**
     * 生成订单
     * @throws \yii\db\Exception
     * @throws \Exception
     */
    public function actionGenerate()
    {
        $package = PackageOrder::find()
            ->where(['status' => Yii::$app->params['package_status']['wait_arrive_center']])
            ->andWhere(['NOT', ['package_list_id' => null]])
            ->asArray()
            ->all();

        if (empty($package)) {
            $this->success();
        }
        $generate_order = [];

        foreach ($package as $val) {
            $generate_order[$val['receive_station_id']][] = $val;
        }

        $order_info = [];
        foreach ($generate_order as $receive_station_id => $package_data) {
            $package_id_list = implode(',', array_column($package_data, 'package_list_id'));// 取快递单号的id吧
            $order_info[] = [
                'package_id_list' => $package_id_list,
                'receive_station_id' => $package_data[0]['receive_station_id'],
                'receive_station_name' => $package_data[0]['receive_station_name'],
//                'send_station_id' => isset($package_data[0]['submit_station_id'])? $package_data[0]['submit_station_id']:0,
//                'send_station_name' => $package_data[0]['submit_station_name'],

                'send_station_id' => 0,
                'send_station_name' => "总站",
                'package_num' => count($package_data),
                'total_account' => array_sum(array_column($package_data, 'total_account')),
                'status' => Yii::$app->params['order_status']['pending'],
                'source' => 2,//1:站点 2:总站
            ];

        }

        foreach ($order_info as $value) {
            $order_model = $this->orderService->add($value);
            //增加order trace  A点服务站已揽收
            //然后更新订单id          'wait_generate' => 6,//生成送货 订单的待发货
            $this->packageOrderService->updateAll(['in', 'package_list_id', explode(',', $value['package_id_list'])],
                ['status' => Yii::$app->params['package_status']['wait_generate'], 'order_num' => $order_model->id]);
        }
        $this->success();
    }

    /**
     * 指派订单给司机
     * @throws \Exception
     */
    public function actionAssign()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                //更新order
                $post = Yii::$app->request->post();
                $model = $this->findModel($post['id']);
                $driver = Customer::findOne(['customer_id' => $post['driver_id']]);
                if (!$driver) {
                    throw new \Exception('司机信息有误，请刷新界面');
                }
                $bus = Bus::findOne(['id' => $post['bus_id']]);
                if (!$bus) {
                    throw new \Exception('车辆信息有误，请刷新界面');
                }

                if (!isset($post['yongjin'])) {
                    throw new \Exception('信息有误，请刷新界面');
                }
                $bus_line = BusLine::findOne($post['line']);
                $model->driver_id = $driver['customer_id'];
                $model->driver_name = $driver['realname'];
                $model->driver_phone = $driver['mobile'];
                $model->bus_id = $bus['id'];
                $model->card = $bus['card'];

                $time = ($post['minutes'] < 10) ? $post['hour'] . ':0' . $post['minutes']: $post['hour'] . ':' . $post['minutes'];
                $model->bus_time = $time;
                $model->yongjin = $post['yongjin'];
                $model->station_yongjin = $post['station_yongjin'];
                $model->driver_accept_type = 0;
                $model->bus_line = $bus_line->station_name;
//                $model->bus_line = $post['line'];
                $model->status = Yii::$app->params['order_status']['wait_send'];
                if (!$model->save()) {
                    throw new \Exception(array_values($model->firstErrors)[0]);
                }

                //新建driver-order 数据
//                  指派的时候  需要再加到driver order里面   ===  就恶心点单独拉出来吧
                $orders['driver_id'] = $post['driver_id'];
                $orders['driver_name'] = $driver->realname;
                $orders['driver_phone'] = $driver->mobile;
                $orders['bus_id'] = $post['bus_id'];
                $orders['bus_card'] = $bus->card;
                $orders['bus_line'] = $bus_line->station_name;
                $orders['car_type'] = $bus['car_type'];
                $orders['status'] = 1;
                $orders['id'] = $model->id;
                $orders['commission'] = $post['yongjin'];//佣金
                $orders['package_num'] = $model->package_num;
                $orders['type'] = 2;//取货订单
//                $orders['customer'] = $model->;//取货订单
                if($model->type == 1){
                    //发货订单
                    $orders['send_station_id'] = 0;
                    $orders['send_station_name'] = "总站";
                    $orders['receive_station_id'] = $model->receive_station_id;
                    $orders['receive_station_name'] = $model->receive_station_name;

                    //轨迹
                }else{
                    //取货订单
                    $orders['send_station_id'] = $model->send_station_id;
                    $orders['send_station_name'] = $model->send_station_name;
                    $orders['receive_station_id'] = 0;
                    $orders['receive_station_name'] = "总站";
                    //轨迹
                }

                $this->driverOrderService->pushToDriver($orders, 'station-order');
                $this->messageService->sendMessage('调度消息', '包裹订单' . $model->id, $driver['customer_id'], ['type' => 'receive']);

                $this->orderTraceService->add(['order_id' => $model->id, 'detail' => $model->send_station_name . '服务站已揽收']);
                $this->success();
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

}
