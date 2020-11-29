<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\Customer;
use common\models\CustomerBill;
use common\models\DriverBill;
use common\models\StationBill;
use common\services\CustomerService;

class CustomerController extends BaseController
{

    /** @var CustomerService */
    public $customerService;

    public function init()
    {
        parent::init();
        $this->customerService = new CustomerService();
    }

    /**
     * 个人中心
     */
    public function actionProfile()
    {
        try {
            $res = $this->customerService->profile();
            $this->success($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 用户账单
     */
    public function actionBill()
    {
        try {
            $customer = \Yii::$app->user->identity;
            $get = \Yii::$app->request->get();
            if (!isset($get['type']) || !in_array($get['type'], ['income', 'issue'])) {
                throw new \Exception('参数有误');
            }
            if ($customer['type'] == 1){
                if ($get['type'] == 'income') {
                    $bills = DriverBill::find()
                        ->where(['driver_id' => \Yii::$app->user->id])
                        ->orderBy('create_time desc')->all();
                    $dateKey = 'create_time';
                } else {
                    $bills = DriverBill::find()
                        ->where(['driver_id' => \Yii::$app->user->id])
                        ->andWhere(['status' => 2])
                        ->orderBy('verify_time desc')->all();
                    $dateKey = 'verify_time';
                }
                $res = [];
                foreach ($bills as $bill) {
                    $date = date('Y-m', $bill[$dateKey]);
                    if (!isset($res[$date])) {
                        $res[$date] = [
                            'date' => $date,
                            'income' => 0,
                            'list' => []
                        ];
                    }
                    $res[$date]['list'][] = [
                        'order_no' => $bill['order_no'],
                        'commission' => $bill['commission'],
                        'bill_type' => \Yii::$app->params['bill_type_list'][$bill['bill_type']],
                        'bill_time' => date('m-d H:i:s', $bill[$dateKey]),
                    ];
                    $res[$date]['income'] += $bill['commission'];
                    $res[$date]['income'] = (string)number_format($res[$date]['income'], 2, '.', ',');
                }
                $res = array_values($res);
                $this->success($res);
            } elseif($customer['type'] == 3){
                //服务站点的订单
                if ($get['type'] == 'income') {
                    $bills = StationBill::find()
                        ->where(['station_id' => $customer->relation_id])
                        ->orderBy('created_at desc')->all();
                    $dateKey = 'created_at';
                } else {
                    $bills = StationBill::find()
                        ->where(['station_id' => $customer->relation_id])
                        ->andWhere(['status' => 1])
                        ->orderBy('verify_time desc')->all();
                    $dateKey = 'verify_time';
                }
                $res = [];
                foreach ($bills as $bill) {
                    $date = date('Y-m', strtotime($bill[$dateKey]));
                    if (!isset($res[$date])) {
                        $res[$date] = [
                            'date' => $date,
                            'income' => 0,
                            'list' => []
                        ];
                    }
                    $res[$date]['list'][] = [
                        'order_no' => $bill['order_id'],
                        'commission' => $bill['yongjin'],
                        'bill_type' => '送包裹',
                        'bill_time' => date('m-d H:i:s', strtotime($bill[$dateKey])),
                    ];
                    $res[$date]['income'] += $bill['yongjin'];
                    $res[$date]['income'] = (string)number_format($res[$date]['income'], 2, '.', ',');
                }
                $res = array_values($res);
                $this->success($res);
            }else{
                throw new \Exception('用户身份有误');
            }

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 会员消费记录
     */
    public function actionConsume()
    {
        try {
            $customer = \Yii::$app->user->identity;
            if ($customer['type'] != 2) {
                throw new \Exception('用户身份有误');
            }
            $limit = $this->getLimit();
            $bills = CustomerBill::find()
                ->where(['customer_id' => \Yii::$app->user->id])
                ->orderBy('create_time desc')
                ->offset($limit[0])
                ->limit($limit[1])
                ->all();
            $res = [];
            foreach ($bills as $v) {
                $title = '寄件';//todo 根据客户订单的类型来区分
                if ($v['order_type'] == 2) {//客运订单
                    $title = \Yii::$app->params['bus_order_type_list'][$v['type']];
                }
                $res[] = [
                    'title' => $title,
                    'order_type' => $v['order_type'],
                    'order_no' => $v['order_no'],
                    'money' => $v['pay_money'],
                    'bill_type' => $v['bill_type'],
                    'pay_time' => date('Y-m-d H:i:s', $v['pay_time']),
                ];
            }
            $this->success($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 修改密码
     */
    public function actionChangePassword()
    {
        try {
            /** @var Customer $customer */
            $customer = \Yii::$app->user->identity;
            //会员账号不能修改密码
            if ($customer['type'] == 2) {
                throw new \Exception('用户信息有误');
            }
            $post = \Yii::$app->request->post();
            if (!isset($post['password']) || $post['password'] == '') {
                throw new \Exception('原密码不能为空');
            }
            if (!isset($post['new_password']) || $post['new_password'] == '') {
                throw new \Exception('新密码不能为空');
            }
            if (!\Yii::$app->security->validatePassword($post['password'], $customer['password'])) {
                throw new \Exception('您输入的密码有误');
            }
            $this->customerService->changePassword($customer, $post['new_password']);
            $this->success([], '您的密码已修改');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 修改个人信息
     */
    public function actionChangeProfile()
    {
        try {
            if (\Yii::$app->request->isPost) {
                $post = \Yii::$app->request->post();
                $file = $this->parseFileOrUrl('avatar', 'customer/avatar');
                if ($file) {
                    $post['avatar'] = $file;
                }
                $this->customerService->changeProfile($post);
                $this->success();
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 用户实名信息
     */
    public function actionUserIdentity()
    {
        try {
            $res = $this->customerService->userIdentity();
            $this->success($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 用户实名信息
     */
    public function actionStationIdentity()
    {
        try {
            $res = $this->customerService->stationIdentity();
            $this->success($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 身份认证
     */
    public function actionVerifyUserIdentity()
    {
        try {
            if (\Yii::$app->request->isPost) {
                $post = \Yii::$app->request->post();
                $post['front_photo'] = $this->parseFileOrUrl('front_photo', 'customer/verify');
                $post['back_photo'] = $this->parseFileOrUrl('back_photo', 'customer/verify');
                $this->customerService->verifyCustomer($post);
                $this->success([], '实名认证已完成');
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 身份认证
     */
    public function actionVerifyStationIdentity()
    {
        try {
            if (\Yii::$app->request->isPost) {
                $post = \Yii::$app->request->post();
                $post['front_photo'] = $this->parseFileOrUrl('front_photo', 'customer/verify');
                $post['back_photo'] = $this->parseFileOrUrl('back_photo', 'customer/verify');
                $this->customerService->verifyStation($post);
                $this->success([], '实名认证已完成');
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
