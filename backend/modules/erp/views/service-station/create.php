<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceStation */

$this->title = '创建服务站点';
$this->params['breadcrumbs'][] = ['label' => 'Service Stations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-station-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
