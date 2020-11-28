<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PackageType */

$this->title = '创建物品类型';
$this->params['breadcrumbs'][] = ['label' => 'Package Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="package-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
