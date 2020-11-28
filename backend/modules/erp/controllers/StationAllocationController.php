<?php

namespace backend\modules\erp\controllers;

use backend\controllers\BaseController;
use common\models\Bus;
use common\models\BusLine;
use common\models\ServiceStation;
use Yii;
use common\models\StationAllocation;
use backend\models\search\StationAllocationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StationAllocationController implements the CRUD actions for StationAllocation model.
 */
class StationAllocationController extends BaseController
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
     * Lists all StationAllocation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StationAllocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StationAllocation model.
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
     * Creates a new StationAllocation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StationAllocation();

        if ($model->load(Yii::$app->request->post())) {



            $bus_line = BusLine::findOne($model->line_id);
            if(empty($bus_line)){
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            $model->line_name = $bus_line->station_name;
            $service_station = ServiceStation::findOne($model->service_id);
            if(empty($service_station)){
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            $model->service_name = $service_station->station_name;
            $model->in_charge_name = $service_station->in_charge_name;
            $model->build_time = $service_station->build_time;
            $model->telphone = $service_station->telephone;
            $model->create_people = Yii::$app->user->identity->realname;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StationAllocation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = new StationAllocation();

        if ($model->load(Yii::$app->request->post())) {
            $model->line_name = BusLine::findOne($model->line_id)->station_name;
            $service_station = ServiceStation::findOne($model->service_id);
            $model->service_name = $service_station->station_name;
            $model->in_charge_name = $service_station->in_charge_name;
            $model->build_time = $service_station->build_time;
            $model->telphone = $service_station->telephone;
            $model->create_people = Yii::$app->user->identity->realname;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model = $this->findModel($id);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing StationAllocation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StationAllocation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StationAllocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StationAllocation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
