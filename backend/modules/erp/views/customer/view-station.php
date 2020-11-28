<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->title = '站点用户 - ' . $model->username;
?>
<div class="customer-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'customer_id',
            'username',
            [
                'attribute' => 'avatar',
                'format'=>'html',
                'value' => function ($model) {
                    return $model->avatar ? '<img src="'.$model->avatar.'" style="width:80px;height:80px">' : '';
                },
            ],
            'realname',
            'mobile',
            [
                'attribute' => 'status',
                'label' => '状态',
                'value' => function ($model) {
                    return $model->status == 1 ? '可用' : '禁用';
                },
            ],
            [
                'attribute' => 'verify_status',
                'value' => function ($model) {
                    return Yii::$app->params['verify_status_list'][$model->verify_status];
                },
            ],
            [
                'attribute' => 'relation_id',
                'label' => '服务站点',
                'value' => function ($model) {
                    $station = \common\models\ServiceStation::find()->where(['id' => $model->relation_id])->one();
                    if(!empty($station)){
                        return $station->station_name;
                    }else{
                        return '';
                    }
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
