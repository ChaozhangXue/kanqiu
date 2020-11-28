<?php

namespace console\controllers;

use common\models\SystemMsg;
use common\services\MessageService;
use JPush\Client as JPush;


class PushController extends BaseController
{

    public function actionIndex()
    {
        $lock_file = 'push.lock';
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
        $messageService = new MessageService();
        $msgList = SystemMsg::find()
            ->where(['status' => 1])
            ->andWhere('publish_time<' . time())->all();
        $client = new JPush(\Yii::$app->params['jpush']['app_key'], \Yii::$app->params['jpush']['market_secret']);
        foreach ($msgList as $v) {
            $messageService->pushMessage($v, $client);
        }
    }
}