<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\StationVerifySearch */
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
                'attribute' => 'country',
                'label' => '所属乡镇',
                'value' => 'station.country'
            ],
            [
                'attribute' => 'station_name',
                'label' => '服务站点',
                'value' => 'station.station_name'
            ],
            [
                'attribute' => 'in_charge_name',
                'label' => '负责人',
                'value' => 'station.in_charge_name'
            ],
            [
                'attribute' => 'people_num',
                'label' => '站点人数',
                'value' => 'station.people_num'
            ],
            [
                'attribute' => 'telephone',
                'label' => '联系电话',
                'value' => 'station.telephone'
            ],
            [
                'attribute' => 'build_time',
                'value' => 'station.build_time',
                'label' => '成立时间',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'backup',
                'value' => 'station.backup',
                'label' => '备注'
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
                'format' => ['date', 'php:Y-m-d H:i:s']
            ]
        ],
    ]); ?>


</div>
