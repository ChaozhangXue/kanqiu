<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\Address;
use Yii;

class WatchListController extends BaseController
{
    public $modelClass = 'common\models\WatchList';
}
