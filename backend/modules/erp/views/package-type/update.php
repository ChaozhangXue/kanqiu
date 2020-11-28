<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PackageType */

$this->title = '更新包裹类型 ';
$this->params['breadcrumbs'][] = ['label' => 'Package Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="package-type-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
