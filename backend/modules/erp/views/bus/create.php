<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Bus */

$this->title = '创建公交管理';
$this->params['breadcrumbs'][] = ['label' => 'Buses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bus-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
