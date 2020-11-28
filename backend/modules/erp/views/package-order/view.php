<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PackageOrder */

$this->params['breadcrumbs'][] = ['label' => '包裹数据', 'url' => ['index']];
\yii\web\YiiAsset::register($this);
?>
<div class="package-order-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'delivery_num',
            'order_num',
            'sender',
            'sender_phone',
            'send_address',
            'receiver',
            'receiver_phone',
            'receive_address',
//            [
//                'attribute' => 'type',
//                'label' => '订单来源',
//                'filter' => Yii::$app->params['order_type'],
//                'value' => function ($model) {
//                    return Yii::$app->params['order_type'][$model->type];
//                },
//            ],
            [
                'attribute' => 'weight',
                'label' => '订单来源',
                'filter' => Yii::$app->params['weight'],
                'value' => function ($model) {
                    return Yii::$app->params['weight'][$model->weight];
                },
            ],
            [
                'attribute' => 'size',
                'label' => '订单来源',
                'filter' => Yii::$app->params['size'],
                'value' => function ($model) {
                    return Yii::$app->params['size'][$model->size];
                },
            ],
            [
                'attribute' => 'distance',
                'label' => '订单来源',
                'filter' => Yii::$app->params['distance'],
                'value' => function ($model) {
                    return Yii::$app->params['distance'][$model->distance];
                },
            ],
            'express_company',
//            'is_on_door',
            'sender_backup:ntext',
            'deliver_time',
            'station_time',
            'receiver_time',
            [
                'attribute' => 'source',
                'label' => '订单来源',
                'filter' => Yii::$app->params['order_source'],
                'value' => function ($model) {
                    return Yii::$app->params['order_source'][$model->source];
                },
            ],
            'total_account',
            [
                'attribute' => 'status',
                'label' => '包裹状态',
                'filter' => Yii::$app->params['order_status_list'],
                'value' => function ($model) {
                    if($model->station_check == 1 && $model->status == 2){
                        return "待审核";
                    }
                    if($model->station_check == 2 && $model->status == 2){
                        return "已审核";
                    }
                    return Yii::$app->params['package_status_list'][$model->status];
                },
            ],
//            'submit_station_id',
            'submit_station_name',
//            'receive_station_id',
            'receive_station_name',
            'created_at',
//            'updated_at',
        ],
    ]) ?>

</div>
