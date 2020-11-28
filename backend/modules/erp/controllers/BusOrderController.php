<?php

namespace backend\modules\erp\controllers;

use backend\controllers\BaseController;
use backend\models\search\BusOrderSearch;
use common\models\Bus;
use common\models\BusMoneyRule;
use common\models\BusOrder;
use common\models\BusOrderTrace;
use common\models\Customer;
use common\services\BusOrderService;
use common\services\DriverOrderService;
use common\services\PayService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * BusOrderController implements the CRUD actions for BusOrder model.
 */
class BusOrderController extends BaseController
{
    /** @var BusOrderService $busOrderService */
    public $busOrderService;
    /** @var PayService $payService */
    public $payService;
    /** @var DriverOrderService $driverOrderService */
    public $driverOrderService;

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

    public function init()
    {
        $this->busOrderService = new BusOrderService();
        $this->payService = new PayService();
        $this->driverOrderService = new DriverOrderService();
        parent::init();
    }

    /**
     * Lists all BusOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BusOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $driverList = Customer::find()->select(['customer_id', 'realname'])->where(['type' => 1, 'status' => 1])->asArray()->all();
        $busList = Bus::find()->select(['id', 'card', 'car_type'])->asArray()->all();
        return $this->render('index', [
            'driverList' => $driverList,
            'busList' => $busList,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BusOrder model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $driverList = Customer::find()->select(['customer_id', 'realname'])->where(['type' => 1, 'status' => 1])->asArray()->all();
        $busList = Bus::find()->select(['id', 'card', 'car_type'])->asArray()->all();
        return $this->render('view', [
            'driverList' => $driverList,
            'busList' => $busList,
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BusOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEdit()
    {

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $order = $this->busOrderService->saveOrder($post);
            if ($order->hasErrors()) {
                $order->load($post);
                return $this->render('edit', [
                    'model' => $order,
                ]);
            }
            return $this->redirect(['view', 'id' => $order->id]);

        }
        $order = new BusOrder();
        $order->order_type = 1;
        $get = Yii::$app->request->get();
        if (isset($get['id']) && $get['id']) {
            $order = $this->findModel($get['id']);
            $order->start_time = $order->start_time ? date('Y-m-d H:i:s', $order->start_time) : '';
            $order->end_time = $order->end_time ? date('Y-m-d H:i:s', $order->end_time) : '';
        }
        return $this->render('edit', [
            'model' => $order,
        ]);
    }

    public function actionCancel()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $post = Yii::$app->request->post();
                $this->busOrderService->cancel($post['order_no'], 1, 0);
                $this->success();
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }


    /**
     * 指派订单给司机
     * @throws \Exception
     */
    public function actionAssign()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $post = Yii::$app->request->post();
                $this->busOrderService->assignOrder($post);
                $this->success();
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * 支付
     * @throws \Exception
     */
    public function actionPay()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $post = Yii::$app->request->post();
                $order = $this->findModel($post['id']);
                if ($order->status != BusOrderService::BUS_ORDER_STATUS_PENDING) {
                    throw new \Exception('订单状态有误，请刷新界面');
                }
                $order->pay_method = $post['pay_method'];
                $order->pay_money = $post['pay_money'];
                $order->transaction_id = $post['transaction_id'];
                $order['status'] = BusOrderService::BUS_ORDER_STATUS_PAY;
                $order->save();
                $this->busOrderService->trace($order->id, '后台订单修改为已支付，支付方式【' . Yii::$app->params['pay_method_list'][$order->pay_method] . '】，金额【' . $order['pay_money'] . '】，交易流水号【' . $order->transaction_id . '】');
                $this->success();
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * 订单退款
     */
    public function actionRefund()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $post = Yii::$app->request->post();
                $order = $this->findModel($post['id']);
                if ($order->status != BusOrderService::BUS_ORDER_STATUS_REFUND) {
                    throw new \Exception('订单状态有误，请刷新界面');
                }
                if ($order->money != 0) {
                    $this->payService->refund($order, 2);
                }
                $order['status'] = BusOrderService::BUS_ORDER_STATUS_CANCEL;
                if (!$order->save()) {
                    throw new \Exception(array_values($order->firstErrors)[0]);
                }
                $this->busOrderService->trace($order->id, '后台操作退款，退款金额' . $order['money']);
                $this->success();
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    public function actionMoneyRule()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $post = Yii::$app->request->post();
                $this->busOrderService->saveMoneyRule($post);
                $this->success();
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
        $list = BusMoneyRule::find()->asArray()->all();
        $res = [];
        foreach ($list as $v) {
            $res[$v['start'] . '-' . $v['end']][$v['car_type']] = $v['money'];
        }
        return $this->render('money-rule', [
            'list' => $res,
        ]);
    }

    public function actionGetInfo()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $post = Yii::$app->request->post();
                if (isset($post['order_no']) && $post['order_no'] == '') {
                    throw new \Exception('参数有误');
                }
                $order = BusOrder::find()->where(['order_no' => $post['order_no']])->one();
                $traceList = BusOrderTrace::find()->where(['order_id' => $order['id']])->all();
                $res = $this->renderPartial('info', [
                    'order' => $order,
                    'traceList' => $traceList
                ]);
                $this->success('success', $res);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * Finds the BusOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($order = BusOrder::findOne($id)) !== null) {
            return $order;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
