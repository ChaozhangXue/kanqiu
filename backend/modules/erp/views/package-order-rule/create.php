<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PackageOrderRule */

$this->title = 'Create Package Order Rule';
$this->params['breadcrumbs'][] = ['label' => 'Package Order Rules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="package-order-rule-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
