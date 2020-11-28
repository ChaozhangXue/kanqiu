<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\StationAllocationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="station-allocation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'bus_id') ?>

    <?= $form->field($model, 'line_id') ?>

    <?= $form->field($model, 'line_name') ?>

    <?= $form->field($model, 'service_id') ?>

    <?php // echo $form->field($model, 'service_name') ?>

    <?php // echo $form->field($model, 'in_charge_name') ?>

    <?php // echo $form->field($model, 'telphone') ?>

    <?php // echo $form->field($model, 'build_time') ?>

    <?php // echo $form->field($model, 'create_people') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'update_time') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
