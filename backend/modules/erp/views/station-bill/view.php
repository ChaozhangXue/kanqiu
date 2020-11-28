<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\StationBill */

$this->params['breadcrumbs'][] = ['label' => '站点账单', 'url' => ['index']];
\yii\web\YiiAsset::register($this);
?>
<div class="station-bill-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            'order_id',
            'station_name',
            'owner',
            'order_type',
            'time',
            'money',
            'yongjin',
            'package_num',
            'verify_time',
            'status',
            'bill_time',
        ],
    ]) ?>

</div>
