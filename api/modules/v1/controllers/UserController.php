<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\Address;
use Yii;

class UserController extends BaseController
{
    public $modelClass = 'common\models\Address';

    public function actions()
    {
        $actions = parent::actions();
        // 禁用""index,delete" 和 "create" 操作
        unset($actions['create'], $actions['update']);
        return $actions;
    }

    public function actionCreate()
    {
        $customer_id = Yii::$app->request->post('phone');
        $capture = Yii::$app->request->post('capture', 0);


    }

    public function actionSendCapture(){

    }

    public function actionLogin(){
        $phone = \Yii::$app->request->get('phone');
        $password = Yii::$app->request->post('password', 0);


    }
}
