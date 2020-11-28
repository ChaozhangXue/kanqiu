<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceStationAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="service-station-account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'station_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'in_charge_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_time')->textInput() ?>

    <?= $form->field($model, 'total_account')->textInput() ?>

    <?= $form->field($model, 'yongjin')->textInput() ?>

    <?= $form->field($model, 'package_num')->textInput() ?>

    <?= $form->field($model, 'verify_time')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'bill_time')->textInput() ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'update_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
