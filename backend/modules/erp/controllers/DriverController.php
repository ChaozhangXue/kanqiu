<?php

namespace backend\modules\erp\controllers;

use backend\models\search\DriverSearch;
use common\models\Driver;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * DriverController implements the CRUD actions for Driver model.
 */
class DriverController extends Controller
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
     * Lists all Driver models.
     * @return mixed
     */
    public function actionBus()
    {
        $searchModel = new DriverSearch();
        $params = Yii::$app->request->queryParams;
        $params['DriverSearch']['type'] = 1;
        $params['DriverSearch']['status'] = 1;
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'driverType' => 1,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Driver models.
     * @return mixed
     */
    public function actionTransport()
    {
        $searchModel = new DriverSearch();
        $params = Yii::$app->request->queryParams;
        $params['DriverSearch']['type'] = 2;
        $params['DriverSearch']['status'] = 1;
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'driverType' => 2,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Driver model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewBus($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single Driver model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewTransport($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Driver model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEditBus()
    {
        $model = new Driver();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $params = $post['Driver'];
            $params['type'] = 1;
            if (!$params['birth_date']) {
                $model->addError('birth_date', '出生日期有误');
            }
            $params['entry_time'] = strtotime($params['entry_time']);
            if (!$params['entry_time']) {
                $model->addError('entry_time', '入职时间有误');
            }
            if ($model->load(['Driver' => $params]) && $model->save()) {
                return $this->redirect(['view-bus', 'id' => $model->id]);
            }
            $model->load($post);
        }
        $id = Yii::$app->request->get('id');
        if ($id) {
            $model = Driver::findOne($id);
        }
        return $this->render('edit', [
            'driverType' => 1,
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Driver model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEditTransport()
    {
        $model = new Driver();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $params = $post['Driver'];
            $params['type'] = 2;
            $params['entry_time'] = strtotime($params['entry_time']);
            if (!$params['birth_date']) {
                $model->addError('birth_date', '出生日期有误');
            }
            if (!$params['entry_time']) {
                $model->addError('entry_time', '入职时间有误');
            }
            if ($model->load(['Driver' => $params]) && $model->save()) {
                return $this->redirect(['view-transport', 'id' => $model->id]);
            }
            $model->load($post);
        }
        $id = Yii::$app->request->get('id');
        if ($id) {
            $model = Driver::findOne($id);
        }
        return $this->render('edit', [
            'driverType' => 2,
            'model' => $model,
        ]);
    }


    public function actionDeleteBus($id)
    {
        $model = $this->findModel($id);
        $model['status'] = 0;
        $model->save();
        return $this->redirect(['bus']);
    }

    public function actionDeleteTransport($id)
    {
        $model = $this->findModel($id);
        $model['status'] = 0;
        $model->save();
        return $this->redirect(['transport']);
    }

    /**
     * Finds the Driver model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Driver the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Driver::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
