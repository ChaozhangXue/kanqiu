<?php

namespace api\modules\v1\controllers;

use common\models\BusStation;
use common\models\StationAllocation;
use common\services\MapService;
use Yii;
use api\controllers\BaseController;
use common\models\BusLine;

class ServiceStationController extends BaseController
{
    public $modelClass = 'common\models\ServiceStation';

    public function actionSearch(){
        $word = Yii::$app->request->get('word');
        if(!empty($word)){
            $bus_line = BusLine::find()->where(['like', 'station_name', $word])->asArray()->all();
            if(!empty($bus_line)){
                $this->success($bus_line);
            }
        }

        $this->success();
    }

    public function actionBusLine()
    {
        $station_name = Yii::$app->request->get('name');
        $point = Yii::$app->request->get('point');
        $station_data = BusStation::find()->where(['station_name' => $station_name])->all();

        $ret = [];
        if(empty($station_data)){
            $this->success();
        }else{
            foreach ($station_data as $station){
//                $bus_station = BusStation::findOne($station->bus_id);

                $bus_line = BusLine::find()
                    ->where(" FIND_IN_SET(" . $station->id . ",station_list)")
                    ->asArray()
                    ->all();

                $distance = 0;
                if(!empty($point)){
                    $distance = $this->getDistance(explode(',', $point), explode(',', $station->up_point));
                }
                $ret[] = ['line' => $bus_line, 'station_info' => ['station' => $station->toArray(), 'distance' => $distance]];
            }
        }

        $this->success($ret);


//        $station_name = Yii::$app->request->get('name');
//        $point = Yii::$app->request->get('point');
//        $station_data = StationAllocation::find()->where(['like', 'service_name', $station_name])->all();
//
//        $ret = [];
//        if(empty($station_data)){
//            $this->success();
//        }else{
//            foreach ($station_data as $station){
//                $bus_station = BusStation::findOne($station->bus_id);
//
//                $bus_line = BusLine::find()
//                    ->where(" FIND_IN_SET(" . $station->bus_id . ",station_list)")
//                    ->asArray()
//                    ->all();
//
//                $distance = 0;
//                if(!empty($point)){
//                    $distance = $this->getDistance(explode(',', $point), explode(',', $bus_station->up_point));
//                }
//                $ret[] = ['line' => $bus_line, 'station_info' => ['station' => $bus_station->toArray(), 'distance' => $distance]];
//            }
//        }
//
//        $this->success($ret);
    }

    public function actionBusLineLink()
    {
        $word = Yii::$app->request->get('word');
        if(empty($word)){
            $this->success();
        }
        $station = BusStation::find()->where(['like', 'station_name', $word])->asArray()->all();

        if(empty($station)){
            $this->success();
        }

        $this->success(array_column($station, 'station_name'));




//        $word = Yii::$app->request->get('word');
//        if(empty($word)){
//            $this->success();
//        }
//        $station = StationAllocation::find()->where(['like', 'service_name', $word])->asArray()->all();
//
//        if(empty($station)){
//            $this->success();
//        }
//
//        $this->success(array_column($station, 'service_name'));
    }

    /**
     * 获取当前用户最近的服务站
     */
    public function actionGetNearStation(){
        $point = Yii::$app->request->get('point');

        $mapService = new MapService();
        $ret = $mapService->getNear($point);

        $this->success($ret);
    }
}
