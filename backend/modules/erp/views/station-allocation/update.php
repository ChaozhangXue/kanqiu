<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\StationAllocation */

$this->title = Yii::t('app', '编辑站点分配');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Station Allocations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="station-allocation-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
