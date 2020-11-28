<?php

namespace common\services;

use AlibabaCloud\Client\AlibabaCloud;
use common\models\Customer;
use common\models\SystemMsg;
use common\models\User;
use JPush\Client as JPush;

class MessageService extends BaseService
{
    /**
     * @param $title
     * @param $content
     * @param array|string $users
     * @param array $extras
     * @param string $publish_time
     * @throws \Exception
     */
    public function sendMessage($title, $content, $users = [], $extras = [], $publish_time = '')
    {
        $messageType = 1;
        if (!is_array($users)) {
            $users = [$users];
        } else if (!count($users)) {
            $messageType = 2;
            $users = [0];
        }
        $user = \Yii::$app->user->identity;
        $publisher = '';
        if ($user instanceof User) {
            $publisher = $user['realname'];
        } else if ($user['type'] == 1) {
            $publisher = $user['realname'];
        } else if ($user['type'] == 2) {
            $publisher = $user['nickname'];
        } else if ($user['type'] == 3) {
            $publisher = $user['username'];
        }
        foreach ($users as $receiveId) {
            $model = new SystemMsg();
            $model['type'] = $messageType;
            $model['title'] = $title;
            $model['content'] = $content;
            $model['receive_id'] = $receiveId;
            if ($receiveId != 0) {
                $customer = Customer::findOne($receiveId);
                $model['customer_type'] = $customer['type'];
            }
            $model['publisher'] = $publisher;
            if (!empty($extras)) {
                $model['extras'] = json_encode($extras);
            }
            $model['publish_time'] = $publish_time ? $publish_time : time();
            if (!$model->save()) {
                throw new \Exception(array_values($model->firstErrors)[0]);
            }
        }
    }

    /**
     * 推送
     * @param SystemMsg $message
     * @param $client
     */
    public function pushMessage($message, $client = null)
    {
        if ($client == null) {
            $client = new JPush(\Yii::$app->params['jpush']['app_key'], \Yii::$app->params['jpush']['market_secret']);
        }
        try {
            $pusher = $client->push();
            $pusher->setPlatform('all');
            switch ($message['type']) {
                case '1':
                    $pusher->addAlias((string)$message['receive_id']);
                    break;
                case '2':
                    $pusher->addAllAudience();
                    break;
                case '3':
                    $pusher->addTag('driver');
                    break;
                case '4':
                    $pusher->addTag('station');
                    break;
                case '5':
                    throw new \Exception('会员用户不提供提送功能');
            }
            $pusher->addAndroidNotification($message['content'], $message['title'], 1, $message['extras'] ? json_decode($message['extras'], true) : null);
            $pusher->send();
            $message['status'] = 2;
            $message['exec_time'] = time();
            $message->save();
        } catch (\Exception $e) {
            $message['status'] = 3;
            $message['response'] = $e->getMessage();
            $message['exec_time'] = time();
            $message->save();
        }
    }

    /**
     * @param $mobile
     * @param $code
     * @param $type
     * @param array $params
     * @throws \Exception
     */
    public function sendSms($mobile, $code, $type, $params = [])
    {
        if(empty($params)){
            $params = ['code' => $code];
        }
        $typeList = \Yii::$app->params['sms_type_list'];
        if (!isset($typeList[$type])) {
            throw new \Exception('短信模版类型有误');
        }
//        if (YII_DEBUG) {
//            return;
//        }
        AlibabaCloud::accessKeyClient(\Yii::$app->params['sms']['AccessKeyID'], \Yii::$app->params['sms']['AccessKeySecret'])
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        $ret = AlibabaCloud::rpc()
            ->product('Dysmsapi')
            // ->scheme('https') // https | http
            ->version('2017-05-25')
            ->action('SendSms')
            ->method('POST')
            ->options([
                'query' => [
                    'PhoneNumbers' => $mobile,//手机号
                    'TemplateCode' => $typeList[$type]['code'],//模版
                    'SignName' => \Yii::$app->params['sms']['SignName'],//签名
                    'TemplateParam' => json_encode($params),//验证码

                ],
            ])->request();
//print_r($ret);die;

    }
}