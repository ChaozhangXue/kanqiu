<?php

namespace api\controllers;

use common\models\BaseModel;
use common\models\Customer;
use common\services\CustomerService;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Base controller
 */
class BaseController extends \yii\rest\Controller
{
    public $modelClass = 'api-model';

    public $user_primary = 'customer_id';

    public $filter = false;

    public function behaviors()
    {
        if ($this->filter) {
            $requestParams = \Yii::$app->request->bodyParams;

            if (empty($requestParams)) {
                $requestParams = \Yii::$app->request->queryParams;
                $requestParams['filter'][$this->user_primary] = \Yii::$app->user->id;
                \Yii::$app->request->setQueryParams($requestParams);
            } else {
                $requestParams['filter'][$this->user_primary] = \Yii::$app->user->id;
                \Yii::$app->request->setBodyParams($requestParams);
            }
        }
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        // 制定允许其他域名访问
        header("Access-Control-Allow-Origin:*");
        // 响应类型
        header('Access-Control-Allow-Methods:*');
        header('Access-Control-Allow-Headers:x-requested-with, content-type');
        return $behaviors;
    }

    public function beforeAction($action)
    {
        if (YII_DEBUG) {
            $header = \Yii::$app->request->headers->toArray();
            $data = array_merge(\Yii::$app->request->post(), \Yii::$app->request->get());
            file_put_contents(\Yii::$app->runtimePath . DIRECTORY_SEPARATOR . 'xc.log', date('Y-m-d H:i:s ') . \Yii::$app->request->url . ' ' . 'token:' . (isset($header['token'][0]) ? $header['token'][0] : '') . ' ' . json_encode($data) . PHP_EOL, FILE_APPEND);
        }
        try {
            //todo 鉴权
//            $this->getUserByToken();
        } catch (\Exception $e) {
            $this->error($e->getMessage(), $e->getCode());
        }
        return parent::beforeAction($action);
    }

    public function success($data = [], $msg = 'success', $code = 200)
    {
        header('status:200 OK');
        $res['code'] = (string)$code;
        $res['message'] = $msg;
        $res['data'] = empty($data) ? null : $data;
        die(json_encode($res));
    }

    public function error($msg = 'failed', $code = 400, $data = [])
    {
        header('status:200 OK');
        $res['code'] = (string)$code;
        $res['message'] = $msg;
        $res['data'] = empty($data) ? null : $data;
        die(json_encode($res));
    }

    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        if ($exception === null) {
            $exception = new NotFoundHttpException(\Yii::t('yii', 'Page not found.'), 404);
        }
        $this->error($exception->getMessage(), 404);
    }

    protected function getUserByToken()
    {
        $moduleController = $this->module->id . '/' . $this->id;
        $url = $moduleController . '/' . $this->action->id;

        $errorUrl = \Yii::$app->id . '/' . \Yii::$app->errorHandler->errorAction;
        if ($url == $errorUrl) {
            return;
        }
        $actionWhites = \Yii::$app->params['action_white_list'];
        $actionWhiteList = [\Yii::$app->id . '/' . \Yii::$app->errorHandler->errorAction];
        foreach ($actionWhites as $controller => $actionList) {
            foreach ($actionList as $action) {
                if ($action == '*') {
                    $actionWhiteList[] = $controller;
                }else{
                    $actionWhiteList[] = $controller . '/' . $action;
                }
            }
        }
        $header = \Yii::$app->request->headers->toArray();
        $customer = null;
        $customerService = new CustomerService();
        if (isset($header['token']) && $header['token'] != '') {
            $customer = Customer::find()->where(['token' => $header['token'][0]])->one();
        } else if (isset($header['app_code']) && $header['app_code'] != '') {
            $openId = $customerService->getOpenIdByCode($header['app_code']);
            $customer = Customer::find()->where(['openid' => $openId])->one();
        }
        if (!in_array($url, $actionWhiteList) && !in_array($moduleController, $actionWhiteList) && !$customer) {
            throw new \Exception('未登录', 999);
        }
        if ($customer) {
            \Yii::$app->user->setIdentity($customer);
        }
    }

    /**
     * 文件上传处理
     * @param $file
     * @param bool $is_image
     * @return array
     * @throws \Exception
     */
    public function parseFile($file, $path = '/')
    {
        $ext_arr = ['gif', 'jpg', 'jpeg', 'png', 'bmp'];
        $fileList = $file['name'];
        if (is_string($file['name'])) {
            $fileList = [$file['name']];
        }
        $tmpList = $file['tmp_name'];
        if (is_string($file['tmp_name'])) {
            $tmpList = [$file['tmp_name']];
        }
        $res = [];
        $path = trim($path, '/') != '' ? trim($path, '/') . '/' : '';
        foreach ($fileList as $i => $f_name) {
            if (!$f_name) {
                continue;
            }
            $arr = explode('.', $f_name);
            $ext = end($arr);
            if (!in_array($ext, $ext_arr)) {
                throw new \Exception('不允许的文件类型,只支持' . implode('/', $ext_arr));
            }
            $filePath = \Yii::$app->basePath . '/web/upload/' . $path;
            if (!file_exists($filePath)) {
                mkdir($filePath, 0755, true);
            }
            $filename = 'upload/' . $path . md5_file($tmpList[$i]) . '.' . $ext;
            if (!file_exists(\Yii::$app->basePath . '/web/' . $filename)) {
                if (@!move_uploaded_file($tmpList[$i], \Yii::$app->basePath . '/web/' . $filename)) {
                    throw new \Exception('文件保存失败');
                }
            }
            $res[$i] =\Yii::$app->request->getHostInfo() . \Yii::$app->request->getBaseUrl() . '/' . $filename;
        }
        return $res;
    }

    /**
     * @param $key
     * @param string $path
     * @return string
     * @throws \Exception
     */
    public function parseFileOrUrl($key, $path = '/')
    {
        $res = [];
        //如果是common目录下的文件需要移动到对应目录
        if ($urlList = \Yii::$app->request->post($key)) {
            if (!is_array($urlList)) {
                $urlList = explode(',', $urlList);
            }
            $baseUrl = \Yii::$app->request->getHostInfo() . \Yii::$app->request->getBaseUrl();
            foreach ($urlList as $i => $url) {
                if (strpos($url, $baseUrl . '/upload/common') === 0) {
                    $filename = str_replace($baseUrl . '/upload/common/', '', $url);
                    $oldFilePath = \Yii::$app->basePath . '/web/upload/common/';
                    $oldFilename = $oldFilePath . $filename;
                    $newFilePath = \Yii::$app->basePath . '/web/upload/' . $path;
                    $newFilename = $newFilePath . $filename;
                    if (!file_exists($newFilePath)) {
                        mkdir($newFilePath, 0755, true);
                    }
                    if (file_exists($oldFilename)) {
                        copy($oldFilename, $newFilename);
                        unlink($oldFilename);
                    }
                    $res[$i] = $baseUrl . '/upload/' . $path . $filename;
                } else {
                    $res[$i] = $url;
                }
            }
        }
        $path = trim($path, '/') != '' ? trim($path, '/') . '/' : '';
        if (!empty($_FILES[$key])) {
            $files = $this->parseFile($_FILES[$key], $path);
            foreach ($files as $key => $new) {
                $res[$key] = $new;
            }
            ksort($res);
        }
        return implode(',', $res);
    }

    /**
     * 获取分页
     * @return array
     */
    public function getLimit()
    {
        $get = \Yii::$app->request->get();
        $page = 1;
        if (isset($get['page']) && $get['page'] != '') {
            $page = $get['page'];
        }
        $perPage = 10;
        if (isset($get['per-page']) && $get['per-page'] != '') {
            $perPage = $get['per-page'];
        }
        return [($page - 1) * $perPage, $perPage];
    }

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];


    /**
     * 根据起点坐标和终点坐标测距离
     * @param  [array]   $from    [起点坐标(经纬度),例如:array(118.012951,36.810024)]
     * @param  [array]   $to    [终点坐标(经纬度)]
     * @param  [bool]    $km        是否以公里为单位 false:米 true:公里(千米)
     * @param  [int]     $decimal   精度 保留小数位数
     * @return [string]  距离数值
     */
    public function getDistance($from, $to, $km = true, $decimal = 2)
    {
        sort($from);
        sort($to);
        $EARTH_RADIUS = 6370.996; // 地球半径系数

        $distance = $EARTH_RADIUS * 2 * asin(sqrt(pow(sin(($from[0] * pi() / 180 - $to[0] * pi() / 180) / 2), 2) + cos($from[0] * pi() / 180) * cos($to[0] * pi() / 180) * pow(sin(($from[1] * pi() / 180 - $to[1] * pi() / 180) / 2), 2))) * 1000;

        if ($km) {
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }

    /**
     * 生成订单id或者包裹id
     */
    public function generateId()
    {
        return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }


    /**
     * @param $action
     * @param BaseModel $model
     * @param array $params
     */
    public function checkModel($action, $model = null, $params = [])
    {
        try {
            if (!$model->hasAttribute($this->user_primary)) {
                throw new \Exception('配置有误');
            }
            if ($model[$this->user_primary] != \Yii::$app->user->id) {
                throw new \Exception('数据有误，请刷新重试');
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
