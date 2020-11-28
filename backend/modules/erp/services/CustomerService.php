<?php

namespace backend\modules\erp\services;

use common\models\Customer;
use common\services\BaseService;

class CustomerService extends BaseService
{
    /**
     * @param $data
     * @return array|Customer|null|\yii\db\ActiveRecord
     * @throws \Exception
     */
    public function saveCustomer($data)
    {
        $selector = Customer::find();
        //司机用户名不能重复
        if ($data['Customer']['type'] == 1) {
            $error = '司机用户名已存在';
            $selector->where(['username' => $data['Customer']['username'], 'type' => 1]);
        }
        //会员手机号不能重复
        if ($data['Customer']['type'] == 2) {
            $error = '会员手机号已存在';
            $selector->where(['mobile' => $data['Customer']['mobile'], 'type' => 2]);
        }
        //站点名不能重复
        if ($data['Customer']['type'] == 3) {
            $error = '站点用户名已存在';
            $selector->where(['username' => $data['Customer']['username'], 'type' => 3]);
        }
        $model = new Customer();
        if (isset($data['Customer']['customer_id']) && $data['Customer']['customer_id']) {
            $model = Customer::find()->where(['customer_id' => $data['Customer']['customer_id']])->one();
            if (!$model) {
                throw new \Exception('账号不存在');
            }
            $selector->andWhere('customer_id != ' . $data['Customer']['customer_id']);
        } else {
            $model->password = \Yii::$app->security->generatePasswordHash('123456');
        }
        $row = $selector->one();
        if ($row) {
            throw new \Exception($error);
        }
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception(array_values($model->getFirstErrors())[0]);
        }
        return $model;
    }

}