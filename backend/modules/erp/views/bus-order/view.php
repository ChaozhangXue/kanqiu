<?php

use common\services\BusOrderService;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BusOrder */

\yii\web\YiiAsset::register($this);
$this->title = $model->title;
?>
<div class="bus-order-view">
    <p>
        <?php if (in_array($model['status'], [
            BusOrderService::BUS_ORDER_STATUS_PENDING,
            BusOrderService::BUS_ORDER_STATUS_PAY,
            BusOrderService::BUS_ORDER_STATUS_ASSIGN,
            BusOrderService::BUS_ORDER_STATUS_REJECT,
            BusOrderService::BUS_ORDER_STATUS_ACCEPT,
        ])) : ?>
            <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> 编辑', ['edit', 'id' => $model['id']], ['class' => 'btn btn-success']); ?>
            <?= Html::a('<i class="glyphicon glyphicon-remove-circle"></i> 取消', 'javascript:void(0)', [
                'class' => 'btn btn-danger cancel-btn',
                'data-id' => $model['order_no'],
                'data-add_type' => $model['add_type'],
                'data-money' => $model['pay_money'],
                'data-url' => Yii::$app->urlManager->createUrl(['/erp/bus-order/cancel']),
            ]); ?>
        <?php endif; ?>
        <?php if ($model['status'] == BusOrderService::BUS_ORDER_STATUS_PENDING && $model['order_type'] != 2) : ?>
            <?= Html::a('<i class="iconfont icon-shouye"></i> 付款', 'javascript:void(0)', [
                'class' => 'btn btn-info pay-btn',
                'data-id' => $model['id'],
                'data-url' => Yii::$app->urlManager->createUrl(['/erp/bus-order/pay']),
            ]); ?>
        <?php endif; ?>
        <?php if (in_array($model->status, [
            BusOrderService::BUS_ORDER_STATUS_PAY,
            BusOrderService::BUS_ORDER_STATUS_ASSIGN,
            BusOrderService::BUS_ORDER_STATUS_REJECT,
            BusOrderService::BUS_ORDER_STATUS_ACCEPT,
        ])): ?>
            <?= Html::a($model->status == BusOrderService::BUS_ORDER_STATUS_PAY ? '指派' : '转派', 'javascript:void(0)', [
                'data-title' => $model->status == BusOrderService::BUS_ORDER_STATUS_PAY ? '指派' : '转派',
                'data-status' => $model['status'],
                'class' => 'btn btn-primary assign-btn',
                'data-id' => $model['id'],
                'data-url' => Yii::$app->urlManager->createUrl(['/erp/bus-order/assign']),
                'data-bus' => $model['bus_id'],
                'data-car_type' => $model['car_type'],
                'data-type' => $model['order_type'],
                'data-driver' => $model['driver_id']
            ]) ?>
        <?php elseif ($model->status == BusOrderService::BUS_ORDER_STATUS_REFUND && $model['order_type'] != 2): ?>
            <?= Html::a('退款', 'javascript:void(0)', [
                'data-title' => '退款',
                'class' => 'btn btn-warning refund-btn',
                'data-id' => $model['id'],
                'data-url' => Yii::$app->urlManager->createUrl(['/erp/bus-order/refund']),
                'data-msg' => '退款金额 ' . $model['money'] . '，请确认?',
            ]) ?>

        <?php endif; ?>
        <?php if ($model['status'] == BusOrderService::BUS_ORDER_STATUS_ACCEPT) : ?>
            <?= Html::a('开始行程二维码', 'javascript:void(0)', [
                'class' => 'btn btn-warning qr-btn',
                'data-no' => $model['order_no'],
                'data-type' => 'start',
                'data-title' => '开始行程二维码',
            ]) ?>
        <?php endif; ?>
        <?php if ($model['status'] == BusOrderService::BUS_ORDER_STATUS_GOING) : ?>
            <?= Html::a('结束行程二维码', 'javascript:void(0)', [
                'class' => 'btn btn-warning qr-btn',
                'data-no' => $model['order_no'],
                'data-type' => 'end',
                'data-title' => '结束行程二维码',
            ]) ?>
        <?php endif; ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'order_no',
            [
                'attribute' => 'add_type',
                'value' => function ($model) {
                    return Yii::$app->params['bus_add_type_list'][$model['add_type']];
                }
            ],
            [
                'attribute' => 'order_type',
                'value' => function ($model) {
                    return Yii::$app->params['bus_order_type_list'][$model['order_type']];
                }
            ],
            [
                'attribute' => 'use_people',
            ],
            [
                'attribute' => 'user_number',
                'visible' => $model->order_type == 2
            ],
            [
                'attribute' => 'mobile',
            ],
            [
                'attribute' => 'reason',
                'labe' => $model->order_type == 2 ? '叫车原因' : '班车用户',
                'visible' => $model->order_type != 1
            ],
            [
                'attribute' => 'car_type',
                'value' => function ($model) {
                    return Yii::$app->params['car_type_list'][$model['car_type']];
                },
                'visible' => $model->order_type != 2 || $model->car_type
            ],
            [
                'attribute' => 'bus_card',
                'visible' => $model->bus_card
            ],
            [
                'attribute' => 'start_time',
                'value' => function ($model) {
                    return $model->start_time ? date('Y-m-d H:i:s', $model->start_time) : '';
                },
                'visible' => $model->order_type != 2
            ],
            [
                'attribute' => 'end_time',
                'value' => function ($model) {
                    return $model->end_time ? date('Y-m-d H:i:s', $model->end_time) : '';
                },
                'visible' => $model->order_type != 2
            ],
            'dispatch_start',
            [
                'attribute' => 'dispatch_end',
                'visible' => $model->order_type != 2
            ],
            [
                'attribute' => 'money',
                'visible' => $model->order_type != 2
            ],
            [
                'attribute' => 'pay_money',
                'visible' => $model->order_type != 2 && $model->status != BusOrderService::BUS_ORDER_STATUS_PENDING
            ],
            [
                'attribute' => 'transaction_id',
                'visible' => $model->order_type != 2 && $model->status != BusOrderService::BUS_ORDER_STATUS_PENDING
            ],
            [
                'attribute' => 'pay_time',
                'value' => function ($model) {
                    return $model->pay_time ? date('Y-m-d H:i:s', $model->pay_time) : '';
                },
                'visible' => $model->order_type != 2 && $model->status != BusOrderService::BUS_ORDER_STATUS_PENDING
            ],
            [
                'attribute' => 'pay_method',
                'value' => function ($model) {
                    return Yii::$app->params['pay_method_list'][$model->pay_method];
                },
                'visible' => $model->order_type != 2 && $model->status != BusOrderService::BUS_ORDER_STATUS_PENDING
            ],
            'remark:ntext',
            [
                'attribute' => 'driver_name',
                'visible' => !in_array($model->status, [BusOrderService::BUS_ORDER_STATUS_PENDING, BusOrderService::BUS_ORDER_STATUS_PAY])
            ],
            [
                'attribute' => 'driver_phone',
                'visible' => !in_array($model->status, [BusOrderService::BUS_ORDER_STATUS_PENDING, BusOrderService::BUS_ORDER_STATUS_PAY])
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return Yii::$app->params['bus_order_status_list'][$model->status];
                },
            ],
            [
                'attribute' => 'create_time',
                'value' => function ($model) {
                    return $model->create_time ? date('Y-m-d H:i:s', $model->create_time) : '';
                },
            ],
        ]
    ]); ?>
</div>
<script>
    var driverList =<?=json_encode($driverList)?>;
    var busList = <?=json_encode($busList)?>;
</script>
<?php $this->registerJsFile('@web/js/plugin/qrcode.min.js', ['depends' => ['backend\assets\AppAsset']]); ?>
<?php $this->registerJsFile('@web/js/erp/bus-order.js', ['depends' => ['backend\assets\AppAsset']]); ?>
