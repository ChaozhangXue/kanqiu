<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->title = $model->nickname;
?>
<div class="customer-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'customer_id',
            'nickname',
            [
                'attribute' => 'avatar',
                'format'=>'html',
                'value' => function ($model) {
                    return $model->avatar ? '<img src="'.$model->avatar.'" style="width:80px;height:80px">' : '';
                },
            ],
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
                'label' => '会员状态',
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
                'label'=>'注册时间',
                'value' => function ($model) {
                    return $model->create_time ? date('Y-m-d H:i:s', $model->create_time) : '';
                },
            ],
            [
                'attribute' => 'verify_status',
                'value' => function ($model) {
                    return Yii::$app->params['verify_status_list'][$model->verify_status];
                },
            ],
        ],
    ]) ?>

</div>
