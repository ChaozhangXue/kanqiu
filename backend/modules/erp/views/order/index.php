<?php

use common\services\OrderService;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '包裹订单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view table-responsive text-nowrap', 'id' => 'list-table'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'type',
                'label' => '订单类型',
                'filter' => Yii::$app->params['order_type'],
                'value' => function ($model) {
                    if (!empty($model->type)) {
                        return Yii::$app->params['order_type'][$model->type];
                    } else {
                        return '';
                    }
                },
            ],
//            'package_id_list:ntext',
//            'receive_station_id',
            'receive_station_name',
//            'send_station_id',
            'send_station_name',
//            'driver_id',
            'driver_name',
            //'driver_phone',
            [
                'attribute' => 'driver_accept_type',
                'label' => '接单状态',
                'filter' => Yii::$app->params['driver_accept_type'],
                'value' => function ($model) {
                    if (!empty($model->driver_accept_type)) {
                        return Yii::$app->params['driver_accept_type'][$model->driver_accept_type];
                    } else {
                        return '';
                    }
                },
            ],
            //'bus_line',
            //'card',
            'deliver_time',
            'station_time',
            'receiver_time',
            'package_num',
            'total_account',
            //'yongjin',
            //'detail:ntext',
            [
                'attribute' => 'status',
                'label' => '订单状态',
                'filter' => Yii::$app->params['order_status_list'],
                'value' => function ($model) {
                    return Yii::$app->params['order_status_list'][$model->status];
                },
            ],
            [
                "attribute" => "package_id_list",
                'value' => function ($model) {
                    return $model->package_id_list;
                },
            ],
            [
                'label' => '操作',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = [];
                    $url = Yii::$app->urlManager->createUrl(['/erp/order/view', 'id' => $model['id']]);
                    $html [] = Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, ['data-title' => '查看']);


                    if (in_array($model['status'], [
                        Yii::$app->params['order_status']['pending'],
                        Yii::$app->params['order_status']['wait_send'],
                        Yii::$app->params['order_status']['wait_bind'],
                    ])) {
                        $html [] = Html::a('<i class="glyphicon glyphicon-hand-right"></i>', 'javascript:void(0)', [
                            'class' => 'assign-btn',
                            'data-title' => $model['status'] == Yii::$app->params['order_status']['pending'] ? '指派' : '转派',
                            'data-id' => $model['id'],
                            'data-url' => Yii::$app->urlManager->createUrl(['/erp/order/assign']),
                        ]);
                    }

                    if (in_array($model['status'], [
                        Yii::$app->params['order_status']['pending'],
                        Yii::$app->params['order_status']['wait_send'],
                        Yii::$app->params['order_status']['wait_bind'],
                    ])) {
                        $html [] = Html::a('<i class="iconfont icon-QR-code"></i>', 'javascript:void(0)', [
                            'class' => 'qr-btn',
                            'data-no' => $model['id'],
                            'data-num' => $model['package_num'],
                            'data-order_type' => 'station-order',
                            'data-title' => '显示二维码',
                        ]);
                    }

                    if (in_array($model['status'], [
                            Yii::$app->params['order_status']['arrive'],
                        ]) && $model['type'] == 2) {
                        $html [] = Html::a('<i class="iconfont icon-QR-code"></i>', 'javascript:void(0)', [
                            'class' => 'qr-btn',
                            'data-no' => $model['id'],
                            'data-num' => $model['package_num'],
                            'data-order_type' => 'arrive',
                            'data-title' => '显示二维码',
                        ]);
                    }
                    return implode(' ', $html);
                }
            ],
//            'headerOptions' => ['width' => '80'],
        ],

    ]);
    ?>
</div>
<script>
    var driverList = <?=json_encode($driverList)?>;
    var busList = <?=json_encode($busList)?>;
    var busLine = <?=json_encode($busLine)?>;
</script>
<?php $this->registerJsFile('@web/js/plugin/qrcode.min.js', ['depends' => ['backend\assets\AppAsset']]); ?>
<?php $this->registerJsFile('@web/js/erp/order.js', ['depends' => ['backend\assets\AppAsset']]); ?>
