<?php

namespace backend\modules\erp\controllers;

use backend\controllers\BaseController;
use backend\models\search\DriverBillSearch;
use common\models\DriverBill;
use common\services\BillService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * DriverBillController implements the CRUD actions for DriverBill model.
 */
class DriverBillController extends BaseController
{
    /** @var BillService $billService */
    public $billService;

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

    public function init()
    {
        $this->billService = new BillService();
        parent::init();
    }

    /**
     * Lists all DriverBill models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DriverBillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCheck()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $post = Yii::$app->request->post();
                if (!isset($post['id']) || $post['id'] == '') {
                    throw new \Exception('参数有误');
                }
                $bill = $this->findModel($post['id']);
                if ($bill['status'] == 2) {
                    throw new \Exception('账单状态有误，请刷新界面');
                }
                $bill['status'] = 2;
                $bill['verify_time'] = time();
                $bill->save();
                $this->billService->decreaseDriverAccount($bill['driver_id'], $bill['commission']);
                $this->success('审核成功');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * Displays a single DriverBill model.
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
     * Finds the DriverBill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DriverBill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DriverBill::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
