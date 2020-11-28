<?php

namespace backend\modules\erp\controllers;

use backend\controllers\BaseController;
use Yii;
use common\models\Complaint;
use backend\models\search\ComplaintSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ComplaintController implements the CRUD actions for Complaint model.
 */
class ComplaintController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Complaint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ComplaintSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Complaint model.
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

    public function actionFeedback()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $post = Yii::$app->request->post();
                $complaint = Complaint::findOne(['id' => $post['id']]);
                if (!$complaint) {
                    throw new \Exception('数据有误，请刷新重试');
                }
                $complaint['feedback_msg'] = $post['feedback_msg'];
                $complaint->status = 1;
                if (!$complaint->save()) {
                    throw new \Exception(array_values($complaint->firstErrors)[0]);
                }
                $this->success('已回复');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
    /**
     * Finds the Complaint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Complaint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Complaint::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
