<?php

namespace backend\modules\erp\controllers;

use common\models\OrderMoneyRule;
use common\models\PackageList;
use common\models\ServiceStation;
use common\services\MapService;
use Yii;
use common\models\PackageOrder;
use backend\models\search\PackageOrderSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PackageOrderController implements the CRUD actions for PackageOrder model.
 */
class PackageOrderController extends BaseController
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
     * Lists all PackageOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PackageOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PackageOrder model.
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
     * Creates a new PackageOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \Exception
     */
    public function actionCreate()
    {
        $model = new PackageOrder();

        if ($model->load(Yii::$app->request->post())) {
            if(empty($model->sender_point) || empty($model->receive_point)){
//                $this->error('寄件地址和收件地址需要选择');
                $model->sender_point = '113,111';
                $model->receive_point = '143,121';
            }

            $mapService = new MapService();
            $model->source = 1;
            if(!empty($model->source)){
                if($model->source == 1){
                    //总站
                    $model->status = Yii::$app->params['package_status']['wait_arrive_center'];
                    $model->submit_station_name = '总站';
                    $model->submit_station_id = 0;

                }elseif($model->source == 2){
                    //服务站
                    $model->status = Yii::$app->params['package_status']['wait'];
                    $ret = $mapService->getPosition($model->sender_point);
                    $model->submit_station_id = $ret['nearest_station_id'];
                    $model->submit_station_name = $ret['nearest_station_name'];
                }
            }

            $ret = $mapService->getPosition($model->receive_point);
            $model->receive_station_id = $ret['nearest_station_id'];
            $model->receive_station_name = $ret['nearest_station_name'];
            $receive = ServiceStation::find()->where(['id' => $model->receive_station_id])->one();
            $model->receive_station_phone = $receive->telephone;
            //            处理里程
            $distance = $mapService->getDirection($model->sender_point, $model->receive_point);//返回的是公里数

            $distance_type = 1;
            if ($distance <= 15) {
                $distance_type = 1;
            } elseif ($distance > 15 && $distance <= 50) {
                $distance_type = 2;
            } elseif ($distance > 50) {
                $distance_type = 3;
            }
            $model->distance = $distance_type;

            $max_type = max($model->size,$model->weight,$distance_type);

            $model->total_account = Yii::$app->params['money'][$max_type];
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PackageOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PackageOrder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PackageOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PackageOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PackageOrder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * 服务站把包裹和总站发来的条码绑定  贴一个绑定一个
     * @throws \Exception
     */
    public function actionBindList()
    {
        //双向绑定
        $package_id = Yii::$app->request->post('package_id');//用户的包裹订单id
        $package_list_id = Yii::$app->request->post('package_list_id');//总部生成的快递单号

        $package_list = PackageList::findOne($package_list_id);
        if(empty($package_list)){
            $this->error('包裹码错误');
        }
        if(empty($package_list->package_id)){
            //修改package_order
            $package = PackageOrder::findOne($package_id);
            $package->package_list_id = $package_list_id;
            $package->save();

            $package_list->package_id = $package_id;
            $package_list->save();
            $this->success([], 'success');
        }else{
            $this->error('包裹码已被绑定');
        }
    }

    public function actionMoneyRule()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $post = Yii::$app->request->post();
//                $this->busOrderService->saveMoneyRule($post);
                $this->success();
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
        $list = OrderMoneyRule::find()->asArray()->all();
        $res = [];
        foreach ($list as $v) {
            $res[$v['start'] . '-' . $v['end']][$v['car_type']] = $v['money'];
        }
        return $this->render('money-rule', [
            'list' => $res,
        ]);
    }
}
