<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BusLineSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bus-line-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'station_name') ?>

    <?= $form->field($model, 'station_num') ?>

    <?= $form->field($model, 'start_point') ?>

    <?= $form->field($model, 'end_point') ?>

    <?php // echo $form->field($model, 'create_people') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'station_list') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
