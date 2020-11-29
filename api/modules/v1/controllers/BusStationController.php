<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\BusStation;
use common\services\MapService;

class BusStationController extends BaseController
{
    public $modelClass = 'common\models\BusStation';

    public function actionNear()
    {
        try {
            $mapService = new MapService();
            $point = \Yii::$app->request->get('point');
            if (!$point) {
                throw new \Exception('当前定位有误');
            }
            $arr = explode(',', $point);
            $limit = $this->getLimit();
            $sql = 'SELECT * FROM `bus_station`
 ORDER BY (substring_index(up_point,\',\',1) - ' . $arr[0] . ')*(substring_index(up_point,\',\',1)  - ' . $arr[0] . ')+(substring_index(up_point,\',\',-1)  - ' . $arr[1] . ')*(substring_index(up_point,\',\',-1)  - ' . $arr[1] . ')
 limit ' . implode(',', $limit);
            $list = BusStation::findBySql($sql)
                ->asArray()
                ->all();
            $res = [];
            foreach ($list as $v) {
                $stationPoint = $v['up_point'];
                $res[] = [
                    'id' => $v['id'],
                    'station_name' => $v['station_name'],
                    'distance' => $mapService->getDistanceByPoint($point, $stationPoint),
                    'point' => $stationPoint
                ];
            }
            $this->success($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
