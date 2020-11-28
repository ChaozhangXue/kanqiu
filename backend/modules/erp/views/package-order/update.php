<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PackageOrder */

$this->title = '编辑包裹';
$this->params['breadcrumbs'][] = ['label' => '包裹数据', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="package-order-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
