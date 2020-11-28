<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/7/24
 * Time: 6:13 PM
 */

namespace backend\controllers;

use backend\models\Menu;
use backend\models\RoleMenu;
use backend\models\RoleUser;
use common\services\MessageService;
use yii\base\ErrorException;

class BaseController extends \yii\web\Controller
{
    /** @var MessageService */
    public $messageService;

    public function init()
    {
        $this->messageService=new MessageService();
        parent::init();
    }

    public function success($message = '操作成功', $data = [])
    {
        if (\Yii::$app->request->isAjax) {
            $res = [
                'code' => 0,
                'message' => $message,
                'data' => $data,
            ];
            echo json_encode($res);
            die;
        }
    }

    public function error($message = '操作失败', $data = [])
    {
        if (\Yii::$app->request->isAjax) {
            $res = [
                'code' => 1,
                'message' => $message,
                'data' => $data,
            ];
            echo json_encode($res);
            die;
        } else {
            throw new \Exception($message);
        }
    }

    public function parseFile($file, $path = '', $is_image = true)
    {
        $ext_arr = [];
        if ($is_image) {
            $ext_arr = ['gif', 'jpg', 'jpeg', 'png', 'bmp'];
        }
        $fileList = $file['name'];
        if (is_string($file['name'])) {
            $fileList = [$file['name']];
        }
        $tmpList = $file['tmp_name'];
        if (is_string($file['tmp_name'])) {
            $tmpList = [$file['tmp_name']];
        }
        $res = [];
        foreach ($fileList as $i => $f_name) {
            if (!$f_name) {
                continue;
            }
            $arr = explode('.', $f_name);
            $ext = end($arr);
            if (!in_array($ext, $ext_arr)) {
                throw new \Exception('不允许的文件类型,只支持' . implode('/', $ext_arr));
            }
            $filePath = \Yii::$app->basePath . '/web/upload/' . ($path != '' ? $path . '/' : '');
            if (!file_exists($filePath)) {
                mkdir($filePath, 0755, true);
            }
            $filename = '/upload/' . ($path != '' ? $path . '/' : '') . md5_file($tmpList[$i]) . '.' . $ext;
            if (!file_exists(\Yii::$app->basePath . '/web' . $filename)) {
                if (@!move_uploaded_file($tmpList[$i], \Yii::$app->basePath . '/web' . $filename)) {
                    throw new \Exception('文件保存失败');
                }
            }
            $res[] = \Yii::$app->request->getHostInfo() . \Yii::$app->request->getBaseUrl() . $filename;
        }
        return implode(',', $res);
    }

    public function beforeAction($action)
    {
        $this->validateUserGrant();
        return parent::beforeAction($action);
    }

    /**
     * @return void|\yii\web\Response
     * @throws ErrorException
     * @throws \Exception
     */
    private function validateUserGrant()
    {
        try {
            if (\Yii::$app->user->isGuest && ($this->module->id != \Yii::$app->id || $this->id != 'site' || $this->action->id != 'login')) {
                return $this->redirect(['/site/login']);
            }
            $uri = '/' . $this->module->id . '/' . $this->id . '/' . $this->action->id;
            if ($this->module->id == \Yii::$app->id) {
                return;
            }
            $whites = \Yii::$app->params['action_white_list'];
            $whiteList = [];
            foreach ($whites as $controller => $actions) {
                foreach ($actions as $action) {
                    $whiteList[] = strtolower($controller . '/' . $action);
                }
            }
            if (in_array($uri, $whiteList)) {
                return;
            }
            $menu = Menu::find()->where(['url' => $uri])->one();
            if (!$menu) {
                throw new \Exception($uri . '该地址不在权限中');
            }
            $user = \Yii::$app->user->identity;
            if ($user['identity'] == 1) {
                return;
            }
            $access = RoleMenu::find()->alias('a')
                ->leftJoin(RoleUser::tableName() . ' as b', 'a.role_id = b.role_id')
                ->where(['a.menu_id' => $menu['id'], 'b.user_id' => $user['id']])
                ->one();
            if (!$access) {

                throw new \Exception('您暂无该权限');
            }
        } catch (\Exception $e) {
            if (\Yii::$app->request->isAjax) {
                $this->error($e->getMessage());
            }
            throw new ErrorException($e->getMessage());
        }
    }
}