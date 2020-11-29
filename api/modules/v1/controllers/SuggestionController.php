<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\Suggestion;

class SuggestionController extends BaseController
{
    public $modelClass = 'common\models\Suggestion';

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
                $list = Suggestion::find()
                    ->where(['customer_id' => \Yii::$app->user->id])->orderBy('id desc')
                    ->offset($limit[0])
                    ->limit($limit[1])
                    ->all();
                foreach ($list as $v) {
                    $res[] = [
                        'id' => $v['id'],
                        'detail' => $v['detail'],
                        'status' => $v['status'],
                        'feedback_msg' => $v['feedback_msg'],
                        'create_time' => date('Y.m.d H:i:s', $v['create_time']),
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
                $data = [
                    'customer_id' => \Yii::$app->user->id,
                    'name' => $customer['nickname'],
                    'phone' => $customer['mobile'],
                    'detail' => $post['detail'],
                ];
                $model = new Suggestion();
                $model->load(['Suggestion' => $data]);
                if (!$model->save()) {
                    throw new \Exception(array_values($model->firstErrors)[0]);
                }
                $this->success([], '您的意见已反馈');
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
                $model = Suggestion::findOne(['id' => $get['id'], 'customer_id' => \Yii::$app->user->id]);
                if (!$model) {
                    throw new \Exception('参数有误');
                }
                $res[] = [
                    'id' => $model['id'],
                    'detail' => $model['detail'],
                    'feedback_msg' => $model['feedback_msg'],
                    'create_time' => date('Y.m.d H:i:s', $model['create_time']),
                    'status' => $model['status'],
                    'remark' => $model['remark']
                ];
                $this->success($res);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

}
