<?php

namespace common\services;

use common\models\ServiceStation;

class ServiceStationService extends BaseService
{
    public $model;

    public function __construct()
    {
        $this->model = new ServiceStation();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function add($data)
    {
        if (!$this->model->load($data, '')) {
            throw new \Exception(array_values($this->model->firstErrors)[0]);
        }
        if (!$this->model->save()) {
            throw new \Exception(array_values($this->model->firstErrors)[0]);
        }
    }

    public function update($id, $data)
    {
        $model = ServiceStation::findOne([
            'id' => $id
        ]);

        foreach ($data as $key => $val) {
            $model->$key = $val;
        }

        if (!$model->save()) {
            throw new \Exception(array_values($model->firstErrors)[0]);
        }
        return $model;
    }


}