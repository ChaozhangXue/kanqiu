<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceStation */

$this->params['breadcrumbs'][] = ['label' => 'Service Stations', 'url' => ['index']];
\yii\web\YiiAsset::register($this);
?>
<div class="service-station-view">


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
            'country',
            'station_name',
            'address',
            'longitude',
            'latitude',
            'entity',
            'in_charge_name',
            'id_card',
            'build_size',
            'code',
            'people_num',
            'telephone',
            'build_time',
            'service_time',
            'backup',
            'create_time:datetime',
            'update_time:datetime',
        ],
    ]) ?>

</div>
