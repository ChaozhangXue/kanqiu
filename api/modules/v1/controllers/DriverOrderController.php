<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\DriverOrder;
use common\services\BusOrderService;
use common\services\DriverOrderService;
use common\services\OrderService;

class DriverOrderController extends BaseController
{
    public $modelClass = 'common\models\DriverOrder';

    public $filter = true;

    /** @var BusOrderService $busOrderService */
    public $busOrderService;
    /** @var OrderService $busOrderService */
    public $orderService;

    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'dataFilter' => [
                    'class' => 'yii\data\ActiveDataFilter',
                    'searchModel' => ['class' => 'backend\models\search\DriverOrderSearch']
                ]
            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkModel'],
            ]
        ];
    }

    public function behaviors()
    {
        $customer = \Yii::$app->user->identity;
        if ($customer['type'] == 1) {
            $this->user_primary = 'driver_id';
        } else if ($customer['type'] == 2) {
            $this->user_primary = 'customer_id';
        } else {
           $this->error('用户身份有误');
        }
        $this->busOrderService = new BusOrderService();
        $this->orderService = new OrderService();
        //列表查询增加默认筛选
        $requestParams = \Yii::$app->request->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->request->queryParams;
            if (!isset($requestParams['filter']['status'])) {
                $requestParams['filter']['status'] = [
                    DriverOrderService::DRIVER_ORDER_STATUS_PENDING,
                    DriverOrderService::DRIVER_ORDER_STATUS_ACCEPT,
                    DriverOrderService::DRIVER_ORDER_STATUS_CONFIRM
                ];
            }
            if (!isset($requestParams['sort'])) {
                $requestParams['sort'] = '-status';
            }
            \Yii::$app->request->setQueryParams($requestParams);
        } else {
            if (!isset($requestParams['filter']['status'])) {
                $requestParams['filter']['status'] = [
                    DriverOrderService::DRIVER_ORDER_STATUS_PENDING,
                    DriverOrderService::DRIVER_ORDER_STATUS_ACCEPT,
                    DriverOrderService::DRIVER_ORDER_STATUS_CONFIRM
                ];
            }
            if (!isset($requestParams['sort'])) {
                $requestParams['sort'] = '-status';
            }
            \Yii::$app->request->setBodyParams($requestParams);
        }
        return parent::behaviors();
    }

    public function actionAccept()
    {
        try {
            $post = \Yii::$app->request->post();
            $driverOrder = DriverOrder::findOne($post['id']);
            if ($driverOrder['order_type'] == 1) {
//                $this->orderService->receiveOrder($driverOrder['order_no']);
            } else if ($driverOrder['order_type'] == 2) {
                $this->busOrderService->receiveOrder($driverOrder['order_no']);
            }
            $this->success();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function actionReject()
    {
        try {
            $post = \Yii::$app->request->post();
            $driverOrder = DriverOrder::findOne($post['id']);
            if ($driverOrder['order_type'] == 1) {
                //包裹
                $this->orderService->rejectOrder($driverOrder['order_no']);
            } else if ($driverOrder['order_type'] == 2) {
                $this->busOrderService->rejectOrder($driverOrder['order_no']);
            }
            $this->success();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}

