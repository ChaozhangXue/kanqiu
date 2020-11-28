<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\DriverBill */

\yii\web\YiiAsset::register($this);
?>
<div class="driver-bill-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            [
                'attribute' => 'bill_type',
                'value' => function ($model) {
                    return Yii::$app->params['bill_type_list'][$model->bill_type];
                },
            ],
            'order_no',
            'driver_name',
            [
                'attribute' => 'pay_method',
                'value' => function ($model) {
                    return Yii::$app->params['pay_method_list'][$model->pay_method];
                },
            ],
            'transaction_id',
            'amount',
            'commission',
            'package_num',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status == 1 ? '未审核' : '已审核';
                },
            ],
            [
                'attribute' => 'verify_time',
                'value' => function ($model) {
                    return $model->verify_time ? date('Y-m-d H:i:s', $model->verify_time) : '';
                },
                'visible' => $model->status == 2
            ],
            'order_time:datetime',
            'pay_time:datetime',
            'create_time:datetime',
            'update_time:datetime',
        ],
    ]) ?>

</div>
