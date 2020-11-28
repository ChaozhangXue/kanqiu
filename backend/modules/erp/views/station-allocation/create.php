<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\StationAllocation */

$this->title = Yii::t('app', '创建站点配置');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Station Allocations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="station-allocation-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
