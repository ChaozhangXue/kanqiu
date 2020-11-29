<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\services\PayService;

class PayController extends BaseController
{
    /** @var PayService $payService */
    public $payService;

    public function init()
    {
        $this->payService = new PayService();
        parent::init();
    }

    /**
     * 微信支付回调
     */
    public function actionNotify()
    {
        try {
            $xml = file_get_contents('php://input');
            file_put_contents('/tmp/123.log', date('Y-m-d H:i:s ') . $xml . PHP_EOL, FILE_APPEND);
            $this->payService->notify($xml);
            $this->sendXml([
                'return_code' => 'SUCCESS',
                'return_msg' => 'OK'
            ]);
        } catch (\Exception $e) {
            $this->sendXml([
                'return_code' => 'FAIL',
                'return_msg' => $e->getMessage()
            ]);
        }
    }

    public function sendXml($ret)
    {
        echo $this->array2xml($ret);
        exit;
    }

    public function array2xml($data)
    {
        if (!is_array($data) || count($data) <= 0) {
            return false;
        }
        $xml = "<xml>";
        foreach ($data as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
}
