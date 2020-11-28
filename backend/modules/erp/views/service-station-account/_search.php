<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ServiceStationAccountSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="service-station-account-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_num') ?>

    <?= $form->field($model, 'station_name') ?>

    <?= $form->field($model, 'in_charge_name') ?>

    <?= $form->field($model, 'order_time') ?>

    <?php // echo $form->field($model, 'total_account') ?>

    <?php // echo $form->field($model, 'yongjin') ?>

    <?php // echo $form->field($model, 'package_num') ?>

    <?php // echo $form->field($model, 'verify_time') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'bill_time') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'update_time') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
