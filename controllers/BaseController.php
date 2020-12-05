<?php

namespace app\controllers;

use app\models\Userinfo;
use Yii;
use yii\web\Controller;


class BaseController extends Controller
{
    public $no_validate = [
	'user/login',
	'file/upload',
	'user/get-by-name',
	'attendance/get-username',
	];
    public $user_info;
//    public $data_model;
//    public $model_keys;
//功能权限 1: 查看权限 2编辑权限 3:保存权限 4新增权限 5 查询权限 6 停用权限
    public $function = [
        1 => ['all', 'one','export'],
        2 => ['update','export'],
        3 => ['update', 'add','export'],
        4 => ['add','export'],
        5 => ['search','export'],
        6 => ['enable','disable'],
    ];

//用户权限 (1 应付管理 2应收管理 3 出纳管理 4 数据管理 5考情管理 6账户管理)
    public $menu = [
        1=>['supplier','supplier-pay-record','resource'],
        2=>['client','client-pay-record','client-resource'],
        3=>['imprest','baoxiao','public-money'],
        4=>['sale-data','customer-data','purchase'],
        5=>['attendance','banci','banci-order'],
        6=>['user'],
    ];

    public function export($title_ary, $data, $filename = 'file', $sheet_name = 'sheet1')
    {
        $filename = $filename . '-' . date('Y-m-d-h-i-s') . '.csv';
        $file_path = '/var/www/html/financial/web/export/' . $filename;

        $str = implode(',', $title_ary) . "\n";
        $str = mb_convert_encoding($str, 'gb2312', 'utf-8');
        file_put_contents($file_path, $str, FILE_APPEND);
        foreach ($data as $val) {
            $str = implode(',', $val) . "\n";
            $str = mb_convert_encoding($str, 'gb2312', 'utf-8');

            file_put_contents($file_path, $str, FILE_APPEND);
        }
//header("Content-type:application/vnd.ms-excel;charset=UTF-8");
        echo json_encode(['status' => 0, 'url' => 'http://backend.delcache.com/export/' . $filename]);die;
    }


    public function exportExc($title_ary, $data, $filename = 'file', $sheet_name = 'sheet1')
    {
        $objPHPExcel = new \PHPExcel();
        //设置文件的一些属性，在xls文件——>属性——>详细信息里可以看到这些值，xml表格里是没有这些值的
        $objPHPExcel
            ->getProperties()//获得文件属性对象，给下文提供设置资源
            ->setCreator("MaartenBalliauw")//设置文件的创建者
            ->setLastModifiedBy("MaartenBalliauw")//设置最后修改者
            ->setTitle("Office2007 XLSX Test Document")//设置标题
            ->setSubject("Office2007 XLSX Test Document")//设置主题
            ->setDescription("Test document for Office2007 XLSX, generated using PHP classes.")//设置备注
            ->setKeywords("office 2007 openxmlphp")//设置标记
            ->setCategory("Test resultfile");                //设置类别

        $key_ary = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        foreach ($title_ary as $key => $val) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($key_ary[$key] . '1', $val);
        }
        //得到当前活动的表,注意下文教程中会经常用到$objActSheet
        $objActSheet = $objPHPExcel->getActiveSheet();
        // 位置bbb *为下文代码位置提供锚
        //给当前活动的表设置名称
        $objActSheet->setTitle($sheet_name);

        $i = 2;
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                foreach ($value as $k => $val) {
                    $objPHPExcel->getActiveSheet()->setCellValue($key_ary[$k] . $i, $val);
                }
                $i++;
            }
        }
        $filename = $filename . date('Y-m-d') . '.xls';

        //我们将要做的是
        //1,直接生成一个文件
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('myexchel.xlsx');
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="' . $filename . '"');
        header('Cache-Control:max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function beforeAction($action)
    {
        $this->log('start');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: token,Origin, X-Requested-With, Content-Type, Accept");
        if (!in_array($this->module->requestedRoute, $this->no_validate)) {
            $token = Yii::$app->request->get('token', 0);
            $user = Userinfo::find()->where(['token' => $token])->asArray()->one();
            if (!empty($user)) {
                $this->user_info = $user;
                //拿到用户信息后 需要校验用户权限
                if($this->validateAuth($this->module->requestedRoute, $user['user_auth'], $user['function_auth']) == false){
                    $this->error('Permission Denied.', -1);
                }

                return true;
            } else {
                $this->error('user not exit。');
            }
        }
        return true;
        //$this->error('账户信息不存在');
    }

    public function validateAuth($url, $user_auth, $function_auth)
    {
        if(empty($user_auth) || empty($function_auth)){
            return false;
        }

        list($controller, $action) = explode('/', $url);
        if(!in_array($action,['add','one','search','update'])){
            return true;
        }
//        function 对应的是action
        $function_id = explode(',', $function_auth);
        $function = [];
        foreach ($function_id as $val){
            $function = array_merge($function, $this->function[$val]);
        }
        if(!in_array($action, $function)){
            return false;
        }

        //menu 对应的是控制器
        $menu_id = explode(',', $user_auth);
        $menu = [];
        foreach ($menu_id as $val){
            $menu = array_merge($menu, $this->menu[$val]);
        }
        if(!in_array($controller, $menu)){
            return false;
        }

        return true;

    }

    public function success($data = [], $msg = 'success')
    {
        $ret = [
            'r' => 0,
            'data' => $data,
            'msg' => $msg,
        ];
        $this->log('success: ' . json_encode($ret));
        echo json_encode($ret);
        die;
    }

    public function error($msg = 'failed', $code = 1, $data = [])
    {
        $ret = [
            'r' => $code,
            'data' => $data,
            'msg' => $msg,
        ];
        $this->log('error: ' . json_encode($ret));
        echo json_encode($ret);
        die;
    }


    public function log($msg = '')
    {
        $str = date('Y-m-d H:i:s') . '  ' . $this->module->requestedRoute . "  params  " . json_encode(Yii::$app->request->post(), true) . '   ' . $msg . "\n";
        file_put_contents('/data/logs/financial/' . date("Y-m-d") . '.log', $str, FILE_APPEND);
    }
}

