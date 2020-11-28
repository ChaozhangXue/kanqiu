<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BusStation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bus-station-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'station_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'up_point')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'down_point')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
