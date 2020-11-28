<?php

namespace common\services;

use common\models\BusOrder;
use common\models\Customer;
use common\models\PackageOrder;

class PayService extends BaseService
{
    /** @var BusOrderService $busOrderService */
    public $busOrderService;
    /** @var BillService $billService */
    public $billService;

    public function __construct()
    {
        $this->busOrderService = new BusOrderService();
        $this->billService = new BillService();
    }

    /**
     * 统一支付接口
     * data数组需要信息
     *  out_trade_no 支付单号
     *  title 标题
     *  money 金额
     *  type 类型，回调值通过attach判断订单类型
     * @return mixed 小程序需要返回值调起支付
     * @throws \Exception
     */
    public function createPreOrder($title, $out_trade_no, $money, $type)
    {
        if ($money == 0) {
            return [];
        }
        if (YII_DEBUG) {
            $money = 0.01;
        }
        /** @var Customer $customer */
        $customer = \Yii::$app->user->identity;
        //创建预售单
        $appInfo = \Yii::$app->params['wechat'];
        // $fee = 0.01;//举例充值0.01
        $body = $title;// 商品的详情，比如iPhone8，紫色
        $nonce_str = md5($appInfo['app_id'] . time() . rand(10000, 99999));//随机字符串
        $notify_url = \Yii::$app->params['web_info']['host'] . '/v1/pay/notify';//回调的url【自己填写】';
        $total_fee = (int)($money * 100);//因为充值金额最小是1 而且单位为分 如果是充值1元所以这里需要*100
        $trade_type = 'JSAPI';//交易类型 默认
        //这里是按照顺序的 因为下面的签名是按照顺序 排序错误 肯定出错
        $post = [];
        $post['appid'] = $appInfo['app_id'];
        $post['attach'] = json_encode(['type' => $type]);
        $post['body'] = $body;
        $post['mch_id'] = $appInfo['mch_id'];//你的商户号
        $post['nonce_str'] = $nonce_str;//随机字符串
        $post['notify_url'] = $notify_url;//回调的url
        $post['openid'] = $customer['openid'];
        $post['out_trade_no'] = $out_trade_no;//商户订单号
        $post['spbill_create_ip'] = \Yii::$app->params['web_info']['ip'];//终端的ip
        $post['total_fee'] = $total_fee;//总金额 最低为一块钱 必须是整数
        $post['trade_type'] = $trade_type;
        $sign = $this->sign($post, $appInfo['pay_key']);//签名
        $post_xml = '<xml>
         <appid>' . $appInfo['app_id'] . '</appid>
         <attach>' . $post['attach'] . '</attach>
         <body>' . $body . '</body>
         <mch_id>' . $appInfo['mch_id'] . '</mch_id>
         <nonce_str>' . $nonce_str . '</nonce_str>
         <notify_url>' . $notify_url . '</notify_url>
         <openid>' . $customer['openid'] . '</openid>
         <out_trade_no>' . $out_trade_no . '</out_trade_no>
         <spbill_create_ip>' . \Yii::$app->params['web_info']['ip'] . '</spbill_create_ip>
         <total_fee>' . $total_fee . '</total_fee>
         <trade_type>' . $trade_type . '</trade_type>
         <sign>' . $sign . '</sign>
         </xml>';
        //统一接口prepay_id
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $xml = $this->sendRequest($url, $post_xml);
        /** @var array $res */
        $res = $this->xml2array($xml);
        if (!$res) {
            throw new \Exception('支付返回信息有误');
        }
        $res = $res['xml'];
        if ($res['return_code'] == 'SUCCESS') {
            $time = time();
            $tmp = [];//临时数组用于签名
            $tmp['appId'] = $appInfo['app_id'];
            $tmp['nonceStr'] = $nonce_str;
            $tmp['package'] = 'prepay_id=' . $res['prepay_id'];
            $tmp['signType'] = 'MD5';
            $tmp['timeStamp'] = "$time";
            $ret['timeStamp'] = "$time";//时间戳
            $ret['nonceStr'] = $nonce_str;//随机字符串
            $ret['signType'] = 'MD5';//签名算法，暂支持 MD5
            $ret['package'] = 'prepay_id=' . $res['prepay_id'];//统一下单接口返回的 prepay_id 参数值，提交格式如：prepay_id=*
            $ret['paySign'] = $this->sign($tmp, $appInfo['pay_key']);//签名,具体签名方案参见微信公众号支付帮助文档;
            $ret['out_trade_no'] = $out_trade_no;
            return $ret;
        } else {
            throw new \Exception($res['return_msg']);
        }
    }

    /**
     * 微信支付回调
     * @param $xml
     * @throws \Exception
     */
    public function notify($xml)
    {
        $res = $this->xml2array($xml);
        if (!$res) {
            throw new \Exception('交易失败');
        }
        $res = $res['xml'];
        if (!isset($res['return_code']) || $res['return_code'] != 'SUCCESS') {
            throw new \Exception('交易失败');
        } else if (!isset($res['result_code']) || $res['result_code'] != 'SUCCESS') {
            throw new \Exception('交易失败');
        }
        $attach = isset($res['attach']) && $res['attach'] ? json_decode($res['attach'], true) : [];
        if (!isset($attach['type'])) {
            throw new \Exception('交易失败');
        }
        $sign = $res['sign'];
        unset($res['sign']);
        $newSign = $this->sign($res, \Yii::$app->params['wechat']['pay_key']);
        if ($sign != $newSign) {
            throw new \Exception('签名失败');
        }
        if ($attach['type'] == 'bus-order') {
            $order = BusOrder::find()->where(['pay_order_no' => $res['out_trade_no']])->one();
            if ($order['status'] != 1) {
                throw new \Exception('交易失败');
            }
            $order['pay_time'] = time();
            $order['status'] = 2;
            $order['transaction_id'] = $res['transaction_id'];
            $order['pay_method'] = 1;
            $order['pay_money'] = $res['total_fee'] / 100;
            if (!$order->save()) {
                throw new \Exception(array_values($order->firstErrors)[0]);
            }
            $this->busOrderService->trace($order['id'], '会员付款，付款金额' . $order['pay_money']);
            $this->billService->saveCustomerBill($order, 2);
        } elseif ($attach['type'] = 'package-order') {
            $order = PackageOrder::find()->where(['pay_order_no' => $res['out_trade_no']])->one();
            $order->status = \Yii::$app->params['package_status']['wait'];
            $order['transaction_id'] = $res['transaction_id'];
            $order['pay_time'] = time();
            $order['pay_method'] = 1;
            $order['pay_money'] = $res['total_fee'] / 100;

            if (!$order->save()) {
                throw new \Exception(array_values($order->firstErrors)[0]);
            }
        }
        //todo 其他订单回调
    }

    /**
     * 退款
     * @param $order
     * @param $orderType 1包裹订单 2用车订单
     * @throws \Exception
     */
    public function refund($order, $orderType = 1)
    {
//        if (!\Yii::$app->params['refund']) {
//            return;
//        }
        $appInfo = \Yii::$app->params['wechat'];
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        if ($orderType == 2) {
            $data = [
                'appid' => $appInfo['app_id'],
                'mch_id' => $appInfo['mch_id'],
                'nonce_str' => md5($appInfo['app_id'] . time() . rand(10000, 99999)),
                'transaction_id' => $order['transaction_id'],
                'out_refund_no' => str_replace('BO', 'TK', $order['order_no']),
                'total_fee' => (int)($order['pay_money'] * 100),
                'refund_fee' => (int)($order['pay_money'] * 100),
            ];
            $sign = $this->sign($data, $appInfo['pay_key']);//签名
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
        } else {
            //todo 包裹订单数据组装
            $data = [
                'appid' => $appInfo['app_id'],
                'mch_id' => $appInfo['mch_id'],
                'nonce_str' => md5($appInfo['app_id'] . time() . rand(10000, 99999)),
                'transaction_id' => $order['transaction_id'],
                'out_refund_no' => str_replace('BO', 'TK', $order['pay_order_no']),
                'total_fee' => (int)($order['pay_money'] * 100),
                'refund_fee' => (int)($order['pay_money'] * 100),
            ];
            $sign = $this->sign($data, $appInfo['pay_key']);//签名
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
        }
        $pemList = [
            'key' => dirname(\Yii::$app->basePath) . '/common/cert/pay_key.pem',
            'cert' => dirname(\Yii::$app->basePath) . '/common/cert/pay_cert.pem',
        ];
        $xml = $this->sendRequest($url, $post_xml, 'POST', [], $pemList);
        $res = $this->xml2array($xml);
        if (!$res) {
            throw new \Exception('退款返回信息有误');
        }
        $res = $res['xml'];
        if ($res['return_code'] != 'SUCCESS') {
            throw new \Exception($res['return_msg']);
        }
        $this->billService->saveCustomerBill($order, $orderType, 2);
    }

    public function sign($data, $wx_key)
    {
        ksort($data);
        $stringA = '';
        foreach ($data as $key => $value) {
            if (!$value) continue;
            if ($stringA) $stringA .= '&' . $key . "=" . $value;
            else $stringA = $key . "=" . $value;
        }
        //申请支付后有给予一个商户账号和密码，登陆后自己设置key
        $stringSignTemp = $stringA . '&key=' . $wx_key;//申请支付后有给予一个商户账号和密码，登陆后自己设置key
        return strtoupper(md5($stringSignTemp));
    }

    /**
     * 生成交易订单号
     */
    public function generateTradeNo($prefix = 'TD')
    {
        return $prefix . date('YmdHis') . str_pad(rand(000000, 999999), 6, '0', STR_PAD_LEFT);
    }
}