<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PackageList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="package-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'package_id')->textInput() ?>

    <?= $form->field($model, 'is_print')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
