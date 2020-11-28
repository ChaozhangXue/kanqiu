<?php

namespace backend\modules\erp\controllers;

use Yii;
use common\models\PackageList;
use backend\models\search\PackageListSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PackageListController implements the CRUD actions for PackageList model.
 */
class PackageListController extends Controller
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
     * Lists all PackageList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PackageListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PackageList model.
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

    public function actionRePrint()
    {
        $id = Yii::$app->request->post('id');

        //1.调用打印机
        $package_list = PackageList::find()->where(['id' => $id])->one();
//        $package_list->is_print = 1;
//        $package_list->save();

        $list = [$package_list->id];
        return json_encode(['code' => 0, 'list' => $list]);
    }

    public function actionGetUnPrint()
    {
        $list = [];

        //1.调用打印机
        $package_list = PackageList::find()->where(['is_print' => 0])->all();
        $package_order = PackageList::find()->where(['is_print' => 0])->asArray()->all();

        if(!empty($package_list)){
            foreach ($package_list as $val){
                $val->is_print = 1;
                $val->save();
            }

            $list = array_column($package_order, 'id');
        }

        $list = implode(',', $list);
        return json_encode(['code' => 0, 'list' => $list]);
    }

    /**
     * Creates a new PackageList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        //批量创建
        if ((Yii::$app->request->isPost)) {
//            print_r(1);die;
            $num = Yii::$app->request->post('PackageList')['package_id'] ? Yii::$app->request->post('PackageList')['package_id'] : 0;
            $rows = [];
            if ($num != 0) {
                for ($i = 0; $i < $num; $i++) {
                    $rows[] = [
                        'package_id' => null,
                        'is_print' => 0,
                    ];
                }

                Yii::$app->db->createCommand()->batchInsert('xinchang.package_list', ['package_id', 'is_print'], $rows)->execute();
            }

            $searchModel = new PackageListSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->redirect(['index',  'searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
        } else {
            $model = new PackageList();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PackageList model.
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
     * Deletes an existing PackageList model.
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
     * Finds the PackageList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PackageList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PackageList::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
