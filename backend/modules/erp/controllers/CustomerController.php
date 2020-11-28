<?php

namespace backend\modules\erp\controllers;

use backend\modules\erp\services\CustomerService;
use backend\controllers\BaseController;
use backend\models\search\CustomerSearch;
use common\models\Customer;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends BaseController
{
    /** @var CustomerService $customerService */
    public $customerService;

    public function init()
    {
        $this->customerService = new CustomerService();
        parent::init();
    }

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
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $searchParams = Yii::$app->request->queryParams;
        $searchParams['CustomerSearch']['type'] = 2;
        $dataProvider = $searchModel->search($searchParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionDriver()
    {
        $searchModel = new CustomerSearch();
        $searchParams = Yii::$app->request->queryParams;
        $searchParams['CustomerSearch']['type'] = 1;
        $dataProvider = $searchModel->search($searchParams);
        return $this->render('driver', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionStation()
    {
        $searchModel = new CustomerSearch();
        $searchParams = Yii::$app->request->queryParams;
        $searchParams['CustomerSearch']['type'] = 3;
        $dataProvider = $searchModel->search($searchParams);
        return $this->render('station', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionResetPassword()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $params = Yii::$app->request->post();
                $model = $this->findModel($params['id']);
                $model->password = Yii::$app->security->generatePasswordHash('123456');
                if (!$model->save()) {
                    throw new \Exception(array_values($model->firstErrors)[0]);
                }
                $this->success('修改成功');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    public function actionResetDriverPassword()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $params = Yii::$app->request->post();
                $model = $this->findModel($params['id']);
                $model->password = Yii::$app->security->generatePasswordHash('123456');
                if (!$model->save()) {
                    throw new \Exception(array_values($model->firstErrors)[0]);
                }
                $this->success('修改成功');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \Exception
     */
    public function actionEdit()
    {
        $model = new Customer();
        if (Yii::$app->request->isPost) {
            try {
                $params = Yii::$app->request->post();
                $params['Customer']['type'] = 2;
                $model = $this->customerService->saveCustomer($params);
                return $this->redirect(['view', 'customer_id' => $model->customer_id]);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
        $params = Yii::$app->request->get();
        if (isset($params['customer_id']) && $params['customer_id']) {
            $model = $this->findModel($params['customer_id']);
        }
        return $this->render('edit', [
            'customerType' => 2,
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \Exception
     */
    public function actionEditDriver()
    {
        $model = new Customer();
        if (Yii::$app->request->isPost) {
            try {
                $params = Yii::$app->request->post();
                $params['Customer']['type'] = 1;
                $model = $this->customerService->saveCustomer($params);
                return $this->redirect(['view-driver', 'customer_id' => $model->customer_id]);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
        $params = Yii::$app->request->get();
        if (isset($params['customer_id']) && $params['customer_id']) {
            $model = $this->findModel($params['customer_id']);
        }
        return $this->render('edit', [
            'customerType' => 1,
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \Exception
     */
    public function actionEditStation()
    {
        $model = new Customer();
        if (Yii::$app->request->isPost) {
            try {
                $params = Yii::$app->request->post();
                $params['Customer']['type'] = 3;
                $model = $this->customerService->saveCustomer($params);
                return $this->redirect(['view-station', 'customer_id' => $model->customer_id]);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
        $params = Yii::$app->request->get();
        if (isset($params['customer_id']) && $params['customer_id']) {
            $model = $this->findModel($params['customer_id']);
        }
        return $this->render('edit', [
            'customerType' => 3,
            'model' => $model,
            'show_status' => true,
        ]);
    }

    public function actionView()
    {
        $customer_id = Yii::$app->request->get('customer_id');
        $model = $this->findModel($customer_id);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionViewDriver()
    {
        $customer_id = Yii::$app->request->get('customer_id');
        $model = $this->findModel($customer_id);
        return $this->render('view-driver', [
            'model' => $model
        ]);
    }

    public function actionViewStation()
    {
        $customer_id = Yii::$app->request->get('customer_id');
        $model = $this->findModel($customer_id);
        return $this->render('view-station', [
            'model' => $model
        ]);
    }

    /**
     * @throws \Exception
     */
    public function actionSetStatus()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $data = Yii::$app->request->post();
                $model = $this->findModel($data['id']);
                $model->status = $data['status'];
                if (!$model->save()) {
                    throw new \Exception(array_values($model->firstErrors)[0]);
                }
                $this->success('修改成功');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function actionSetDriverStatus()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $data = Yii::$app->request->post();
                $model = $this->findModel($data['id']);
                $model->status = $data['status'];
                if (!$model->save()) {
                    throw new \Exception(array_values($model->firstErrors)[0]);
                }
                $this->success('修改成功');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
