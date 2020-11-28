<?php

namespace common\services;


use common\models\CustomerBill;
use common\models\DriverAccount;
use common\models\DriverBill;
use common\models\ServiceStation;
use common\models\StationAccount;
use common\models\StationBill;

class BillService extends BaseService
{
    /**
     * 保存司机账单
     * @param $billType 1
     * @param array $orders 依据type区分是单条记录还是数组
     * @param $type
     * @throws \Exception
     */
    public function saveDriverBill($billType, $orders, $type = 'single')
    {
        if ($type == 'single') {
            $orders = [$orders];
        }
        $billList = [];
        $commissions = [];
        $insertKeys = [];
        foreach ($orders as $order) {
            $bill = [];
            $bill['bill_type'] = $billType;
            //客运订单
            if (in_array($billType, [1, 3])) {
                if ($order['status'] != 6) {
                    throw new \Exception('订单状态有误');
                }
                $bill['order_id'] = $order['id'];
                $bill['order_no'] = $order['order_no'];
                $bill['driver_id'] = $order['driver_id'];
                $bill['driver_name'] = $order['driver_name'];
                $bill['customer_id'] = $order['customer_id'];
                $bill['pay_method'] = $order['pay_method'];
                $bill['transaction_id'] = $order['transaction_id'];
                $bill['amount'] = $order['money'];
                $bill['commission'] = $order['commission'];
                $bill['order_time'] = $order['create_time'];
                $bill['pay_time'] = $order['pay_time'];
                $bill['create_time'] = $bill['update_time'] = time();
                if (!isset($commissions[$order['driver_id']])) {
                    $commissions[$order['driver_id']] = 0;
                }
                $commissions[$order['driver_id']] += $bill['commission'];
            } else if ($billType == 2) {
                //todo 包裹订单  需要bill   然后还要添加station
                $bill['order_id'] = $order['id'];
                $bill['order_no'] = $order['id'];
                $bill['driver_id'] = $order['driver_id'];
                $bill['driver_name'] = $order['driver_name'];
                $bill['customer_id'] = 0;
                $bill['pay_method'] = 2;//todo 现在是写死的微信支付
                $bill['transaction_id'] = '';
                $bill['amount'] = $order['total_account'];
                $bill['commission'] = $order['yongjin'];
                $bill['order_time'] = strtotime($order['created_at']);
                $bill['pay_time'] = strtotime($order['created_at']);
                $bill['create_time'] = $bill['update_time'] = time();
                if (!isset($commissions[$order['driver_id']])) {
                    $commissions[$order['driver_id']] = 0;
                }
                $commissions[$order['driver_id']] += $bill['commission'];
            } else {
                throw new \Exception('账单类型有误');
            }
            $billList[] = $bill;
            $insertKeys = array_keys($bill);
        }
        \Yii::$app->db->createCommand()
            ->batchInsert(DriverBill::tableName(), $insertKeys, $billList)
            ->execute();

        //增加司机余额
        foreach ($commissions as $driverId => $commission) {
            $this->increaseDriverAccount($driverId, $commission);
        }
    }

    /**
     * 保存站点订单
     * @param $orders
     * @param string $type
     * @throws \Exception
     */
    public function saveStationBill($orders, $type = 'single')
    {
        if ($type == 'single') {
            $orders = [$orders];
        }
        $billList = [];
        $commissions = [];
        $insertKeys = [];
        foreach ($orders as $order) {
            $bill = [];
            $bill['order_id'] = $order['id'];
            $bill['station_id'] = ($order['type'] == 1)? $order['receive_station_id']:$order['send_station_id'];
            $bill['station_name'] = ($order['type'] == 1)? $order['receive_station_name']:$order['send_station_name'];

            if(!empty($bill['station_id'])){
                $station_data = ServiceStation::find()->where(['id' => $bill['station_id']])->one();
                $bill['owner'] = $station_data->in_charge_name;
            }

            $bill['order_type'] = $order['type'];
            $bill['time'] = $order['created_at'];
            $bill['money'] = $order['total_account'];
            $bill['yongjin'] = $order['station_yongjin'];
            $bill['package_num'] = $order['package_num'];
            $bill['status'] = 0;
            $bill['bill_time'] =date('Y-m-d');

            if (!isset($commissions[$bill['station_id']])) {
                $commissions[$bill['station_id']] = 0;
            }
            $commissions[$bill['station_id']] += $bill['yongjin'];

            $billList[] = $bill;
            $insertKeys = array_keys($bill);
        }
        \Yii::$app->db->createCommand()
            ->batchInsert(StationBill::tableName(), $insertKeys, $billList)
            ->execute();
        //增加司机余额
        foreach ($commissions as $station_id => $commission) {
            $this->increaseStationAccount($station_id, $commission);
        }
    }


    /**
     * 增加用户余额
     * @param $station_id
     * @param $money
     */
    public function increaseStationAccount($station_id, $money)
    {
        $stationAccount = StationAccount::find()->where(['station_id' => $station_id])->one();
        if (!$stationAccount) {
            $stationAccount = new StationAccount();
            $stationAccount['station_id'] = $station_id;
            $stationAccount['balance'] = 0;
            $stationAccount['total_income'] = 0;
        }
        $stationAccount['balance'] += $money;
        $stationAccount['total_income'] += $money;
        $stationAccount->save();
    }


    /**
     * 增加用户余额
     * @param $driver_id
     * @param $money
     */
    public function increaseDriverAccount($driver_id, $money)
    {
        $driverAccount = DriverAccount::find()->where(['driver_id' => $driver_id])->one();
        if (!$driverAccount) {
            $driverAccount = new DriverAccount();
            $driverAccount['driver_id'] = $driver_id;
            $driverAccount['balance'] = 0;
            $driverAccount['total_income'] = 0;
        }
        $driverAccount['balance'] += $money;
        $driverAccount['total_income'] += $money;
        $driverAccount->save();
    }

    /**
     * 减少用户余额
     * @param $driver_id
     * @param $money
     */
    public function decreaseDriverAccount($driver_id, $money)
    {
        $driverAccount = DriverAccount::find()->where(['driver_id' => $driver_id])->one();
        if (!$driverAccount) {
            $driverAccount = new DriverAccount();
            $driverAccount['driver_id'] = $driver_id;
            $driverAccount['balance'] = 0;
        }
        $driverAccount['balance'] -= $money;
        if ($driverAccount['balance'] < 0) {
            throw new \Exception('司机余额不足');
        }
        $driverAccount->save();
    }

    public function decreaseStationAccount($station_id, $money)
    {
        $stationAccount = StationAccount::find()->where(['station_id' => $station_id])->one();
        if (!$stationAccount) {
            $stationAccount = new StationAccount();
            $stationAccount['station_id'] = $station_id;
            $stationAccount['balance'] = 0;
        }
        $stationAccount['balance'] -= $money;
        if ($stationAccount['balance'] < 0) {
            throw new \Exception('站点余额不足');
        }
        $stationAccount->save();
    }


    /**
     * 保存司机账单
     * @param $billType 1支付 2退款
     * @param array $orders 依据type区分是单条记录还是数组
     * @param $type
     * @throws \Exception
     */
    public function saveCustomerBill($order, $orderType, $billType = 1)
    {
        $bill = new CustomerBill();
        //客运订单
        if ($orderType == 2) {
            $bill['bill_type'] = $billType;
            $bill['order_type'] = $orderType;
            $bill['type'] = $order['order_type'];
            $bill['order_id'] = $order['id'];
            $bill['order_no'] = $order['order_no'];
            $bill['customer_id'] = $order['customer_id'];
            $bill['pay_method'] = $order['pay_method'];
            $bill['transaction_id'] = $order['transaction_id'];
            $bill['amount'] = $order['money'];
            $bill['pay_money'] = $order['pay_money'];
            $bill['order_time'] = $order['create_time'];
            $bill['pay_time'] = $order['pay_time'];

        } else if ($orderType == 1) {
            $bill['bill_type'] = $billType;
            $bill['order_type'] = $orderType;
            $bill['type'] = 0;
            $bill['order_id'] = $order['id'];
            $bill['order_no'] = $order['package_list_id'];
            $bill['customer_id'] = $order['customer_id'];
            $bill['pay_method'] = 2;
            $bill['transaction_id'] = $order['transaction_id'];
            $bill['amount'] = $order['total_account'];
            $bill['pay_money'] = $order['total_account'];
            $bill['order_time'] = $order['created_at'];
            $bill['pay_time'] = time();
        } else {
            throw new \Exception('账单类型有误');
        }
        $ret = $bill->save();
    }

}