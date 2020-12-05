<?php

namespace api\controllers;

use api\controllers\BaseController;
use common\models\Address;
use common\models\UserInfo;
use Yii;

class UserInfoController extends BaseController
{
    public $modelClass = 'common\models\UserInfo';
	
	public function actionLogin(){
		print_r(1);die;
	}
	
	public function actionSendCapture(){
	
	}
	
	public function actionRegister(){
        $params = \Yii::$app->request->post();

        $user_info = new UserInfo();

        foreach ($params as $key => $value){
            $user_info->$key = $value;
        }
        $user_info->save();

        $this->success();
	}
}
