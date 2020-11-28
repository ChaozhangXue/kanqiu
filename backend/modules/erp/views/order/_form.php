<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'package_id_list')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'receive_station_id')->textInput() ?>

    <?= $form->field($model, 'receive_station_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'send_station_id')->textInput() ?>

    <?= $form->field($model, 'send_station_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'driver_id')->textInput() ?>

    <?= $form->field($model, 'driver_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'driver_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'driver_accept_type')->textInput() ?>

    <?= $form->field($model, 'bus_line')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'card')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deliver_time')->textInput() ?>

    <?= $form->field($model, 'station_time')->textInput() ?>

    <?= $form->field($model, 'receiver_time')->textInput() ?>

    <?= $form->field($model, 'package_num')->textInput() ?>

    <?= $form->field($model, 'source')->textInput() ?>

    <?= $form->field($model, 'total_account')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'yongjin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'detail')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
