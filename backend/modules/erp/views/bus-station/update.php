<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusStation */

$this->title = '编辑公交站点';
$this->params['breadcrumbs'][] = ['label' => 'Bus Stations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="bus-station-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
