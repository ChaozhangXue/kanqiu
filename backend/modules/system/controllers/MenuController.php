<?php

namespace backend\modules\system\controllers;

use backend\models\Menu;
use backend\models\search\MenuSearch;
use backend\modules\system\services\MenuService;
use backend\controllers\BaseController;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends BaseController
{
    /** @var MenuService */
    public $menuService;

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
        $this->menuService = new MenuService();
        parent::init();
    }

    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionEdit()
    {
        $model = new Menu();
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $params = Yii::$app->request->post();
                if (isset($params['id']) && $params['id']) {
                    $model = $this->findModel($params['id']);
                }
                $model->load(['Menu' => $params]);
                if (!$model->save()) {
                    throw new \Exception(array_values($model->firstErrors)[0]);
                }
                $this->success('保存成功');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
        $params = Yii::$app->request->get();
        if (isset($params['id']) && $params['id']) {
            $model = $this->findModel($params['id']);
        } else {
            $params['id'] = 0;
        }
        $childList = $this->menuService->getChildMenus();
        $methodList = $this->menuService->getAllMethodList($params['id']);
        return $this->render('edit', [
            'model' => $model,
            'childList' => $childList,
            'methodList' => $methodList,
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
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
