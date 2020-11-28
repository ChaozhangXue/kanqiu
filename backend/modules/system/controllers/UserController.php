<?php

namespace backend\modules\system\controllers;

use backend\controllers\BaseController;
use backend\models\search\UserSearch;
use backend\models\User;
use backend\modules\system\services\UserService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
{
    /** @var UserService */
    public $userService;

    public function init()
    {
        $this->userService = new UserService();
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @throws \Exception
     */
    public function actionEdit()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $params = Yii::$app->request->post();
                if (!empty($_FILES['file'])) {
                    $file = $_FILES['file'];
                    $params['avatar'] = $this->parseFile($file);
                }
                $this->userService->saveUser($params);
                $this->success('保存成功');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
        $model = new User();
        $params = Yii::$app->request->get();
        if (isset($params['id']) && $params['id']) {
            $model = $this->findModel($params['id']);
        }
        return $this->render('edit', [
            'model' => $model
        ]);
    }

    /**　
     * 账号启用\禁用
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

    public function actionProfile()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $params = Yii::$app->request->post();
                if (!empty($_FILES['file'])) {
                    $params['avatar'] = $this->parseFile($_FILES['file']);
                }
                $user = Yii::$app->user->identity;
                $params['id'] = $user['id'];
                $params['username'] = $user['username'];
                $params['dept'] = $user['dept'];
                $params['job_position'] = $user['job_position'];
                $this->userService->saveUser($params);
                $this->success('修改成功');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }

        }
        return $this->render('profile', [
            'model' => Yii::$app->user->identity
        ]);
    }

    public function actionChangePassword()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $params = Yii::$app->request->post();
                if ($params['newPassword'] != $params['rePassword']) {
                    throw new \Exception('新密码与验证密码不一致～');
                }
                /** @var User $user */
                $user = Yii::$app->user->identity;
                if (!\Yii::$app->security->validatePassword($params['password'], $user['password_hash'])) {
                    throw new \Exception('当前登录密码有误～');
                }
                $user->setPassword($params['password']);
                $user->generateAuthKey();
                if (!$user->save()) {
                    throw new \Exception(array_values($user->firstErrors)[0]);
                }
                Yii::$app->user->logout();
                $this->success('修改成功');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    public function actionChangeProfile()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $params = Yii::$app->request->post();
                if (!empty($_FILES['file'])) {
                    $file = $_FILES['file'];
                    $params['avatar'] = $this->parseFile($file);
                }
                $user = Yii::$app->user->identity;
                $params['id'] = $user['user_id'];
                $params['username'] = $user['username'];
                $this->userService->saveUser($params);
                $this->success('修改成功');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    public function actionResetPassword()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            try {
                $params = Yii::$app->request->post();
                $user = $this->findModel($params['id']);
                $user->setPassword('123456');
                $user->generateAuthKey();
                if (!$user->save()) {
                    throw new \Exception(array_values($user->firstErrors)[0]);
                }
                $this->success('修改成功');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
