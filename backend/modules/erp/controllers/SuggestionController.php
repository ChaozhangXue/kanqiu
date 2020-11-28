<?php

namespace backend\modules\erp\controllers;

use backend\controllers\BaseController;
use backend\models\search\SuggestionSearch;
use common\models\Suggestion;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * SuggestionController implements the CRUD actions for Suggestion model.
 */
class SuggestionController extends BaseController
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
     * Lists all Suggestion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SuggestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Suggestion model.
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
                $suggest = Suggestion::findOne(['id' => $post['id']]);
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
     * Finds the Suggestion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Suggestion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Suggestion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
