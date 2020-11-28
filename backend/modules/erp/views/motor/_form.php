<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Motor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="motor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'brand')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'card')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'num')->textInput() ?>

    <?= $form->field($model, 'site_num')->label('座位数量')->dropDownList(Yii::$app->params['seat_type_list'], ['prompt' => '请选择']) ?>

    <?= $form->field($model, 'car_type')->label('车辆类型')->dropDownList(Yii::$app->params['car_type_list'], ['prompt' => '请选择']) ?>

    <?= $form->field($model, 'buy_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
