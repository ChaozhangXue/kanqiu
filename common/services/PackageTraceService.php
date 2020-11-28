<?php

namespace common\services;

use common\models\PackageTrace;

class PackageTraceService extends BaseService
{
    public $messageService;
    public $model;

    public function __construct()
    {
        $this->messageService = new MessageService();
        $this->model = new PackageTrace();
    }

//    /**
//     * @param $package_id
//     * @param $detail
//     * @throws \Exception
//     */
//    public function add($package_id, $detail){
//        $data = [
//            'package_id' => $package_id,
//            'detail' => $detail,
//        ];
//        $model = new PackageTrace();
//
//        if (!$model->load($data, '')) {
//            throw new \Exception(array_values($model->firstErrors)[0]);
//        }
//        if (!$model->save()) {
//            throw new \Exception(array_values($model->firstErrors)[0]);
//        }
//    }
}