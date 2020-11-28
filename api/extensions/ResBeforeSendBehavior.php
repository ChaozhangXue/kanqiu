<?php

namespace api\extensions;

use Yii;
use yii\base\Behavior;
use yii\web\Response;

class ResBeforeSendBehavior extends Behavior
{

    public $defaultCode = 400;

    public $defaultMsg = 'error';

    // 重载events() 使得在事件触发时，调用行为中的一些方法
    public function events()
    {
        // 在 EVENT_BEFORE_SEND 事件触发时，调用成员函数 beforeSend
        return [
            Response::EVENT_BEFORE_SEND => 'beforeSend',
        ];
    }

    // 注意 beforeSend 是行为的成员函数，而不是绑定的类的成员函数。
    // 还要注意，这个函数的签名，要满足事件 handler 的要求。
    public function beforeSend($event)
    {
        try {
            /** @var yii\web\Response $response */
            $response = $event->sender;
            if ($response->data === null) {
                $response->data = [
                    'code' => $this->defaultCode,
                    'message' => $this->defaultMsg,
                ];
            } elseif (!$response->isSuccessful) {
                $exception = Yii::$app->getErrorHandler()->exception;
                if (is_object($exception) && !$exception instanceof yii\web\HttpException) {
                    throw $exception;
                } else {
                    $rData = $response->data;
                    $message = [];
                    if (!isset($rData['message'])) {
                        foreach ($rData as $v) {
                            $message[] = $v['message'];
                        }
                        $message = implode('\\n', $message);
                    } else {
                        $message = $rData['message'];
                    }
                    $response->data = [
                        'code' => empty($rData['code']) ? $this->defaultCode : $rData['code'],
                        'message' => $message ? $message : $this->defaultMsg,
                    ];
                }
            } else {
                /**
                 * $response->isSuccessful 表示是否会抛出异常
                 * 值为 true, 代表返回数据正常，没有抛出异常
                 */
                $rData = $response->data;
                $res = [
                    'code' => isset($rData['code']) ? $rData['code'] : 200,
                    'message' => isset($rData['message']) ? $rData['message'] : '',
                ];
                if (isset($rData['code'])) {
                    unset($rData['code']);
                }
                if (isset($rData['message'])) {
                    unset($rData['message']);
                }
                $res['data'] = isset($rData['data']) ? $rData['data'] : $rData;
                if (empty($res['data'])) {
                    unset($res['data']);
                }
                $response->data = $res;
            }
        } catch (\Exception $e) {
            $response->data = [
                'code' => $this->defaultCode,
                'message' => $e->getMessage(),
            ];
        }
        $response->statusCode = 200;
        return true;
    }
}