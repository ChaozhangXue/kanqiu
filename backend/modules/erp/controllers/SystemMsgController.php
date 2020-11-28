<?php

namespace backend\modules\erp\controllers;

use backend\controllers\BaseController;
use backend\models\search\SystemMsgSearch;
use common\models\Customer;
use common\models\SystemMsg;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * SystemMsgController implements the CRUD actions for SystemMsg model.
 */
class SystemMsgController extends BaseController
{
    /**
     * Lists all SystemMsg models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SystemMsgSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SystemMsg model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SystemMsg model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEdit()
    {

        $data = Yii::$app->request->get();
        if (isset($data['id']) && $data['id'] != '') {
            $model = $this->findModel($data['id']);
            if ($model->publish_time <= time()) {
                throw new \Exception('消息已加入推送队列，不允许修改');
            }
        } else {
            $model = new SystemMsg();
        }
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $data = $post['SystemMsg'];
            if (isset($data['id']) && $data['id'] != '') {
                $model = $this->findModel($data['id']);
            }
            if ($data['publish_time'] == '') {
                $data['publish_time'] = time();
            } else {
                $data['publish_time'] = strtotime($data['publish_time']);
                if ($data['publish_time'] === false) {
                    $model->addError('publish_time', '发布时间有误');
                }
            }
            if ($data['type'] == '1' && $data['receive_id'] == '') {
                $model->addError('receive_id', '请输入接收用户');
            }
            if ($data['type'] != '1') {
                $data['receive_id'] = 0;
            }
            $user = Yii::$app->user->identity;
            $data['publisher'] = $user['realname'];
            if ($data['type'] == 1) {
                //会员收不到推送消息，推送状态直接置为已完成
                $customer = Customer::find()->where(['customer_id' => $data['receive_id']])->one();
                if ($customer['type'] == 2) {
                    $data['status'] = 2;
                }
                $data['customer_type'] = $customer['type'];
            }
            //会员收不到推送消息，推送状态直接置为已完成
            if ($data['type'] == '5') {
                $data['status'] = 2;
            }
            $model->load(['SystemMsg' => $data]);
            if (!$model->hasErrors() && $model->save()) {
                return $this->redirect(['view', 'id' => $model['id']]);
            }

            $model->load($post);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * 加入发送队列
     */
    public function actionAddToSend()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $post = Yii::$app->request->post();
                $model = $this->findModel($post['id']);
                $model->status = 1;
                $model->save();
                $this->success('加入成功');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }


    /**
     * Deletes an existing SystemMsg model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the SystemMsg model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SystemMsg the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemMsg::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
