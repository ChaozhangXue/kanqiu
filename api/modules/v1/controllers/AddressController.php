<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\Address;
use Yii;

class AddressController extends BaseController
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
        $customer_id = Yii::$app->request->post('customer_id');
        $is_default = Yii::$app->request->post('is_default', 0); //默认值为2  不默认

        if(empty($customer_id)){
            $this->error();
        }
        $model = new Address();

        if($is_default == 1){
            //如果是默认的话  要把其他的都变成不默认
            Address::updateAll(['is_default' => 0], ['and', ['is_default' => 1], ['customer_id' => $customer_id]]);
        }

        if ($model->load(['Address' => Yii::$app->request->post()]) && $model->save()) {
            $this->success();
        }
        $this->error();
    }

    public function actionUpdate()
    {
        $id = \Yii::$app->request->get('id');
        $is_default = Yii::$app->request->post('is_default', 0);


        $model = Address::find()->where(['id' =>$id])->one();
        if($is_default == 1){
            //如果是默认的话  要把其他的都变成不默认
            Address::updateAll(['is_default' => 0], ['and', ['is_default' => 1], ['customer_id' => $model->customer_id]]);
        }
        foreach (Yii::$app->request->post() as $key => $val){
            $model->$key = $val;
        }
        if ($model->save()) {
            $this->success();
        }
        $this->error();
    }
}
