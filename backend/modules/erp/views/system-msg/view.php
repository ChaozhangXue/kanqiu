<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SystemMsg */

\yii\web\YiiAsset::register($this);
?>
<div class="system-msg-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            [
                'attribute' => 'type',
                'label' => '消息类型',
                'value' => function ($model) {
                    return Yii::$app->params['message_type_list'][$model->type];
                },
            ],
            [
                'attribute' => 'customer_type',
                'value' => function ($model) {
                    return $model->customer_type != 0 ? Yii::$app->params['customer_type_list'][$model->customer_type] : '';
                },
            ],
            'title',
            'content:ntext',
            [
                'attribute' => 'receive_id',
                'value' => function ($model) {
                    return $model->receive_id;
                },
                'visible' => $model->type == 1
            ],
            [
                'attribute' => 'publish_time',
                'label' => '发布时间',
                'value' => function ($model) {
                    return $model->publish_time ? date('Y-m-d H:i:s', $model->publish_time) : '';
                },
            ],
            'publisher',
            [
                'attribute' => 'create_time',
                'label' => '创建时间',
                'value' => function ($model) {
                    return $model->create_time ? date('Y-m-d H:i:s', $model->create_time) : '';
                },
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return Yii::$app->params['message_status_list'][$model->status];
                },
            ],
            [
                'attribute' => 'exec_time',
                'label' => '推送时间',
                'value' => function ($model) {
                    return $model->exec_time ? date('Y-m-d H:i:s', $model->exec_time) : '';
                },
                'visible' => $model->exec_time
            ],
            [
                'attribute' => 'response',
                'format' => 'ntext',
                'visible' => $model->exec_time
            ]
        ],
    ]) ?>

</div>
