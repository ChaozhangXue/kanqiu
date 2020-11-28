<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Motor */

$this->params['breadcrumbs'][] = ['label' => '机动车辆', 'url' => ['index']];
\yii\web\YiiAsset::register($this);
?>
<div class="motor-view">

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
//            'id',
            'brand',
            'model',
            'card',
            'color',
            'num',
            [
                'attribute' => 'site_num',
                'label' => '座位数量',
                'filter' => Yii::$app->params['seat_type_list'],
                'value' => function ($model) {
                    return Yii::$app->params['seat_type_list'][$model->site_num];
                },
            ],
            [
                'attribute' => 'car_type',
                'label' => '车辆类型',
                'filter' => Yii::$app->params['car_type'],
                'value' => function ($model) {
                    return Yii::$app->params['car_type'][$model->car_type];
                },
            ],
            'buy_time',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
