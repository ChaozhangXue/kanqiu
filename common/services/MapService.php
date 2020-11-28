<?php

namespace common\services;

use common\models\ServiceStation;

class MapService extends BaseService
{
    /*
     * 传入一个地址  找到最近的几个的服务站点
     */
    public function getNear($location)
    {
        if (empty($location)) {
            return false;
        }
        list($longitude, $latitude) = explode(',', $location);

        $station = $this->getNearStations($longitude, $latitude);
        return $station;
    }

    /*
     * 传入一个地址  找到对应的服务站点
     */
    public function getPosition($location)
    {
        if (empty($location)) {
            return false;
        }
        list($longitude, $latitude) = explode(',', $location);

        $station = $this->getNearestStation($longitude, $latitude);

        return [
            'latitude' => $longitude,
            'gratitude' => $latitude,
            'nearest_station_id' => isset($station['id']) ? $station['id'] : 0,
            'nearest_station_name' => isset($station['name']) ? $station['name'] : 0,
        ];
    }

    /**
     * 获取最近的的几个服务站点
     * @param $longitude
     * @param $latitude
     * @return array
     */
    public function getNearStations($longitude, $latitude)
    {
        $station_ary = [];
//        $station_name = '';

        //获取所有的站点信息
        $station = ServiceStation::find()->where('id != 0')->indexBy('id')->asArray()->all();
        foreach ($station as $val) {
            $distance = $this->getDistance([$longitude, $latitude], [$val['longitude'], $val['latitude']]);
            $station_ary[$val['id']] = $distance;

        }
        $station_ary = $this->sort_with_keyName($station_ary, 'asc');

        $station_detail = [];
        foreach ($station_ary as $station_id => $distance) {
            $station_detail[$station_id] = $station[$station_id];
            $station_detail[$station_id]['distance'] = $distance;
        }
        return array_slice($station_detail, 0, 6);
    }

    private function sort_with_keyName($arr, $orderby = 'desc')
    {
        $new_array = array();
        $new_sort = array();
        foreach ($arr as $key => $value) {
            $new_array[] = $value;
        }
        if ($orderby == 'asc') {
            asort($new_array);
        } else {
            arsort($new_array);
        }
        foreach ($new_array as $k => $v) {
            foreach ($arr as $key => $value) {
                if ($v == $value) {
                    $new_sort[$key] = $value;
                    unset($arr[$key]);
                    break;
                }
            }
        }
        return $new_sort;
    }

    /**
     * 获取最近的站点
     * @param $longitude
     * @param $latitude
     * @return array
     */
    public function getNearestStation($longitude, $latitude)
    {
        $station_id = 0;
        $station_name = '';

        //获取所有的站点信息
        $station = ServiceStation::find()->select(['id', 'station_name', 'longitude', 'latitude'])->where('id != 0')->all();
        foreach ($station as $val) {
            if (empty($distance)) {
                $distance = $this->getDistance([$longitude, $latitude], [$val['longitude'], $val['latitude']]);
                $station_id = $val['id'];
                $station_name = $val['station_name'];
            } else {
                $now_distance = $this->getDistance([$longitude, $latitude], [$val['longitude'], $val['latitude']]);

                if ($now_distance < $distance) {
                    $distance = $now_distance;
                    $station_id = $val['id'];
                    $station_name = $val['station_name'];
                }
            }
        }
        return ['id' => $station_id, 'name' => $station_name];
    }

    /**
     * 获取驾车路线长度,不足一公里按一公里算
     * @param $start_point
     * @param $end_point
     * @return int
     * @throws \Exception
     */
    public function getDirection($start_point, $end_point)
    {
        //todo 考虑是否使用缓存机制
        $url = 'https://restapi.amap.com/v3/direction/driving';
        $data = [
            'key' => \Yii::$app->params['map']['app_key'],
            'origin' => $start_point,
            'destination' => $end_point,
            'strategy' => 2,
        ];
        $res = $this->sendRequest($url, $data, 'GET');

        $res = json_decode($res, true);
        if (!$res['status']) {
            return $this->getDistance(explode(',', $start_point), explode(',', $end_point));
        } else {
            return (int)ceil($res['route']['paths'][0]['distance'] / 1000);
        }
    }


    public function getDistanceByPoint($start_point, $end_point)
    {
        $start = explode(',', $start_point);
        $end = explode(',', $end_point);
        $radLat1 = deg2rad($start[1]);//deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($end[1]);
        $radLng1 = deg2rad($start[0]);
        $radLng2 = deg2rad($end[0]);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6371.393 * 1000;
        return $s > 1000 ? round($s / 1000, 1) . 'km' : round($s) . 'm';
    }
}