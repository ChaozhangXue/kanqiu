<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BusStation */

$this->params['breadcrumbs'][] = ['label' => '公交站点', 'url' => ['index']];
\yii\web\YiiAsset::register($this);
?>
<div class="bus-station-view">


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
            'station_name',
            'up_point',
            'down_point',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
