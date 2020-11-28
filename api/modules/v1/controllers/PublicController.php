<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\services\CustomerService;

class PublicController extends BaseController
{

    /** @var CustomerService */
    public $customerService;

    public function init()
    {
        parent::init();
        $this->customerService = new CustomerService();
    }

    /**
     * 会员登录
     */
    public function actionLogin()
    {
        try {
            $post = \Yii::$app->request->post();
            if (!isset($post['mobile']) || !$post['mobile']) {
                throw new \Exception('手机号不能为空');
            }
            if (!isset($post['verify_code']) || !$post['verify_code']) {
                throw new \Exception('验证码不能为空');
            }
            if (!isset($post['nickname']) || !$post['nickname']) {
                throw new \Exception('数据有误');
            }
            if (!isset($post['avatar']) || !$post['avatar']) {
                throw new \Exception('数据有误');
            }
            if (!isset($post['app_code']) || !$post['app_code']) {
                throw new \Exception('数据有误');
            }
            $res = $this->customerService->login($post);
            $this->success($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 司机登录
     */
    public function actionDriverLogin()
    {
        try {
            $post = \Yii::$app->request->post();
            if (!isset($post['username']) || !$post['username']) {
                throw new \Exception('用户名不能为空');
            }
            if (!isset($post['password']) || !$post['password']) {
                throw new \Exception('密码不能为空');
            }
            $res = $this->customerService->driverLogin($post);
            $this->success($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 站点登录
     */
    public function actionStationLogin()
    {
        try {
            $post = \Yii::$app->request->post();
            if (!isset($post['username']) || !$post['username']) {
                throw new \Exception('用户名不能为空');
            }
            if (!isset($post['password']) || !$post['password']) {
                throw new \Exception('密码不能为空');
            }
            $res = $this->customerService->stationLogin($post);
            $this->success($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 发送验证码
     */
    public function actionSendCode()
    {
        try {
            $post = \Yii::$app->request->post();
            if (!isset($post['mobile']) || !$post['mobile']) {
                throw new \Exception('手机号不能为空');
            }
            if (!isset($post['type']) || $post['type'] == '') {
                $post['type'] = 2;//默认是验证码登录
            }
            $code = $this->customerService->sendVerifyCode($post['mobile']);
            $res = YII_DEBUG ? ['verify_code' => $code] : [];
            $this->success($res, '验证码已发送');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

}
