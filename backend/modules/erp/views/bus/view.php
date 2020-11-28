<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Bus */

$this->params['breadcrumbs'][] = ['label' => '公交汽车', 'url' => ['index']];
?>
<div class="bus-view">

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
                'attribute' => 'car_type',
                'label' => '车辆类型',
                'filter' => Yii::$app->params['car_type'],
                'value' => function ($model) {
                    return Yii::$app->params['car_type'][$model->car_type];
                },
            ],
            'buy_time',
            'dept',
//            'created_at',
//            'updated_at',
        ],
    ]) ?>

</div>
