<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PackageOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="package-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'delivery_num') ?>

    <?= $form->field($model, 'order_num') ?>

    <?= $form->field($model, 'sender') ?>

    <?= $form->field($model, 'sender_phone') ?>

    <?php // echo $form->field($model, 'send_address') ?>

    <?php // echo $form->field($model, 'receiver') ?>

    <?php // echo $form->field($model, 'receiver_phone') ?>

    <?php // echo $form->field($model, 'receive_address') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'size') ?>

    <?php // echo $form->field($model, 'distance') ?>

    <?php // echo $form->field($model, 'express_company') ?>

    <?php // echo $form->field($model, 'is_on_door') ?>

    <?php // echo $form->field($model, 'sender_backup') ?>

    <?php // echo $form->field($model, 'deliver_time') ?>

    <?php // echo $form->field($model, 'station_time') ?>

    <?php // echo $form->field($model, 'receiver_time') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'total_account') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'submit_station_id') ?>

    <?php // echo $form->field($model, 'submit_station_name') ?>

    <?php // echo $form->field($model, 'receive_station_id') ?>

    <?php // echo $form->field($model, 'receive_station_name') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
