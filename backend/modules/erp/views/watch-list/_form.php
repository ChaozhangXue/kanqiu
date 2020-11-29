<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WatchList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="watch-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'game_date')->textInput() ?>

    <?= $form->field($model, 'game_time')->textInput() ?>

    <?= $form->field($model, 'team1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'team2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'game_link')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'update_time')->textInput() ?>

    <?= $form->field($model, 'expire_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
