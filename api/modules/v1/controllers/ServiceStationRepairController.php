<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\ServiceStation;
use common\models\ServiceStationRepair;

class ServiceStationRepairController extends BaseController
{
    public $modelClass = 'common\models\ServiceStationRepair';

    public function actions()
    {
        $actions = parent::actions();
        // 禁用""index,delete" 和 "create" 操作
        unset($actions['index'], $actions['delete'], $actions['view'], $actions['create'], $actions['update']);
        return $actions;
    }

    public function actionIndex()
    {
        if (\Yii::$app->request->isGet) {
            try {
                $limit = $this->getLimit();
                $list = ServiceStationRepair::find()
                    ->where(['customer_id' => \Yii::$app->user->id])
                    ->orderBy('id desc')
                    ->offset($limit[0])
                    ->limit($limit[1])
                    ->all();
                $res = [];
                foreach ($list as $v) {
                    $picList = $v['pic'] ? explode(',', $v['pic']) : [];
                    $images = [];
                    foreach ($picList as $pic) {
                        $images[] = $pic;
                    }
                    $res[] = [
                        'id' => $v['id'],
                        'repair_station' => $v['repair_station'],
                        'type' => '设备',
                        'pic' => $images,
                        'reason' => $v['reason'],
                        'create_time' => date('Y.m.d H:i:s', $v['create_time']),
                        'remark' => $v['remark'],
                        'status' => $v['status'],
                        'feedback_msg' => $v['feedback_msg']
                    ];
                }
                $this->success($res);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    public function actionCreate()
    {
        if (\Yii::$app->request->isPost) {
            try {
                $post = \Yii::$app->request->post();
                $customer = \Yii::$app->user->identity;
                if ($customer['type'] == 3) {
                    $station = ServiceStation::findOne($customer['relation_id']);
                    $post['repair_station'] = $station['id'];
                    $name = $customer['verify_status'] == 2 ? $customer['realname'] : '站点用户' . $customer['mobile'];
                } else {
                    $name = $customer['verify_status'] == 2 ? $customer['realname'] : '手机用户' . $customer['mobile'];
                }
                $data = [
                    'customer_id' => \Yii::$app->user->id,
                    'name' => $name,
                    'phone' => $customer['mobile'],
                    'repair_station' => $post['repair_station'],
                    'reason' => $post['reason'],
                    'pic' => $this->parseFileOrUrl('pic', 'repair')
                ];
                $model = new ServiceStationRepair();
                $model->load(['ServiceStationRepair' => $data]);
                if (!$model->save()) {
                    throw new \Exception(array_values($model->firstErrors)[0]);
                }
                $this->success([], '您的报修已提交');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }

        }
    }

    public function actionView()
    {
        if (\Yii::$app->request->isGet) {
            try {
                $get = \Yii::$app->request->get();
                $model = ServiceStationRepair::findOne(['id' => $get['id'], 'customer_id' => \Yii::$app->user->id]);
                if (!$model) {
                    throw new \Exception('参数有误');
                }
                $picList = $model['pic'] ? explode(',', $model['pic']) : [];
                $images = [];
                foreach ($picList as $pic) {
                    $images[] = $pic;
                }
                $res[] = [
                    'id' => $model['id'],
                    'repair_station' => $model['repair_station'],
                    'pic' => $images,
                    'create_time' => date('Y.m.d H:i:s', $model['create_time']),
                    'reason' => $model['reason'],
                    'remark' => $model['remark'],
                    'status' => $model['status'],
                    'feedback_msg' => $model['feedback_msg']
                ];
                $this->success($res);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
}
