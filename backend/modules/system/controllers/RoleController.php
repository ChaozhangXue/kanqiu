<?php

namespace backend\modules\system\controllers;

use backend\models\Menu;
use backend\models\Role;
use backend\models\RoleMenu;
use backend\models\RoleUser;
use backend\models\search\RoleSearch;
use backend\models\User;
use backend\modules\system\services\RoleService;
use backend\controllers\BaseController;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends BaseController
{
    /** @var RoleService */
    public $roleService;

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
        $this->roleService = new RoleService();
        parent::init();
    }

    /**
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEdit()
    {
        $model = new Role();
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $params = Yii::$app->request->post();
                if (isset($params['id']) && $params['id']) {
                    $model = $this->findModel($params['id']);
                }
                $model->load(['Role' => $params]);
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
        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * 设置用户角色
     * @throws \Exception
     */
    public function actionSetRoleUser()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $params = Yii::$app->request->post();
                $this->roleService->setRoleUser($params);
                $this->success('设置成功');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
        $params = Yii::$app->request->get();
        if (!isset($params['id']) || (int)$params['id'] == 0) {
            throw new \Exception('参数有误');
        }
        $model = Role::find()->where(['id' => $params['id']])->one();
        $rows = RoleUser::find()->where(['role_id' => $params['id']])->asArray()->all();
        $userIdList = array_column($rows, 'user_id');
        $userList = User::find()->all();
        return $this->render('set-role-user', [
            'model' => $model,
            'userList' => $userList,
            'userIdList' => $userIdList,
        ]);
    }


    /**
     * 设置角色权限
     * @throws \Exception
     */
    public function actionSetRoleMenu()
    {

        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $params = Yii::$app->request->post();
                $this->roleService->setRoleMenu($params);
                $this->success('设置成功');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
        $params = Yii::$app->request->get();
        if (!isset($params['id']) || (int)$params['id'] == 0) {
            throw new \Exception('参数有误');
        }
        $model = Role::find()->where(['id' => $params['id']])->one();
        $rows = RoleMenu::find()->where(['role_id' => $params['id']])->asArray()->all();
        $roleMenuIds = array_column($rows, 'menu_id');
        $menuList = Menu::find()->select(['id', 'parent_id as pId', 'name'])
            ->where(['status' => 1])
            ->orderBy('sort desc,create_time asc')->asArray()->all();
        foreach ($menuList as $key => $v) {
            if (!empty($roleMenuIds) && in_array($v['id'], $roleMenuIds)) {
                $menuList[$key]['checked'] = true;
            }
        }
        return $this->render('set-role-menu', [
            'model' => $model,
            'menuList' => $menuList,
        ]);
    }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
