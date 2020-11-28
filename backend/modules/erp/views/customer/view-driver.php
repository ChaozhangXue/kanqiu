<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->title = $model->realname;
?>
<div class="customer-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'customer_id',
            'username',
            'realname',
            [
                'attribute' => 'gender',
                'value' => function ($model) {
                    return $model->gender == 1 ? '男' : '女';
                },
            ],
            'mobile',
            [
                'attribute' => 'status',
                'label' => '状态',
                'value' => function ($model) {
                    return $model->status == 1 ? '可用' : '禁用';
                },
            ],
            [
                'attribute' => 'last_login_time',
                'value' => function ($model) {
                    return $model->last_login_time ? date('Y-m-d H:i:s', $model->last_login_time) : '';
                },
            ],
            [
                'attribute' => 'create_time',
                'value' => function ($model) {
                    return $model->create_time ? date('Y-m-d H:i:s', $model->create_time) : '';
                },
            ],
        ],
    ]) ?>

</div>
