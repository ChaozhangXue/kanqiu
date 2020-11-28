<?php

namespace backend\modules\system\services;

use backend\models\User;
use common\services\BaseService;

class UserService extends BaseService
{

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveUser($data)
    {
        if (isset($data['id']) && $data['id']) {
            $user = User::find()->where(['id' => $data['id']])->one();
            if (!$user) {
                throw new \Exception('用户有误');
            }
            $user->load(['User' => $data]);
        } else {
            /** @var User $user */
            $user = new User();
            $user->load(['User' => $data]);
            $user->setPassword('123456');
            $user->generateAuthKey();
        }
        if (!$user->save()) {
            throw new \Exception(array_values($user->firstErrors)[0]);
        }
    }

    /**
     * @param $admin
     * @param \common\models\User $user
     * @throws \Exception
     */
    public function changePassword($user, $data)
    {

        $user->setPassword($data['password']);
        $user->generateAuthKey();
        if (!$user->save()) {
            throw new \Exception(array_values($user->firstErrors)[0]);
        }
    }
}

      