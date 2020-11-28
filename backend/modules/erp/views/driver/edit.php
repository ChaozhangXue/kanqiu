<?php

use backend\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Driver */
/* @var $form yii\widgets\ActiveForm */
if ($model->isNewRecord) {
    if ($driverType == 1) {
        $this->title = '创建公交司机';
    } else {
        $this->title = '创建客运司机';
    }
}
?>
<div class="driver-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'col-12 col-md-8 col-sm-8 form-horizontal', 'id' => 'save-form']
    ]); ?>

    <input type="hidden" name="id" value="<?= $model['id'] ?>">
    <?= $form->field($model, 'realname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->radioList(['1' => '男', '2' => '女']) ?>

    <?= $form->field($model, 'birth_date')->textInput(['type' => 'date']) ?>

    <?= $form->field($model, 'dept')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'job_position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'employment_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'license')->dropDownList(Yii::$app->params['driving_license_list']) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idcard')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'entry_time')->textInput([
        'type' => 'date',
        'value' => $model->entry_time?date("Y-m-d", $model->entry_time):''
    ]) ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
