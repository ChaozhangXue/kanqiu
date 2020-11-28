<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PackageOrder */

$this->title = '创建包裹数据';
$this->params['breadcrumbs'][] = ['label' => 'Package Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="package-order-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
