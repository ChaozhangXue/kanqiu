<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\BusLine;
use Yii;

class BusLineController extends BaseController
{
    public $modelClass = 'common\models\BusLine';

    public function actionSearch(){
        $word = Yii::$app->request->get('word');
        if(!empty($word)){
            $bus_line = BusLine::find()->where(['like', 'station_name', $word])->asArray()->all();
            if(!empty($bus_line)){
                $this->success($bus_line);
            }
        }

        $this->success();
    }
}
