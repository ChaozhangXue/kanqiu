<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PackageList */

$this->title = '更新包裹码';
$this->params['breadcrumbs'][] = ['label' => 'Package Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="package-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
