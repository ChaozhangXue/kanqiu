<?php

namespace common\services;

use common\models\Customer;
use common\models\CustomerVerify;
use common\models\DriverAccount;
use common\models\DriverBill;
use common\models\SmsVerifyCode;
use common\models\StationAccount;
use common\models\StationBill;
use common\models\StationVerify;

class CustomerService extends BaseService
{

    public $messageService;

    public function __construct()
    {
        $this->messageService = new MessageService();
    }

    /**
     * 用户登录
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function login($data)
    {
        $this->checkVerifyCode($data['mobile'], $data['verify_code']);
        $customer = Customer::find()->where(['mobile' => $data['mobile'], 'type' => 2])->one();
        if (!$customer) {
            $customer = new Customer();
            $customer['mobile'] = $data['mobile'];
            $customer['openid'] = $this->getOpenIdByCode($data['app_code']);
            $customer['avatar'] = $data['avatar'];
            $customer['nickname'] = $data['nickname'];
        } else {
            if ($customer['status'] == 0) {
                throw new \Exception('账户已停用，请联系客服');
            }
        }
        $customer['token'] = $this->generateToken();
        $customer['last_login_time'] = time();

        if (!$customer->save()) {
            throw new \Exception(array_values($customer->firstErrors)[0]);
        }

        $res = [
            'customer_id' => $customer['customer_id'],
            'nickname' => $customer['nickname'],
            'token' => $customer['token'],
            'mobile' => $customer['mobile'],
        ];
        return $res;
    }

    /**
     * 用户登录
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function stationLogin($data)
    {
        $customer = Customer::find()->where(['username' => $data['username'], 'type' => 3])->one();
        if (!$customer) {
            throw new \Exception('用户名密码有误');
        }
        if ($customer['status'] == 0) {
            throw new \Exception('账户已停用，请联系客服');
        }
        if (!\Yii::$app->security->validatePassword($data['password'], $customer['password'])) {
            throw new \Exception('用户名密码有误');
        }
        $customer['token'] = $this->generateToken();
        $customer['last_login_time'] = time();
        if (!$customer->save()) {
            throw new \Exception(array_values($customer->firstErrors)[0]);
        }

        $res = [
            'customer_id' => $customer['customer_id'],
            'username' => $customer['username'],
            'token' => $customer['token'],
        ];
        return $res;
    }

    /**
     * 司机登录
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function driverLogin($data)
    {
        $customer = Customer::find()->where(['username' => $data['username'], 'type' => 1])->one();
        if (!$customer) {
            throw new \Exception('用户名密码有误');
        }
        if ($customer['status'] == 0) {
            throw new \Exception('账户已停用，请联系客服');
        }
        if (!\Yii::$app->security->validatePassword($data['password'], $customer['password'])) {
            throw new \Exception('用户名密码有误');
        }
        $customer['token'] = $this->generateToken();
        $customer['last_login_time'] = time();
        if (!$customer->save()) {
            throw new \Exception(array_values($customer->firstErrors)[0]);
        }

        $res = [
            'customer_id' => $customer['customer_id'],
            'username' => $customer['username'],
            'token' => $customer['token'],
        ];
        return $res;
    }

    /**
     * 会员身份认证
     * @param $cardNo
     * @param $name
     * @throws \Exception
     */
    public function verifyCustomer($post)
    {
        /** @var Customer $customer */
        $customer = \Yii::$app->user->identity;
        if ($customer['type'] != 2) {
            throw new \Exception('数据有误');
        }
        $verify = CustomerVerify::find()
            ->where(['customer_id' => $customer['customer_id'], 'verify_status' => [1, 2]])->one();
        if ($verify) {
            if ($verify['verify_status'] == 2) {
                throw new \Exception('您的身份认证已通过，请勿重复提交');
            } else {
                throw new \Exception('您的身份认证已提交，请勿重复操作');
            }
        }
        $data = [
            'customer_id' => $customer['customer_id'],
            'mobile' => $customer['mobile'],
            'gender' => $customer['gender'],
            'front_photo' => $post['front_photo'],
            'back_photo' => $post['back_photo'],
            'realname' => $post['realname'],
            'idcard' => $post['idcard'],
        ];
        $verify = new CustomerVerify();
        $verify->load(['CustomerVerify' => $data]);
        $data = [
            'cardno' => $post['idcard'],
            'name' => $post['realname']
        ];
        $verifyResult = $this->identityVerify($data);
        if (!$verifyResult['success']) {
            $verify['verify_status'] = 3;
        } else {
            $verify['verify_status'] = 2;
            $customer['realname'] = $verify['realname'];
        }
        if (!$verify->save()) {
            throw new \Exception(array_values($verify->firstErrors)[0]);
        }
        $customer['verify_status'] = $verify['verify_status'];
        if (!$customer->save()) {
            throw new \Exception(array_values($customer->firstErrors)[0]);
        }
        if ($verify['verify_status'] == 3) {
            throw new \Exception($verifyResult['msg']);
        }
    }

    /**
     * 站点认证
     * @param $post
     * @throws \Exception
     */
    public function verifyStation($post)
    {
        /** @var Customer $customer */
        $customer = \Yii::$app->user->identity;
        if ($customer['type'] != 3) {
            throw new \Exception('数据有误');
        }
        $verify = StationVerify::find()
            ->where(['customer_id' => $customer['customer_id'], 'verify_status' => [1, 2]])->one();
        if ($verify) {
            if ($verify['verify_status'] == 2) {
                throw new \Exception('您的身份认证已通过，请勿重复提交');
            } else {
                throw new \Exception('您的身份认证已提交，请勿重复操作');
            }
        }
        $data = [
            'customer_id' => $customer['customer_id'],
            'station_id' => $customer['relation_id'],
            'mobile' => $customer['mobile'],
            'front_photo' => $post['front_photo'],
            'back_photo' => $post['back_photo'],
            'realname' => $post['realname'],
            'idcard' => $post['idcard'],
        ];
        $verify = new StationVerify();
        $verify->load(['StationVerify' => $data]);
        $data = [
            'cardno' => $post['idcard'],
            'name' => $post['realname']
        ];
        $verifyResult = $this->identityVerify($data);
        if (!$verifyResult['success']) {
            $verify['verify_status'] = 3;
        } else {
            $verify['verify_status'] = 2;
            $customer['realname'] = $verify['realname'];
        }
        $verify->save();
        $customer['verify_status'] = $verify['verify_status'];
        if (!$customer->save()) {
            throw new \Exception(array_values($customer->firstErrors)[0]);
        }
        if ($verify['verify_status'] == 3) {
            throw new \Exception($verifyResult['msg']);
        }
    }

    public function generateToken()
    {
        return md5(time() . rand(100000, 999999));
    }

    public function generateNickName()
    {
        return 'id_' . $this->uuid(10, 62);
    }

    /**
     * 发送验证码
     * @param $mobile
     * @return string
     * @throws \Exception
     */
    public function sendVerifyCode($mobile, $type = 2)
    {
        $smsVerifyCode = SmsVerifyCode::find()
            ->where(['mobile' => $mobile, 'status' => 0])
            ->andWhere('expire_time>' . time())
            ->one();
        if ($smsVerifyCode) {
            throw new \Exception('您的验证码已发送，请稍后再试');
        }
        $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $this->messageService->sendSms($mobile, $code, $type);
        $smsVerifyCode = new SmsVerifyCode();
        $smsVerifyCode->mobile = $mobile;
        $smsVerifyCode->code = $code;
        $smsVerifyCode->expire_time = time() + \Yii::$app->params['verify_code_expire'];
        $smsVerifyCode->save();
        return $code;
    }

    /**
     * 个人中心
     * @return array
     */
    public function profile()
    {
        /** @var Customer $customer */
        $customer = \Yii::$app->user->identity;
        $res = [];
        $res['token'] = $customer['token'];
        $res['customer_id'] = $customer['customer_id'];
        $res['nickname'] = $customer['nickname'];
        $res['realname'] = $customer['realname'];
        $res['gender'] = $customer['gender'];
        $res['mobile'] = $customer['mobile'];
        $res['birth_date'] = $customer['birth_date'] == 0 ? '' : $customer['birth_date'];
        $res['avatar'] = $customer['avatar'];
        $res['verify_status'] = $customer['verify_status'];
        $res['relation_id'] = $customer['relation_id'];
        if ($customer['type'] == 1) {
//            1司机账号 2会员账号 3站点用户
            //司机
            $account = DriverAccount::findOne(['driver_id' => $customer['customer_id']]);
            if (!$account) {
                $account['balance'] = '0.00';
                $account['total_income'] = '0.00';
                $yesterday_income = '0.00';
            } else {
                $today = strtotime(date('Y-m-d'));
                $bill = DriverBill::find()
                    ->select(['sum(commission) as commission'])
                    ->where(['driver_id' => $customer['customer_id']])
                    ->andWhere(['between', 'create_time', $today - 24 * 3600, $today - 1])
                    ->one();
                $yesterday_income = $bill['commission'] ? $bill['commission'] : '0.00';
            }
            $res['balance'] = $account['balance'];
            $res['yesterday_income'] = $yesterday_income;
            $res['total_income'] = $account['total_income'];
        }elseif($customer['type'] == 3){
            //司机
            $account = StationAccount::findOne(['station_id' => $customer['customer_id']]);
            if (!$account) {
                $account['balance'] = '0.00';
                $account['total_income'] = '0.00';
                $yesterday_income = '0.00';
            } else {
                $today = strtotime(date('Y-m-d'));
                $bill = StationBill::find()
                    ->select(['sum(yongjin) as commission'])
                    ->where(['station_id' => $customer['customer_id']])
                    ->andWhere(['between', 'create_time', $today - 24 * 3600, $today - 1])
                    ->one();
                $yesterday_income = $bill['commission'] ? $bill['commission'] : '0.00';
            }
            $res['balance'] = $account['balance'];
            $res['yesterday_income'] = $yesterday_income;
            $res['total_income'] = $account['total_income'];
        }
        return $res;
    }

    /**
     * 修改个人资料
     * @param $data
     * @throws \Exception
     */
    public function changeProfile($data)
    {
        /** @var Customer $customer */
        $customer = \Yii::$app->user->identity;
        if (isset($data['nickname']) && $data['nickname'] != '') {
            $customer['nickname'] = $data['nickname'];
        }
        if (isset($data['avatar']) && $data['avatar'] != '') {
            $customer['avatar'] = $data['avatar'];
        }
        if (isset($data['birth_date']) && $data['birth_date'] != '') {
            $customer['birth_date'] = $data['birth_date'];
        }
        if (isset($data['gender']) && $data['gender'] != '') {
            $customer['gender'] = $data['gender'];
        }
        if (isset($data['mobile']) && $data['mobile'] != '') {
            $this->checkVerifyCode($data['mobile'], $data['verify_code']);
            $obj = Customer::find()
                ->where(['mobile' => $data['mobile']])
                ->andWhere(['!=', 'customer_id', 1])
                ->one();
            if ($obj) {
                throw new \Exception('手机号已存在');
            }
            $customer['mobile'] = $data['mobile'];
        }
        if (!$customer->save()) {
            throw new \Exception(array_values($customer->firstErrors)[0]);
        }
    }


    /**
     * 实名认证信息
     */
    public function userIdentity()
    {
        $customerVerify = CustomerVerify::find()
            ->where(['customer_id' => \Yii::$app->user->id])
            ->orderBy('verify_id desc')->one();
        $res = [];
        $res['mobile'] = $customerVerify['mobile'];
        $res['gender'] = $customerVerify['gender'];
        $res['remark'] = $customerVerify['remark'];
        $res['front_photo'] = $customerVerify['front_photo'];
        $res['back_photo'] = $customerVerify['back_photo'];
        $res['idcard'] = $customerVerify['idcard'];
        $res['realname'] = $customerVerify['realname'];
        $res['verify_status'] = $customerVerify['verify_status'];
        return $res;
    }

    /**
     * 实名认证信息
     */
    public function stationIdentity()
    {
        $customerVerify = StationVerify::find()
            ->where(['customer_id' => \Yii::$app->user->id])
            ->orderBy('verify_id desc')->one();
        $res = [];
        $res['mobile'] = $customerVerify['mobile'];
        $res['front_photo'] = $customerVerify['front_photo'];
        $res['back_photo'] = $customerVerify['back_photo'];
        $res['idcard'] = $customerVerify['idcard'];
        $res['realname'] = $customerVerify['realname'];
        $res['verify_status'] = $customerVerify['verify_status'];
        return $res;
    }

    /**
     * 验证手机验证码是否匹配
     * @param $mobile
     * @param $code
     * @throws \Exception
     */
    public function checkVerifyCode($mobile, $code)
    {
        if (YII_DEBUG && $data['verify_code'] = '9998') {
            return;
        }
        $smsVerifyCode = SmsVerifyCode::find()->where(['mobile' => $mobile, 'code' => $code, 'status' => 0])->one();
        if (!$smsVerifyCode) {
            throw new \Exception('验证码有误');
        }
        if ($smsVerifyCode['expire_time'] < time()) {
            throw new \Exception('验证码已过期');
        }
        $smsVerifyCode['status'] = 1;
        $smsVerifyCode->save();
    }

    /**
     * 微信小程序code换openid
     * @param $code
     * @return mixed
     * @throws \Exception
     */
    public function getOpenIdByCode($code)
    {
        $t_url = 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code';
        $url = sprintf($t_url, \Yii::$app->params['wechat']['app_id'], \Yii::$app->params['wechat']['app_secret'], $code);
        $apiData = $this->sendRequest($url, [], 'GET');
        if ($apiData) {
            $apiData = json_decode($apiData, true);
        }
        if (empty($apiData) || !isset($apiData['openid'])) {
            throw new \Exception('微信信息注册失败');
        }
        return $apiData['openid'];
    }

    /**
     * @param Customer $customer
     * @param $data
     * @throws \Exception
     */
    public function changePassword($customer, $password)
    {
        $customer->setPassword($password);
        if (!$customer->save()) {
            throw new \Exception(array_values($customer->firstErrors)[0]);
        }
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function identityVerify($data)
    {
        if (YII_DEBUG) {
            return [
                'success' => true,
                'msg' => 'DEBUG-实名认证接口未开放'
            ];
        }
        $header = [
            'Content-type: application/json; charset=utf-8'
        ];
        $content = $this->sendRequest(\Yii::$app->params['identity_verify_url'], $data, 'POST', $header);
        $verify['verify_response'] = $content;
        $response = json_decode($content, true);
        if (!$response) {
            throw new \Exception('身份认证失败，请稍后重试~');
        }
        if ($response['code'] != '10000' || !isset($response['result']['resp']['code']) || $response['result']['resp']['code'] != 0) {
            return [
                'success' => false,
                'msg' => $response['msg']
            ];
        } else {
            return [
                'success' => true,
                'msg' => $response['result']['msg']
            ];
        }
    }
}