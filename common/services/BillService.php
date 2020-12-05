<?php

namespace common\services;


use common\models\CustomerBill;
use common\models\DriverAccount;
use common\models\DriverBill;
use common\models\ServiceStation;
use common\models\StationAccount;
use common\models\StationBill;
use common\models\UserInfo;

class UserInfoService extends BaseService
{
    public function create(){
        $model = new UserInfo();

    }

}