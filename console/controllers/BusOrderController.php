<?php

namespace console\controllers;

use common\models\BusOrder;
use common\services\BusOrderService;
use common\services\PayService;

class BusOrderController extends BaseController
{

    /**
     * 自动取消订单
     */
    public function actionCancel()
    {
        $lock_file = 'bus-order-cancel.lock';
        $lock_file = \Yii::$app->runtimePath . '/lock/' . $lock_file;
        $path = dirname($lock_file);
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        if (!file_exists($lock_file)) {
            touch($lock_file);
        }
        $lock_file_handle = fopen($lock_file, 'w');
        if ($lock_file_handle === false) {
            die("Can not create lock file $lock_file\n");
        }
        if (!flock($lock_file_handle, LOCK_EX + LOCK_NB)) {
            die(date("Y-m-d H:i:s") . " Process already exists.\n");
        }
        $busOrderService = new BusOrderService();
        $orderList = BusOrder::find()->select(['id'])->where(['status' => BusOrderService::BUS_ORDER_STATUS_PENDING])
            ->andWhere(['<=', 'create_time', time() - 15 * 60])->asArray()->all();
        if (!count($orderList)) {
            return;
        }
        foreach ($orderList as $order) {
            $busOrderService->cancel($order['order_no'], 0, 1);
        }
    }

    /**
     * 订单完成
     * @throws \Exception
     */
    public function actionComplete()
    {
        $lock_file = 'bus-order-complete.lock';
        $lock_file = \Yii::$app->runtimePath . '/lock/' . $lock_file;
        $path = dirname($lock_file);
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        if (!file_exists($lock_file)) {
            touch($lock_file);
        }
        $lock_file_handle = fopen($lock_file, 'w');
        if ($lock_file_handle === false) {
            die("Can not create lock file $lock_file\n");
        }
        if (!flock($lock_file_handle, LOCK_EX + LOCK_NB)) {
            die(date("Y-m-d H:i:s") . " Process already exists.\n");
        }
        $busOrderService = new BusOrderService();
        $orderList = BusOrder::find()
            ->where(['status' => BusOrderService::BUS_ORDER_STATUS_CONFIRM])
            ->andWhere('(order_type=2 and update_time<=' . (time() - 24 * 3600) . ') or (order_type!=2 and end_time<=' . (time() - 24 * 3600) . ')')->asArray()->all();
        if (!count($orderList)) {
            return;
        }
        foreach ($orderList as $order) {
            $busOrderService->complete($order['order_no'], 1);
        }
    }


    public function actionTest()
    {
        $service=new PayService();
//        if (!\Yii::$app->params['refund']) {
//            return;
//        }
        $appInfo = \Yii::$app->params['wechat'];
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $data = [
            'appid' => $appInfo['app_id'],
            'mch_id' => $appInfo['mch_id'],
            'nonce_str' => md5($appInfo['app_id'] . time() . rand(10000, 99999)),
            'transaction_id' => '4200000422201912106102185521',
            'out_refund_no' => str_replace('BO', 'TK', '201911221237566804521'),
            'total_fee' =>1,
            'refund_fee' =>1,
        ];
        $sign = $service->sign($data, $appInfo['pay_key']);//签名
        $post_xml = '<xml>
         <appid>' . $data['appid'] . '</appid>
         <mch_id>' . $data['mch_id'] . '</mch_id>
         <nonce_str>' . $data['nonce_str'] . '</nonce_str>
         <transaction_id>' . $data['transaction_id'] . '</transaction_id>
         <out_refund_no>' . $data['out_refund_no'] . '</out_refund_no>
         <total_fee>' . $data['total_fee'] . '</total_fee>
         <refund_fee>' . $data['refund_fee'] . '</refund_fee>
         <sign>' . $sign . '</sign>
         </xml>';

        $pemList = [
            'key' => dirname(\Yii::$app->basePath) . '/common/cert/pay_key.pem',
            'cert' => dirname(\Yii::$app->basePath) . '/common/cert/pay_cert.pem',
        ];
//        $xml = $service->sendRequest($url, $post_xml, 'POST', [], $pemList);
        $xml='<xml><return_code><![CDATA[SUCCESS]]></return_code>
<return_msg><![CDATA[OK]]></return_msg>
<appid><![CDATA[wxd4886c7ba623dcae]]></appid>
<mch_id><![CDATA[1555283351]]></mch_id>
<nonce_str><![CDATA[l9qupyqnmghEKSNn]]></nonce_str>
<sign><![CDATA[E02916023995D107ECBD38771FB0283A]]></sign>
<result_code><![CDATA[SUCCESS]]></result_code>
<transaction_id><![CDATA[4200000422201912106102185521]]></transaction_id>
<out_trade_no><![CDATA[TD20191210234949995005]]></out_trade_no>
<out_refund_no><![CDATA[201911221237566804521]]></out_refund_no>
<refund_id><![CDATA[50300102802019121113571388969]]></refund_id>
<refund_channel><![CDATA[]]></refund_channel>
<refund_fee>1</refund_fee>
<coupon_refund_fee>0</coupon_refund_fee>
<total_fee>1</total_fee>
<cash_fee>1</cash_fee>
<coupon_refund_count>0</coupon_refund_count>
<cash_refund_fee>1</cash_refund_fee>
</xml>';
        $res = $service->xml2array($xml);
        if (!$res) {
            throw new \Exception('退款返回信息有误');
        }
        $res = $res['xml'];
        if ($res['return_code'] != 'SUCCESS') {
            throw new \Exception($res['return_msg']);
        }
        var_dump($res);die;
    }

}