<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'package_id_list') ?>

    <?= $form->field($model, 'receive_station_id') ?>

    <?= $form->field($model, 'receive_station_name') ?>

    <?= $form->field($model, 'send_station_id') ?>

    <?php // echo $form->field($model, 'send_station_name') ?>

    <?php // echo $form->field($model, 'driver_id') ?>

    <?php // echo $form->field($model, 'driver_name') ?>

    <?php // echo $form->field($model, 'driver_phone') ?>

    <?php // echo $form->field($model, 'driver_accept_type') ?>

    <?php // echo $form->field($model, 'bus_line') ?>

    <?php // echo $form->field($model, 'card') ?>

    <?php // echo $form->field($model, 'deliver_time') ?>

    <?php // echo $form->field($model, 'station_time') ?>

    <?php // echo $form->field($model, 'receiver_time') ?>

    <?php // echo $form->field($model, 'package_num') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'total_account') ?>

    <?php // echo $form->field($model, 'yongjin') ?>

    <?php // echo $form->field($model, 'detail') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
