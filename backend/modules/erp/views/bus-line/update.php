<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusLine */

$this->title = '更新公交新线路: ' . $model->station_name;
$this->params['breadcrumbs'][] = ['label' => 'Bus Lines', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';

?>
<div class="bus-line-update">


    <?= $this->render('_form', [
        'model' => $model,
        'stations' => $stations
    ]) ?>

</div>
