<?php

namespace app\controllers;

use app\models\Userinfo;
use Yii;

/**
 * 账户管理-用户管理  账户管理  账户管理-新增用户  倒数第一列
 * Class UserController
 * @package app\controllers
 */
class UserInfoController extends BaseController
{
    

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionLogin()
    {
        $username = Yii::$app->request->post('username', '');
        $password = Yii::$app->request->post('password', '');
        if (empty($username) || empty($password)) {
            return $this->error();
        }
        $user = Userinfo::find()->where(['account_name' => $username])->one();
        if ($user) {
            $token = $this->generateRandomString();
            $user->token = $token;
            $user->save();
        } else {
            $this->error('用户不存在');
        }
        $this->success(['token' => $token,'username' => $user->username,'account_name' => $user->account_name]);
        return true;
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString . time();
    }

    public function actionAdd()
    {
//        $id = Yii::$app->request->post('id', '');
        $type = Yii::$app->request->post('type', '');
        $username = trim(Yii::$app->request->post('username', ''));
        $position = Yii::$app->request->post('position', '');
        $phone = Yii::$app->request->post('phone', '');
        $user_ext = Yii::$app->request->post('user_ext', '');
        $account_name = Yii::$app->request->post('account_name', '');
        $password = Yii::$app->request->post('password', '');
        $user_auth = Yii::$app->request->post('user_auth', '');
        $function_auth = Yii::$app->request->post('function_auth', '');
        $department = Yii::$app->request->post('department', '');
        $employment_date = Yii::$app->request->post('employment_date', '');

        $user = new Userinfo();
//        $user->id = $id;
        $user->type = $type;
        $user->username = $username;
        $user->position = $position;
        $user->phone = $phone;
        $user->user_ext = $user_ext;
        $user->account_name = $account_name;
        $user->password = $password;
        $user->user_auth = !empty($user_auth)? implode(',', $user_auth):'';
        $user->function_auth = !empty($function_auth)? implode(',', $function_auth):'';
        $user->department = $department;
        $user->employment_date = $employment_date;
        $user->created_at = date('Y-m-d H:i:s',time());

        $user->maker = isset($this->user_info['username']) ? $this->user_info['username'] : '';
        try {
            if ($user->save()) {
                $this->success();
            } else {
                $this->error();
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     *  全部
     */
    public function actionAll()
    {
        $pageNum = Yii::$app->request->post('pageNum', '0');
        $pageSize = Yii::$app->request->post('pageSize', '5');
        $all = Userinfo::find()->offset($pageNum * $pageSize)->limit($pageSize)->asArray()->all();
        $count = Userinfo::find()->count();

        $this->success(['data' => $all, 'count' => $count]);
    }

    /**
     * 查询
     */
    public function actionSearch()
    {
        $username = trim(Yii::$app->request->post('username', ''));
        $type = Yii::$app->request->post('type', '');
        $phone = Yii::$app->request->post('phone', '');
        $user_ext = Yii::$app->request->post('user_ext', '');

        $params = [];
        if (!empty($username)) {
            $params['username'] = $username;
        }

        if (!empty($type)) {
            $params['type'] = $type;
        }

        if (!empty($phone)) {
            $params['phone'] = $phone;
        }

        if (!empty($user_ext)) {
            $params['user_ext'] = $user_ext;
        }

        if (!empty($params)) {
            $all = Userinfo::find()->where($params)->asArray()->all();
            if (!empty($all)) {
                $this->success(['users' => $all]);
            } else {
                $this->success(['users' => []]);
            }
        } else {
            $all = Userinfo::find()->asArray()->all();
            $this->success(['users' => $all]);
        }
    }

    /**
     *  查看单个用户
     */
    public function actionOne()
    {
        $id = Yii::$app->request->post('id', '');
        $one = Userinfo::find()->where(['id' => $id])->asArray()->one();
        $one['user_auth'] = !empty($one['user_auth'])? explode(',', $one['user_auth']):'';
        $one['function_auth'] = !empty($one['function_auth'])? explode(',', $one['function_auth']):'';

        $this->success(['user' => $one]);
    }

    /**
     *  查看单个
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->post('id', '');
        $update_json = Yii::$app->request->post('update_json', '');
        $update = json_decode($update_json, true);
        $one = Userinfo::find()->where(['id' => $id])->one();

        if (!empty($one) && !empty($update)) {
            foreach ($update as $key => $value) {
//                $user->user_auth = !empty($user_auth)? implode(',', $user_auth):'';
//                $user->function_auth = !empty($function_auth)? implode(',', $function_auth):'';
                if(in_array($key, ['user_auth', 'function_auth'])){
                    $one->$key = !empty($value)? implode(',', $value):'';
                }else{
                    $one->$key = $value;
                }
            }
            try {
                if ($one->save()) {
                    $this->success();
                } else {
                    $this->error();
                }
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        } else {
            $this->error();
        }
        $this->success();
    }


    /**
     *  查看单个用户
     */
    public function actionGetByName()
    {
        $name = Yii::$app->request->post('name', '');
        $one = Userinfo::find()->where(['username' => $name])->asArray()->one();
        $this->success(['user' => $one]);
    }

    const ENABLE = 1;
    const DISABLE = 0;

    /**
     * 停用
     */
    public function actionDisable()
    {
        $id = Yii::$app->request->post('id', '');
        $one = Userinfo::find()->where(['id' => $id])->one();
        if (empty($one)) {
            $this->error();
        }
        $one->enabled = self::DISABLE;
        try {
            if ($one->save()) {
                $this->success();
            } else {
                $this->error();
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }


    /**
     * 停用
     */
    public function actionEnable()
    {
        $id = Yii::$app->request->post('id', '');
        $one = Userinfo::find()->where(['id' => $id])->one();
        if (empty($one)) {
            $this->error();
        }
        $one->enabled = self::ENABLE;
        try {
            if ($one->save()) {
                $this->success();
            } else {
                $this->error();
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}

