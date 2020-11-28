<?php

use common\services\BusOrderService;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BusOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="bus-order-index">

    <p>
        <?= Html::a('创建新订单', ['edit'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('计费规则', ['money-rule'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view table-responsive text-nowrap', 'id' => 'list-table'],
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'order_no',
                'contentOptions' => [
                    'class' => 'bus-order-no',
                    'data-url' => Yii::$app->urlManager->createUrl(['erp/bus-order/get-info']),
                    'style' => 'cursor:pointer'
                ],
            ],
            [
                'attribute' => 'order_type',
                'filter' => Yii::$app->params['bus_order_type_list'],
                'value' => function ($model) {
                    return Yii::$app->params['bus_order_type_list'][$model['order_type']];
                }
            ],
            [
                'attribute' => 'status',
                'filter' => Yii::$app->params['bus_order_status_list'],
                'value' => function ($model) {
                    return Yii::$app->params['bus_order_status_list'][$model->status];
                },
            ],
            [
                'attribute' => 'money',
                'value' => function ($model) {
                    if ($model['order_type'] == '2') {
                        return '--';
                    }
                    return $model['money'];
                },
            ],
            [
                'attribute' => 'car_type',
                'filter' => Yii::$app->params['car_type_list'],
                'value' => function ($model) {
                    if ($model['order_type'] == '2') {
                        return '--';
                    }
                    return $model['car_type'] ? Yii::$app->params['car_type_list'][$model['car_type']] : '未选择';
                }
            ],
            'use_people',
            [
                'attribute' => 'start_time',
                'value' => function ($model) {
                    return $model->start_time ? date('Y-m-d H:i:s', $model->start_time) : '';
                },
            ],
            [
                'attribute' => 'end_time',
                'value' => function ($model) {
                    if ($model['order_type'] == '2') {
                        return '--';
                    }
                    return $model->end_time ? date('Y-m-d H:i:s', $model->end_time) : '';
                },
            ],
            [
                'attribute' => 'pay_time',
                'value' => function ($model) {
                    if ($model['order_type'] == '2') {
                        return '--';
                    }
                    return $model->pay_time ? date('Y-m-d H:i:s', $model->pay_time) : '--';
                },
            ],
            [
                'attribute' => 'pay_method',
                'filter' => Yii::$app->params['pay_method_list'],
                'value' => function ($model) {
                    if ($model['order_type'] == '2') {
                        return '--';
                    }
                    return isset(Yii::$app->params['pay_method_list'][$model->pay_method]) ? Yii::$app->params['pay_method_list'][$model->pay_method] : '--';
                },
            ],
            'driver_name',
            'driver_phone',
            'dispatch_start',
            [
                'attribute' => 'dispatch_end',
                'value' => function ($model) {
                    if ($model['order_type'] == '2') {
                        return '--';
                    }
                    return $model['dispatch_end'];
                },
            ],
            'reason',
            'remark:ntext',
            [
                'attribute' => 'create_time',
                'value' => function ($model) {
                    return $model->create_time ? date('Y-m-d H:i:s', $model->create_time) : '';
                },
            ],
            [
                'label' => '操作',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = [];
                    $url = Yii::$app->urlManager->createUrl(['/erp/bus-order/view', 'id' => $model['id']]);
                    $html [] = Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, ['data-title' => '查看']);
                    if (in_array($model['status'], [
                        BusOrderService::BUS_ORDER_STATUS_PENDING,
                        BusOrderService::BUS_ORDER_STATUS_PAY,
                        BusOrderService::BUS_ORDER_STATUS_ASSIGN,
                        BusOrderService::BUS_ORDER_STATUS_REJECT,
                        BusOrderService::BUS_ORDER_STATUS_ACCEPT,
                    ])) {
                        $html [] = Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['edit', 'id' => $model['id']], ['data-title' => '编辑']);
                        $html [] = Html::a('<i class="glyphicon glyphicon-remove-circle"></i>', 'javascript:void(0)', [
                            'class' => 'cancel-btn',
                            'data-title' => '取消订单',
                            'data-id' => $model['order_no'],
                            'data-add_type' => $model['add_type'],
                            'data-money' => $model['pay_money'],
                            'data-url' => Yii::$app->urlManager->createUrl(['/erp/bus-order/cancel'])
                        ]);
                    }
                    if ($model['status'] == BusOrderService::BUS_ORDER_STATUS_PENDING && $model['order_type'] != 2) {
                        $html [] = Html::a('<i class="iconfont icon-shouye"></i>', 'javascript:void(0)', [
                            'class' => 'pay-btn',
                            'data-title' => '付款',
                            'data-id' => $model['id'],
                            'data-url' => Yii::$app->urlManager->createUrl(['/erp/bus-order/pay']),
                        ]);
                    }
                    if (in_array($model['status'], [
                        BusOrderService::BUS_ORDER_STATUS_PAY,
                        BusOrderService::BUS_ORDER_STATUS_ASSIGN,
                        BusOrderService::BUS_ORDER_STATUS_REJECT,
                        BusOrderService::BUS_ORDER_STATUS_ACCEPT,
                    ])) {
                        $html [] = Html::a('<i class="glyphicon glyphicon-hand-right"></i>', 'javascript:void(0)', [
                            'class' => 'assign-btn',
                            'data-title' => $model['status'] == BusOrderService::BUS_ORDER_STATUS_PAY ? '指派' : '转派',
                            'data-status' => $model['status'],
                            'data-id' => $model['id'],
                            'data-url' => Yii::$app->urlManager->createUrl(['/erp/bus-order/assign']),
                            'data-bus' => $model['bus_id'],
                            'data-car_type' => $model['car_type'],
                            'data-type' => $model['order_type'],
                            'data-driver' => $model['driver_id']
                        ]);
                    }
                    if ($model['status'] == BusOrderService::BUS_ORDER_STATUS_REFUND) {
                        $html [] = Html::a('<i class="iconfont icon-tuikuan"></i>', 'javascript:void(0)', [
                            'class' => 'refund-btn',
                            'data-url' => Yii::$app->urlManager->createUrl(['/erp/bus-order/refund']),
                            'data-msg' => '退款金额 ' . $model['money'] . '，请确认?',
                            'data-title' => '退款',
                            'data-id' => $model['id'],
                        ]);
                    }
                    if ($model['status'] == BusOrderService::BUS_ORDER_STATUS_ACCEPT) {
                        $html [] = Html::a('<i class="iconfont icon-QR-code"></i>', 'javascript:void(0)', [
                            'class' => 'qr-btn',
                            'data-no' => $model['order_no'],
                            'data-type' => 'start',
                            'data-title' => '开始行程二维码',
                        ]);
                    }
                    if ($model['status'] == BusOrderService::BUS_ORDER_STATUS_GOING) {
                        $html [] = Html::a('<i class="iconfont icon-QR-code"></i>', 'javascript:void(0)', [
                            'class' => 'qr-btn',
                            'data-no' => $model['order_no'],
                            'data-type' => 'end',
                            'data-title' => '结束行程二维码',
                        ]);
                    }
                    return implode(' ', $html);
                }
            ],
        ],
    ]); ?>
</div>
<script>
    var driverList = <?=json_encode($driverList)?>;
    var busList = <?=json_encode($busList)?>;
</script>
<?php $this->registerJsFile('@web/js/plugin/qrcode.min.js', ['depends' => ['backend\assets\AppAsset']]); ?>
<?php $this->registerJsFile('@web/js/erp/bus-order.js', ['depends' => ['backend\assets\AppAsset']]); ?>
