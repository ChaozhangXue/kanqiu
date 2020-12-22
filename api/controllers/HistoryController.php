<?php

namespace api\controllers;

use api\controllers\BaseController;
use common\models\BroadcastHistory;
use common\models\WatchList;
use Yii;

class HistoryController extends BaseController
{

    public function actionCreate(){
//        `username` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
//	`password` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
//	`email` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
//	`phone` INT(11) UNSIGNED NULL DEFAULT NULL,

        $params = \Yii::$app->request->post();

        try{
            $model = new BroadcastHistory();

            foreach ($params as $key => $value){
                $model->$key = $value;
            }
            $model->save();
        }catch (\Exception $exception){
            $this->error();
        }

        $this->success();
    }

    public function actionList(){
        $model = new BroadcastHistory();
        $list = $model::find()
            ->select(['title', 'preview_url', 'pan_url', 'price', 'create_time', 'modify_time'])
            ->asArray()
            ->all();
        $this->success($list);
    }

    public function actionDetail(){
        $id = \Yii::$app->request->get('id');

        $model = new BroadcastHistory();

        $list = $model::find()
            ->select(['title', 'preview_url', 'pan_url', 'price', 'create_time', 'modify_time'])
            ->where(['id' => $id])
            ->asArray()
            ->one();
        $this->success($list);
    }

    public function actionDelete(){
        $id = \Yii::$app->request->post('id');
        $model = new BroadcastHistory();

        $model->deleteAll(['id'=> $id]);
        $this->success();
    }
}
