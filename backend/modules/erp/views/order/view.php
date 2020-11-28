<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">
    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'package_id_list:ntext',
//            'receive_station_id',
            'receive_station_name',
//            'send_station_id',
            'send_station_name',
            'driver_id',
            'driver_name',
            'driver_phone',
//            'driver_accept_type',
            'bus_line',
            'card',
            'deliver_time',
            'station_time',
            'receiver_time',
            'package_num',
            [
                'attribute' => 'source',
                'label' => '订单来源',
                'filter' => Yii::$app->params['order_status_list'],
                'value' => function ($model) {
                    return Yii::$app->params['order_status_list'][$model->status];
                },
            ],
            'total_account',
            'yongjin',
            'detail:ntext',
            [
                'attribute' => 'status',
                'label' => '订单状态',
                'filter' => Yii::$app->params['order_status_list'],
                'value' => function ($model) {
                    return Yii::$app->params['order_status_list'][$model->status];
                },
            ],
//            'created_at',
//            'updated_at',
        ],
    ]) ?>

</div>
