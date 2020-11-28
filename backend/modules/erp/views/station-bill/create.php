<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\StationBill */

$this->title = 'Create Station Bill';
$this->params['breadcrumbs'][] = ['label' => 'Station Bills', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="station-bill-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
