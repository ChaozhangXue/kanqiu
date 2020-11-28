<?php

namespace common\services;

use common\models\DriveTrack;
use common\models\OrderTrace;

class DriveTrackService extends BaseService
{
    public $messageService;
    public $model;

    public function __construct()
    {
        $this->messageService = new MessageService();
        $this->model = new DriveTrack();
    }

    public function getTraceByOrderId($order_id){
        $model = $this->model;
        $data = $model::find()->where(['order_id' => $order_id])->orderBy('time desc')->asArray()->one();
        return $data;
    }

}