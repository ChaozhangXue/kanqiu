<?php

namespace backend\modules\erp\controllers;

use common\models\BusStation;
use Yii;
use common\models\BusLine;
use backend\models\search\BusLineSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BusLineController implements the CRUD actions for BusLine model.
 */
class BusLineController extends BaseController
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
     * Lists all BusLine models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BusLineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BusLine model.
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
     * Creates a new BusLine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BusLine();

        if ($model->load(Yii::$app->request->post())) {
            $station = Yii::$app->request->post('BusLine')['station'];

            if(!empty($station)){
                $station = array_unique(array_filter($station));

                $model->station_list = implode(',', $station);
                $model->station_num = count($station);
                $model->start_point = BusStation::find()->where(['id' => $station[0]])->one()->station_name;
                $model->end_point = BusStation::find()->where(['id' => $station[$model->station_num - 1]])->one()->station_name;
            }else{
                $model->station_num = 0;
                $model->start_point = '';
                $model->end_point = '';
                $model->station_list = '';
            }

            $model->create_people = Yii::$app->user->identity->realname;

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $stations = BusStation::find()->indexBy('id')->asArray()->all();

        return $this->render('create', [
            'model' => $model,
            'stations' => $stations,
        ]);
    }

    /**
     * Creates a new BusLine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionXiaCreate()
//    {
//        $model = new BusLine();
//
//        if ($model->load(Yii::$app->request->post())) {
//            $station = Yii::$app->request->post('BusLine')['station'];
//
//            if(!empty($station)){
//                $station = array_unique(array_filter($station));
//
//                $model->station_list = implode(',', $station);
//                $model->station_num = count($station);
//                $model->start_point = BusStation::find()->where(['id' => $station[0]])->one()->station_name;
//                $model->end_point = BusStation::find()->where(['id' => $station[$model->station_num - 1]])->one()->station_name;
//            }else{
//                $model->station_num = 0;
//                $model->start_point = '';
//                $model->end_point = '';
//                $model->station_list = '';
//            }
//
//            $model->create_people = Yii::$app->user->identity->realname;
//
//            $model->save();
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
////        获取上行的线路
//        $stations = BusStation::find()->where(['type' => 1])->indexBy('id')->asArray()->all();
//
//        return $this->render('create', [
//            'model' => $model,
//            'type' => 2,
//            'stations' => $stations,
//        ]);
//    }

    /**
     * Updates an existing BusLine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $station = Yii::$app->request->post('BusLine')['station'];

            if(!empty($station)){
                $station = array_unique(array_filter($station));

                $model->station_list = implode(',', $station);
                $model->station_num = count($station);
                $model->start_point = BusStation::find()->where(['id' => $station[0]])->one()->station_name;
                $model->end_point = BusStation::find()->where(['id' => $station[$model->station_num - 1]])->one()->station_name;
            }else{
                $model->station_num = 0;
                $model->start_point = '';
                $model->end_point = '';
                $model->station_list = '';
            }

            $model->create_people = Yii::$app->user->identity->realname;

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $stations = BusStation::find()->indexBy('id')->asArray()->all();

        return $this->render('update', [
            'model' => $model,
            'type' => $model->type,
            'stations' => $stations,
        ]);
    }

    /**
     * Deletes an existing BusLine model.
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
     * Finds the BusLine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusLine the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BusLine::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
