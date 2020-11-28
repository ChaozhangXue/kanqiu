<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceStation */

$this->title = '编辑服务站点';
$this->params['breadcrumbs'][] = ['label' => 'Service Stations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="service-station-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
