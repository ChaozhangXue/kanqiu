<?php

namespace backend\modules\erp\controllers;

use backend\controllers\BaseController;
use backend\models\search\CustomerVerifySearch;
use backend\models\search\StationVerifySearch;
use Yii;

/**
 * CustomerVerifyController implements the CRUD actions for CustomerVerify model.
 */
class VerifyController extends BaseController
{

    /**
     * 会员认证
     * Lists all CustomerVerify models.
     * @return mixed
     */
    public function actionCustomer()
    {
        $searchModel = new CustomerVerifySearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);
        return $this->render('customer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 站点认证
     * Lists all CustomerVerify models.
     * @return mixed
     */
    public function actionStation()
    {
        $searchModel = new StationVerifySearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);

        return $this->render('station', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
