<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\StationBillSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="station-bill-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'station_id') ?>

    <?= $form->field($model, 'station_name') ?>

    <?= $form->field($model, 'owner') ?>

    <?php // echo $form->field($model, 'order_type') ?>

    <?php // echo $form->field($model, 'time') ?>

    <?php // echo $form->field($model, 'money') ?>

    <?php // echo $form->field($model, 'yongjin') ?>

    <?php // echo $form->field($model, 'package_num') ?>

    <?php // echo $form->field($model, 'verify_time') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'bill_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
