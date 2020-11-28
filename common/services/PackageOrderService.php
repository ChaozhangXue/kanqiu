<?php

namespace common\services;

use common\models\BusMoneyRule;
use common\models\BusOrder;
use common\models\BusOrderTrace;
use common\models\Customer;
use common\models\PackageOrder;

class PackageOrderService extends BaseService
{
    public $model;

    public function __construct()
    {
        $this->model = new PackageOrder();
    }

    public function updateAllByOrderId($order_id, $update_data)
    {
        $this->model->updateAll(['order_num' => $order_id], $update_data);
    }
}