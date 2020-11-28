<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\BusRepair;

class BusRepairController extends BaseController
{
    public $modelClass = 'common\models\BusRepair';

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
                $list = BusRepair::find()
                    ->where(['customer_id' => \Yii::$app->user->id])->orderBy('id desc')
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
                        'type' => '车辆',
                        'repair_card' => $v['repair_card'],
                        'pic' => $images,
                        'create_time' => date('Y.m.d H:i:s', $v['create_time']),
                        'reason' => $v['reason'],
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
                $post['pic'] = $this->parseFileOrUrl('pic', 'repair');
                $data = [
                    'customer_id' => \Yii::$app->user->id,
                    'name' => $customer['realname'],
                    'phone' => $customer['mobile'],
                    'repair_card' => $post['repair_card'],
                    'reason' => $post['reason'],
                    'pic' => isset($post['pic']) ? $post['pic'] : ''
                ];
                $model = new BusRepair();
                $model->load(['BusRepair' => $data]);
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
                $model = BusRepair::findOne(['id' => $get['id'], 'customer_id' => \Yii::$app->user->id]);
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
                    'pic' => $images,
                    'reason' => $model['reason'],
                    'remark' => $model['remark'],
                    'create_time' => date('Y.m.d H:i:s', $model['create_time']),
                    'repair_card' => $model['repair_card'],
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
