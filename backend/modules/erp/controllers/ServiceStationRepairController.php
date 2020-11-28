<?php

namespace backend\modules\erp\controllers;

use backend\controllers\BaseController;
use Yii;
use common\models\ServiceStationRepair;
use backend\models\search\ServiceStationRepairSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ServiceStationRepairController implements the CRUD actions for ServiceStationRepair model.
 */
class ServiceStationRepairController extends BaseController
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
     * Lists all ServiceStationRepair models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServiceStationRepairSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ServiceStationRepair model.
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
                $suggest = ServiceStationRepair::findOne(['id' => $post['id']]);
                if (!$suggest) {
                    throw new \Exception('数据有误，请刷新重试');
                }
                $suggest['feedback_msg'] = $post['feedback_msg'];
                $suggest->status = 1;
                if (!$suggest->save()) {
                    throw new \Exception(array_values($suggest->firstErrors)[0]);
                }
                $this->success('已回复');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }


    /**
     * Finds the ServiceStationRepair model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ServiceStationRepair the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ServiceStationRepair::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
