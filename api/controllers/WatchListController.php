<?php

namespace api\controllers;

use api\controllers\BaseController;
use common\models\WatchList;
use Yii;

class WatchListController extends BaseController
{

    public function actionCreate(){
//        `username` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
//	`password` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
//	`email` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
//	`phone` INT(11) UNSIGNED NULL DEFAULT NULL,

        $params = \Yii::$app->request->post();

        try{
            $model = new WatchList();

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
        $date = \Yii::$app->request->get('date', date("Y-m-d"));
        $model = new WatchList();
        $list = $model::find()->where(['game_date' => $date])
            ->andWhere(['>', 'expire_time', date("Y-m-d H:i:s")])
            ->asArray()
            ->all();
        $this->success($list);
    }

    public function actionDelete(){
        $id = \Yii::$app->request->post('id');
        $model = new WatchList();

        $model->deleteAll(['id'=> $id]);
        $this->success();
    }
}
