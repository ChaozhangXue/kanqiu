<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusStation */

$this->title = '创建公交站点';
$this->params['breadcrumbs'][] = ['label' => 'Bus Stations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bus-station-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
