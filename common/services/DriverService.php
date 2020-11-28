<?php

namespace common\services;

use common\models\Customer;

class DriverService extends BaseService
{
    public $model;

    public function __construct()
    {
        $this->model = new Customer();
    }
}