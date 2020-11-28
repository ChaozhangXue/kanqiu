<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\StationBill */

$this->title = '编辑站点账单';
$this->params['breadcrumbs'][] = ['label' => '站点账单', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="station-bill-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
