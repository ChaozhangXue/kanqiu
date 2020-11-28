<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusLine */

$this->title = '创建公交线路';
$this->params['breadcrumbs'][] = ['label' => 'Bus Lines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bus-line-create">


    <?= $this->render('_form', [
        'model' => $model,
        'stations' => $stations
    ]) ?>

</div>
