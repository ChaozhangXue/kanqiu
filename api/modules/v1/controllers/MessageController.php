<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\AnnouncementRead;
use common\models\SystemMsg;

class MessageController extends BaseController
{
    public $modelClass = 'common\models\SystemMsg';

    public function actions()
    {
        $actions = parent::actions();
        // 禁用""index,delete" 和 "create" 操作
        unset($actions['index'], $actions['delete'], $actions['view'], $actions['create'], $actions['update']);
        return $actions;
    }

    /**
     * 消息列表
     */
    public function actionIndex()
    {
        try {
            $limit = $this->getLimit();
            $msgList = SystemMsg::find()->select(['id', 'type', 'title', 'content', 'exec_time'])
                ->where(['or', 'receive_id = ' . \Yii::$app->user->id, 'receive_id = 0'])
                ->orderBy('id desc')
                ->offset($limit[0])
                ->limit($limit[1])
                ->asArray()->all();
            $res = [];
            $readList = AnnouncementRead::find()
                ->select(['message_id'])
                ->where(['customer_id' => \Yii::$app->user->id, 'message_id' => array_column($msgList, 'id')])
                ->asArray()->all();
            $readList = array_column($readList, 'message_id');
            foreach ($msgList as $v) {
                $res[] = [
                    'id' => $v['id'],
                    'type' => $v['type'],
                    'title' => $v['title'],
                    'content' => $v['content'],
                    'publish_time' => date('Y.m.d H:i:s', $v['exec_time']),
                    'is_read' => in_array($v['id'], $readList) ? 1 : 0
                ];
            }
            $this->success($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * 回执
     */
    public function actionRead()
    {
        try {
            $post = \Yii::$app->request->post();
            if (!isset($post['type']) || !in_array($post['type'], ['all', 'single'])) {
                throw new \Exception('参数有误');
            }
            if ($post['type'] == 'single' && (!isset($post['id']) || $post['id'] == '')) {
                throw new \Exception('参数有误');
            }
            if ($post['type'] == 'single') {
                $model = AnnouncementRead::findOne(['message_id' => $post['id'], 'customer_id' => \Yii::$app->user->id]);
                if (!$model) {
                    $model = new AnnouncementRead();
                    $model->message_id = $post['id'];
                    $model->customer_id = \Yii::$app->user->id;
                    $model->save();
                }
            } else {
                $readList = AnnouncementRead::find()->where(['customer_id' => \Yii::$app->user->id])->asArray()->all();
                $readList = array_column($readList, 'message_id');
                $announcementIds = SystemMsg::find()->select(['id'])->where(['status' => 2])->asArray()->all();
                $announcementIds = array_column($announcementIds, 'id');
                $insertList = array_diff($announcementIds, $readList);
                if (count($insertList)) {
                    $list = [];
                    foreach ($insertList as $v) {
                        $list[] = [
                            'message_id' => $v,
                            'customer_id' => \Yii::$app->user->id,
                            'create_time' => time(),
                        ];
                    }
                    \Yii::$app->db->createCommand()
                        ->batchInsert(AnnouncementRead::tableName(), ['message_id', 'customer_id', 'create_time'], $list)
                        ->execute();
                }
            }
            $this->success();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

    }

    public function actionView($id)
    {
        try {
            $model = SystemMsg::findOne($id);
            if (!$model) {
                throw new \Exception('数据有误');
            }
            $res = [
                'id' => $model['id'],
                'type' => $model['type'],
                'title' => $model['title'],
                'content' => $model['content'],
                'extras' => $model['extras'] ? json_decode($model['extras']) : null,
                'publish_time' => date('Y.m.d H:i:s', $model['exec_time']),
            ];
            $this->success($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

}
