<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CustomerVerifySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/** @var \common\services\BaseService $service $service */
$service = new \common\services\BaseService();
?>
<div class="customer-verify-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view table-responsive text-nowrap', 'id' => 'list-table'],
        'filterModel' => $searchModel,
        'columns' => [
            'verify_id',
            [
                'attribute' => 'customer_id',
                'label' => '昵称',
                'value' => function ($model) {
                    $customer = \common\models\Customer::find()->where(['customer_id' => $model->customer_id])->one();
                    return $customer['nickname'];
                },
            ],
            'mobile',
            [
                'attribute' => 'gender',
                'filter' => ['1' => '男', '2' => '女'],
                'value' => function ($model) {
                    return $model['gender'] == '1' ? '男' : '女';
                },
            ],
            [
                'attribute' => 'front_photo',
                'format' => 'html',
                'value' => function ($model) {
                    return \yii\helpers\Html::img($model['front_photo'], ['style' => 'width:80px;height:80px']);
                },
            ],
            [
                'attribute' => 'back_photo',
                'format' => 'html',
                'value' => function ($model) {
                    return \yii\helpers\Html::img($model['front_photo'], ['style' => 'width:80px;height:80px']);
                },
            ],
            'realname',
            'idcard',
            //'update_time:datetime',
            [
                'attribute' => 'verify_status',
                'filter' => Yii::$app->params['verify_status_list'],
                'value' => function ($model) {
                    return Yii::$app->params['verify_status_list'][$model->verify_status];
                },
            ],
            [
                'attribute' => 'create_time',
                'label' => '创建时间',
                'value' => function ($model) {
                    return $model->create_time ? date('Y-m-d H:i:s', $model->create_time) : '';
                },
            ]
        ],
    ]); ?>


</div>
