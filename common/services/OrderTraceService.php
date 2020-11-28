<?php

namespace common\services;

use common\models\OrderTrace;

class OrderTraceService extends BaseService
{
    public $messageService;
    public $model;

    public function __construct()
    {
        $this->messageService = new MessageService();
        $this->model = new OrderTrace();
    }



}