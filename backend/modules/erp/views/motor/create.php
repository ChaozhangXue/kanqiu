<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Motor */

$this->title = '创建机动车辆';
$this->params['breadcrumbs'][] = ['label' => 'Motors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="motor-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
