<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\Complaint;

class ComplaintController extends BaseController
{
    public $modelClass = 'common\models\Complaint';

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
                $list = Complaint::find()
                    ->where(['customer_id' => \Yii::$app->user->id])->orderBy('id desc')
                    ->offset($limit[0])
                    ->limit($limit[1])
                    ->all();
                $res = [];
                foreach ($list as $v) {
                    $res[] = [
                        'id' => $v['id'],
                        'name' => $v['name'],
                        'phone' => $v['phone'],
                        'detail' => $v['detail'],
                        'status' => $v['status'],
                        'create_time' => date('Y.m.d H:i:s', $v['create_time']),
                        'update_time' => date('Y.m.d H:i:s', $v['update_time']),
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
                $data = [
                    'customer_id' => \Yii::$app->user->id,
                    'name' => $post['name'],
                    'phone' => $post['phone'],
                    'detail' => $post['detail'],
                ];
                $model = new Complaint();
                $model->load(['Complaint' => $data]);
                if (!$model->save()) {
                    throw new \Exception(array_values($model->firstErrors)[0]);
                }
                $this->success([], '您的投诉已提交');
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
                $model = Complaint::findOne(['id' => $get['id'], 'customer_id' => \Yii::$app->user->id]);
                if (!$model) {
                    throw new \Exception('参数有误');
                }
                $res[] = [
                    'id' => $model['id'],
                    'detail' => $model['detail'],
                    'status' => $model['status'],
                    'feedback_msg' => $model['feedback_msg'],
                    'remark' => $model['remark']
                ];
                $this->success($res);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

}
