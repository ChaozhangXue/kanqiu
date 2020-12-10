<?php

namespace api\controllers;

use api\controllers\BaseController;
use common\models\Address;
use common\models\FreeWatch;
use common\models\Invite;
use common\models\InviteRecord;
use common\models\UserInfo;
use Yii;

class UserInfoController extends BaseController
{
    public $modelClass = 'common\models\UserInfo';
	
	public function actionLogin(){
        $phone = \Yii::$app->request->post('phone');
        $username = \Yii::$app->request->post('username');
        $password = \Yii::$app->request->post('password');
        $email = \Yii::$app->request->post('email');

        $user = new UserInfo();
        if(!empty($phone)){
            $user_info = $user::find()->where(['phone'=> $phone])->one();
        }

        if(!empty($username)){
            $user_info = $user::find()->where(['username'=> $username])->one();
        }

        if(!empty($email)){
            $user_info = $user::find()->where(['email'=> $email])->one();
        }

        if(empty($user_info)){
            $this->error('用户已存在');
        }else{
            if($user_info->password != $password){
                $this->error('密码不正确');
            }
        }

        $this->success();
    }
	
	public function actionSendCapture(){
	
	}

	public function actionCreate(){
//        `username` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
//	`password` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
//	`email` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
//	`phone` INT(11) UNSIGNED NULL DEFAULT NULL,

        $params = \Yii::$app->request->post();
        $invite_code = \Yii::$app->request->post('invite_code');
        unset($params['invite_code']);
        try{
            $model = new UserInfo();

            foreach ($params as $key => $value){
                $model->$key = $value;
            }
            $model->save();

            if(isset($invite_code)){
                //如果有邀请码 增加一条邀请记录
                $invite_model = new InviteRecord();
                $invite_detail = explode('_', $invite_code);
                $inviter_id = $invite_detail[0];
                $invite_model->inviter_id = $inviter_id;
                $invite_model->inviter_code = $invite_code;
                $invite_model->invited_id = $model->id;
                $invite_model->create_time = date('Y-m-d H:i:s');
                $invite_model->save();
            }
        }catch (\Exception $exception){
            $this->error();
        }

        $this->success();
	}

    /**
     * 判断是不是新用户
     */
	public function actionCheckFree(){
	    $ip = $this->getClientIp();
        $model = new FreeWatch();
        $free_watch_history = $model::find()->where(['ip' => $ip])->one();
        if(!empty($free_watch_history)){
            $this->success(['is_new_user' => 0]);
        }else{
            $model->create_time = date("Y-m-d H:i:s");
            $model->ip = $ip;
            $model->save();
            $this->success(['is_new_user' => 1]);
        }
	}

    protected function getClientIp ()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * 生成邀请码
     */
    public function actionGenerateInvite(){
	    $user_id = Yii::$app->request->post('id');

	    //判断用户在不在
        $user_model = new UserInfo();
        $user_info = $user_model::find()->where(['id' => $user_id])->one();

        if(empty($user_info)){
            $this->error('用户不存在');
        }

	    $model = new Invite();
	    $invite_data = $model::find()->where(['inviter_id' => $user_id])->one();
	    if(empty($invite_data)){
            $invite_code = $user_id . '_' . time();
            $model->inviter_id = $user_id;
            $model->invite_code = $invite_code;
            $model->save();
        }else{
            $invite_code = $invite_data->invite_code;
        }
        $this->success($invite_code);
    }
}
