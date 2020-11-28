<?php

namespace common\services;

use common\models\Bus;
use common\models\BusLine;
use common\models\BusOrder;
use common\models\BusStation;
use common\models\Order;
use common\models\PackageOrder;
use common\models\ServiceStation;

class StatisticsService extends BaseService
{
    /**
     * @param $data
     * @return array|\yii\db\ActiveRecord[]
     * @throws \Exception
     */
    public function busOrder($data)
    {
        $selector = BusOrder::find();
        if (isset($data['bus_card']) && $data['bus_card'] != '') {
            $selector->andWhere(['bus_card' => $data['bus_card']]);
        }
        if (isset($data['status']) && $data['status'] != '') {
            $selector->andWhere(['status' => $data['status']]);
        } else {
            $selector->andWhere(['!=', 'status', '0']);
        }
        if (isset($data['start_time']) && $data['start_time'] != '') {
            $data['start_time'] = strtotime($data['start_time']);
            $selector->andWhere(['>=', 'create_time', $data['start_time']]);
        }
        if (isset($data['end_time']) && $data['end_time'] != '') {
            $data['end_time'] = strtotime($data['end_time']);
            $selector->andWhere(['<', 'create_time', $data['end_time'] + 24 * 3600]);
        }
        switch ($data['type']) {
            case '1'://金额
                $select = ['sum(money) as values'];
                break;
            case '2'://佣金
                $select = ['sum(commission) as values'];
                break;
            case '3'://数量
                $select = ['count(*) as values'];
                break;
            default:
                throw new \Exception('参数有误');
        }
        switch ($data['show_type']) {
            case '1'://天
                $select[] = 'from_unixtime(create_time,\'%Y-%m-%d\') as keys';
                $group = ['from_unixtime(create_time,\'%Y-%m-%d\')'];
                break;
            case '2'://月
                $select[] = 'from_unixtime(create_time,\'%Y-%m\') as keys';
                $group = ['from_unixtime(create_time,\'%Y-%m\')'];
                break;
            case '3'://季
                $select[] = 'concat(from_unixtime(create_time,\'%Y\'),\'年第\',QUARTER(from_unixtime(create_time,\'%Y-%m-%d\')),\'季度\') as keys';
                $group = ['from_unixtime(create_time,\'%Y\')', 'QUARTER(from_unixtime(create_time,\'%Y-%m-%d\'))'];
                break;
            default:
                throw new \Exception('参数有误');
        }
        $list = $selector->select($select)->groupBy($group)->asArray()->all();
        $list = array_column($list, 'values', 'keys');

        return $list;
    }

    public function packageOrder($data)
    {
        $selector = Order::find();
        if (isset($data['card']) && $data['card'] != '') {
            $selector->andWhere(['card' => $data['card']]);
        }
        if (isset($data['status']) && $data['status'] != '') {
            $selector->andWhere(['status' => $data['status']]);
        } else {
            $selector->andWhere(['!=', 'status', '0']);
        }
        if (isset($data['start_time']) && $data['start_time'] != '') {
            $data['start_time'] = strtotime($data['start_time']);
            $selector->andWhere(['>=', 'create_time', $data['start_time']]);
        }
        if (isset($data['end_time']) && $data['end_time'] != '') {
            $data['end_time'] = strtotime($data['end_time']);
            $selector->andWhere(['<', 'create_time', $data['end_time'] + 24 * 3600]);
        }
        switch ($data['type']) {
            case '1'://金额
                $select = ['sum(total_account) as values'];
                break;
            case '2'://佣金
                $select = ['sum(station_yongjin) as values'];
                break;
            case '3'://数量
                $select = ['count(*) as values'];
                break;
            default:
                throw new \Exception('参数有误');
        }
        switch ($data['show_type']) {
            case '1'://天
                $select[] = 'from_unixtime(create_time,\'%Y-%m-%d\') as keys';
                $group = ['from_unixtime(create_time,\'%Y-%m-%d\')'];
                break;
            case '2'://月
                $select[] = 'from_unixtime(create_time,\'%Y-%m\') as keys';
                $group = ['from_unixtime(create_time,\'%Y-%m\')'];
                break;
            case '3'://季
                $select[] = 'concat(from_unixtime(create_time,\'%Y\'),\'年第\',QUARTER(from_unixtime(create_time,\'%Y-%m-%d\')),\'季度\') as keys';
                $group = ['from_unixtime(create_time,\'%Y\')', 'QUARTER(from_unixtime(create_time,\'%Y-%m-%d\'))'];
                break;
            default:
                throw new \Exception('参数有误');
        }
        $list = $selector->select($select)->groupBy($group)->asArray()->all();
        $list = array_column($list, 'values', 'keys');

        return $list;
    }

    /**
     * @param $data
     * @return array|\yii\db\ActiveRecord[]
     * @throws \Exception
     */
    public function car($data)
    {
        $selector = Bus::find();
        if (isset($data['car_type']) && $data['car_type'] != '') {
            $selector->andWhere(['car_type' => $data['car_type']]);
        }
        if (isset($data['brand']) && $data['brand'] != '') {
            $selector->andWhere(['like', 'brand', '"%' . $data['brand'] . '%"']);
        }
        if (isset($data['model']) && $data['model'] != '') {
            $selector->andWhere(['like', 'model', '"%' . $data['model'] . '%"']);
        }
        if (isset($data['start_time']) && $data['start_time'] != '') {
            $data['start_time'] = strtotime($data['start_time']);
            $selector->andWhere(['>=', 'created_at', date('Y-m-d 00:00:00', $data['start_time'])]);
        }
        if (isset($data['end_time']) && $data['end_time'] != '') {
            $data['end_time'] = strtotime($data['end_time']);
            $selector->andWhere(['<=', 'created_at', date('Y-m-d 23:59:59', $data['end_time'])]);
        }
        $select = ['count(*) as value'];
        if ($data['chart_type'] == 'default') {
            switch ($data['show_type']) {
                case '1'://天
                    $select[] = 'date(created_at) as name';
                    $group = ['date(created_at)'];
                    break;
                case '2'://月
                    $select[] = 'DATE_FORMAT(created_at,\'%Y-%m\') as name';
                    $group = ['DATE_FORMAT(created_at,\'%Y-%m\')'];
                    break;
                case '3'://季
                    $select[] = 'concat(DATE_FORMAT(created_at,\'%Y\'),\'年第\',QUARTER(date(created_at)),\'季度\') as name';
                    $group = ['DATE_FORMAT(created_at,\'%Y\')', 'QUARTER(date(created_at))'];
                    break;
                default:
                    throw new \Exception('参数有误');
            }
        } else {
            $select[] = $data['chart_type'] . ' as name';
            $group = $data['chart_type'];
        }
        $list = $selector->select($select)->groupBy($group)->asArray()->all();
        if ($data['chart_type'] == 'car_type') {
            foreach ($list as $k => $v) {
                $list[$k]['name'] = \Yii::$app->params['car_type_list'][$v['name']];
            }
        }
        return $list;
    }

    /**
     * @param $data
     * @return array|\yii\db\ActiveRecord[]
     * @throws \Exception
     */
    public function station($data)
    {
        return $data['station_type'] == 1 ? $this->serviceStation($data) : $this->busStation($data);
    }

    /**
     * @param $data
     * @return array|\yii\db\ActiveRecord[]
     * @throws \Exception
     */
    protected function busStation($data)
    {
        $selector = BusStation::find();
        if (isset($data['start_time']) && $data['start_time'] != '') {
            $data['start_time'] = strtotime($data['start_time']);
            $selector->andWhere(['>=', 'created_at', date('Y-m-d 00:00:00', $data['start_time'])]);
        }
        if (isset($data['end_time']) && $data['end_time'] != '') {
            $data['end_time'] = strtotime($data['end_time']);
            $selector->andWhere(['<=', 'created_at', date('Y-m-d 23:59:59', $data['end_time'])]);
        }
        $select = ['count(*) as value'];
        switch ($data['show_type']) {
            case '1'://天
                $select[] = 'date(created_at) as name';
                $group = ['date(created_at)'];
                break;
            case '2'://月
                $select[] = 'DATE_FORMAT(created_at,\'%Y-%m\') as name';
                $group = ['DATE_FORMAT(created_at,\'%Y-%m\')'];
                break;
            case '3'://季
                $select[] = 'concat(DATE_FORMAT(created_at,\'%Y\'),\'年第\',QUARTER(date(created_at)),\'季度\') as name';
                $group = ['DATE_FORMAT(created_at,\'%Y\')', 'QUARTER(date(created_at))'];
                break;
            default:
                throw new \Exception('参数有误');
        }
        $list = $selector->select($select)->groupBy($group)->asArray()->all();
        return $list;
    }

    /**
     * @param $data
     * @return array|\yii\db\ActiveRecord[]
     * @throws \Exception
     */
    protected function serviceStation($data)
    {
        $selector = ServiceStation::find();
        if (isset($data['country']) && $data['country'] != '') {
            $selector->andWhere(['country' => $data['country']]);
        }
        if (isset($data['start_time']) && $data['start_time'] != '') {
            $data['start_time'] = strtotime($data['start_time']);
            $selector->andWhere(['>=', 'create_time', $data['start_time']]);
        }
        if (isset($data['end_time']) && $data['end_time'] != '') {
            $data['end_time'] = strtotime($data['end_time']);
            $selector->andWhere(['<', 'create_time', $data['end_time'] + 24 * 3600]);
        }
        $select = ['count(*) as value'];
        switch ($data['show_type']) {
            case '1'://天
                $select[] = 'from_unixtime(create_time,\'%Y-%m-%d\') as name';
                $group = ['from_unixtime(create_time,\'%Y-%m-%d\')'];
                break;
            case '2'://月
                $select[] = 'from_unixtime(create_time,\'%Y-%m\') as name';
                $group = ['from_unixtime(create_time,\'%Y-%m\')'];
                break;
            case '3'://季
                $select[] = 'concat(from_unixtime(create_time,\'%Y\'),\'年第\',QUARTER(from_unixtime(create_time,\'%Y-%m-%d\')),\'季度\') as name';
                $group = ['from_unixtime(create_time,\'%Y\')', 'QUARTER(from_unixtime(create_time,\'%Y-%m-%d\'))'];
                break;
            default:
                throw new \Exception('参数有误');
        }
        $list = $selector->select($select)->groupBy($group)->asArray()->all();
        return $list;
    }

    public function busLine($data)
    {
        $selector = BusLine::find();
        if (isset($data['area']) && $data['area'] != '') {
            $selector->andWhere(['area' => $data['area']]);
        }
        if (isset($data['start_time']) && $data['start_time'] != '') {
            $data['start_time'] = strtotime($data['start_time']);
            $selector->andWhere(['>=', 'created_at', date('Y-m-d 00:00:00', $data['start_time'])]);
        }
        if (isset($data['end_time']) && $data['end_time'] != '') {
            $data['end_time'] = strtotime($data['end_time']);
            $selector->andWhere(['<=', 'created_at', date('Y-m-d 23:59:59', $data['end_time'])]);
        }
        $select = ['count(*) as value'];
        switch ($data['show_type']) {
            case '1'://天
                $select[] = 'date(created_at) as name';
                $group = ['date(created_at)'];
                break;
            case '2'://月
                $select[] = 'DATE_FORMAT(created_at,\'%Y-%m\') as name';
                $group = ['DATE_FORMAT(created_at,\'%Y-%m\')'];
                break;
            case '3'://季
                $select[] = 'concat(DATE_FORMAT(created_at,\'%Y\'),\'年第\',QUARTER(date(created_at)),\'季度\') as name';
                $group = ['DATE_FORMAT(created_at,\'%Y\')', 'QUARTER(date(created_at))'];
                break;
            default:
                throw new \Exception('参数有误');
        }

        $list = $selector->select($select)->groupBy($group)->asArray()->all();
        return $list;
    }
}