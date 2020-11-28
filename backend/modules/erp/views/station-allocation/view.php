<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\StationAllocation */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Station Allocations'), 'url' => ['index']];
\yii\web\YiiAsset::register($this);
?>
<div class="station-allocation-view">


    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'bus_id',
            'line_id',
            'line_name',
            'service_id',
            'service_name',
            'in_charge_name',
            'telphone',
            'build_time',
            'create_people',
//            'create_time:datetime',
//            'update_time:datetime',
        ],
    ]) ?>

</div>
