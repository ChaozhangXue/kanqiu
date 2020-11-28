<?php

namespace backend\modules\erp\controllers;

use backend\controllers\BaseController;
use common\models\Bus;
use common\services\StatisticsService;
use yii\filters\VerbFilter;

/**
 * BusController implements the CRUD actions for Bus model.
 */
class StatisticsController extends BaseController
{
    /** @var StatisticsService $statisticsService */
    public $statisticsService;

    public function init()
    {
        $this->statisticsService = new StatisticsService();
        parent::init();
    }

    public $carChartTypeList = [
        'default' => '默认',
        'car_type' => '类型-饼状图',
        'brand' => '品牌-饼状图',
        'model' => '型号-饼状图'
    ];

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

    public function actionOrder()
    {
        $params['type'] = \Yii::$app->request->get('type', 1);
        $params['order_type'] = \Yii::$app->request->get('order_type', 1);
        $params['show_type'] = \Yii::$app->request->get('show_type', 1);
        $params['bus_card'] = \Yii::$app->request->get('bus_card', '');
        $params['status'] = \Yii::$app->request->get('status', '');
        $params['start_time'] = \Yii::$app->request->get('start_time', date('Y-m-d', strtotime('-1 month')));
        $params['end_time'] = \Yii::$app->request->get('end_time', date('Y-m-d', strtotime('-1 day')));
        $errorMsg = '';
        try {
            if (!isset($params['start_time'])) {
                throw new \Exception('请选择统计开始时间');
            }
            if (!isset($params['end_time'])) {
                throw new \Exception('请选择统计开始时间');
            }
            $startTime = strtotime($params['start_time']);
            $endTime = strtotime($params['end_time']) + 24 * 3600 - 1;
            if (!$startTime || !$endTime) {
                throw new \Exception('起止时间格式有误');
            }
            if ($startTime > $endTime) {
                throw new \Exception('开始时间不能大于结束时间');
            }
            if ($params['show_type'] == 1) {
                if (date('Y-m-d', strtotime('-1 month', $endTime)) > date('Y-m-d', $startTime)) {
                    throw new \Exception('按天统计最长时间为一个月');
                }
            } else if ($params['show_type'] == 2) {
                if (date('Y-m-d', strtotime('-2 years', $endTime)) > date('Y-m-d', $startTime)) {
                    throw new \Exception('按天统计最长时间为2年');
                }
            }
            switch ($params['order_type']) {
                case '1':
                    $list = $this->statisticsService->busOrder($params);
                    break;
                case '2':
                    $list = $this->statisticsService->packageOrder($params);
                    break;
                default:
                    throw new \Exception('暂不支持此类型查询');
                    break;
            }

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $list = [];
        }
        $statusAllList = [
            1 => \Yii::$app->params['bus_order_status_list'],
            2 => \Yii::$app->params['order_status_list']
        ];
        $cardList = Bus::find()->select(['card'])->asArray()->all();
        $cardList = array_column($cardList, 'card');

        return $this->render('order', [
            'params' => $params,
            'statusAllList' => $statusAllList,
            'chartTitle' => $this->getOrderChartTitle($params),
            'errorMsg' => $errorMsg,
            'list' => $list,
            'cardList' => $cardList
        ]);
    }

    protected function getOrderChartTitle($params)
    {
        $orderTypeList = [
            1 => '客运订单',
            2 => '包裹订单',
            3 => '站点订单',
        ];
        $typeList = [
            1 => '金额',
            2 => '佣金',
            3 => '数量',
        ];
        $showTypeList = [
            1 => '每日',
            2 => '每月',
            3 => '每季',
        ];
        return $orderTypeList[$params['order_type']] . $typeList[$params['type']] . $showTypeList[$params['show_type']] . '统计';

    }

    public function actionCar()
    {
        $params['show_type'] = \Yii::$app->request->get('show_type', 1);
        $params['car_type'] = \Yii::$app->request->get('car_type');
        $params['chart_type'] = \Yii::$app->request->get('chart_type', 'default');
        $params['brand'] = \Yii::$app->request->get('brand');
        $params['model'] = \Yii::$app->request->get('model');
        $params['start_time'] = \Yii::$app->request->get('start_time', date('Y-m-d', strtotime('-1 month')));
        $params['end_time'] = \Yii::$app->request->get('end_time', date('Y-m-d', strtotime('-1 day')));
        $errorMsg = '';
        try {
            if (!isset($params['start_time'])) {
                throw new \Exception('请选择统计开始时间');
            }
            if (!isset($params['end_time'])) {
                throw new \Exception('请选择统计开始时间');
            }
            $startTime = strtotime($params['start_time']);
            $endTime = strtotime($params['end_time']) + 24 * 3600 - 1;
            if (!$startTime || !$endTime) {
                throw new \Exception('起止时间格式有误');
            }
            if ($startTime > $endTime) {
                throw new \Exception('开始时间不能大于结束时间');
            }
            if ($params['show_type'] == 1) {
                if (date('Y-m-d', strtotime('-1 month', $endTime)) > date('Y-m-d', $startTime)) {
                    throw new \Exception('按天统计最长时间为一个月');
                }
            } else if ($params['show_type'] == 2) {
                if (date('Y-m-d', strtotime('-2 years', $endTime)) > date('Y-m-d', $startTime)) {
                    throw new \Exception('按天统计最长时间为2年');
                }
            }
            $list = $this->statisticsService->car($params);
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $list = [];
        }

        return $this->render('car', [
            'params' => $params,
            'carChartTypeList' => $this->carChartTypeList,
            'chartTitle' => $this->getCarChartTitle($params),
            'errorMsg' => $errorMsg,
            'list' => $list,
        ]);
    }

    protected function getCarChartTitle($params)
    {

        $showTypeList = [
            1 => '每日',
            2 => '每月',
            3 => '每季',
        ];
        $chartTypeList = [
            'default' => '',
            'car_type' => '类型',
            'brand' => '品牌',
            'model' => '型号'
        ];
        return '车辆' . $chartTypeList[$params['chart_type']] . $showTypeList[$params['show_type']] . '统计';
    }

    public function actionStation()
    {
        $params['show_type'] = \Yii::$app->request->get('show_type', 1);
        $params['station_type'] = \Yii::$app->request->get('station_type', 1);
        $params['country'] = \Yii::$app->request->get('country');
        $params['start_time'] = \Yii::$app->request->get('start_time', date('Y-m-d', strtotime('-1 month')));
        $params['end_time'] = \Yii::$app->request->get('end_time', date('Y-m-d', strtotime('-1 day')));
        $errorMsg = '';
        try {
            if (!isset($params['start_time'])) {
                throw new \Exception('请选择统计开始时间');
            }
            if (!isset($params['start_time'])) {
                throw new \Exception('请选择统计结束时间');
            }
            $startTime = strtotime($params['start_time']);
            $endTime = strtotime($params['end_time']) + 24 * 3600 - 1;
            if (!$startTime || !$endTime) {
                throw new \Exception('起止时间格式有误');
            }
            if ($startTime > $endTime) {
                throw new \Exception('开始时间不能大于结束时间');
            }
            if ($params['show_type'] == 1) {
                if (date('Y-m-d', strtotime('-1 month', $endTime)) > date('Y-m-d', $startTime)) {
                    throw new \Exception('按天统计最长时间为一个月');
                }
            } else if ($params['show_type'] == 2) {
                if (date('Y-m-d', strtotime('-2 years', $endTime)) > date('Y-m-d', $startTime)) {
                    throw new \Exception('按天统计最长时间为2年');
                }
            }
            $list = $this->statisticsService->station($params);
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $list = [];
        }

        return $this->render('station', [
            'params' => $params,
            'chartTitle' => $this->getStationChartTitle($params),
            'errorMsg' => $errorMsg,
            'list' => $list,
        ]);
    }

    protected function getStationChartTitle($params)
    {

        $showTypeList = [
            1 => '每日',
            2 => '每月',
            3 => '每季',
        ];
        $stationTypeList = [
            1 => '服务站点',
            2 => '公交站点'
        ];
        return $stationTypeList[$params['station_type']] . $showTypeList[$params['show_type']] . '统计';
    }

    public function actionBusLine()
    {
        $params['show_type'] = \Yii::$app->request->get('show_type', 1);
        $params['area'] = \Yii::$app->request->get('area', '');
        $params['start_time'] = \Yii::$app->request->get('start_time', date('Y-m-d', strtotime('-1 month')));
        $params['end_time'] = \Yii::$app->request->get('end_time', date('Y-m-d', strtotime('-1 day')));
        $errorMsg = '';
        try {
            if (!isset($params['start_time'])) {
                throw new \Exception('请选择统计开始时间');
            }
            if (!isset($params['end_time'])) {
                throw new \Exception('请选择统计开始时间');
            }
            $startTime = strtotime($params['start_time']);
            $endTime = strtotime($params['end_time']) + 24 * 3600 - 1;
            if (!$startTime || !$endTime) {
                throw new \Exception('起止时间格式有误');
            }
            if ($startTime > $endTime) {
                throw new \Exception('开始时间不能大于结束时间');
            }
            if ($params['show_type'] == 1) {
                if (date('Y-m-d', strtotime('-1 month', $endTime)) > date('Y-m-d', $startTime)) {
                    throw new \Exception('按天统计最长时间为一个月');
                }
            } else if ($params['show_type'] == 2) {
                if (date('Y-m-d', strtotime('-2 years', $endTime)) > date('Y-m-d', $startTime)) {
                    throw new \Exception('按天统计最长时间为2年');
                }
            }
            $list = $this->statisticsService->busLine($params);
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $list = [];
        }
        return $this->render('bus-line', [
            'params' => $params,
            'chartTitle' => $this->getBusLineChartTitle($params),
            'errorMsg' => $errorMsg,
            'list' => $list,
        ]);
    }

    protected function getBusLineChartTitle($params)
    {
        $showTypeList = [
            1 => '每日',
            2 => '每月',
            3 => '每季',
        ];
        return '公交线路' . $showTypeList[$params['show_type']] . '统计';

    }
}
