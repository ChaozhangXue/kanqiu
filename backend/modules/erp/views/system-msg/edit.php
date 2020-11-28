<?php

use backend\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var common\models\SystemMsg $model */
$model->type = $model->type ? $model->type : 2;
if ($model->isNewRecord) {
    $this->title = '创建新消息';
}
?>
<div class="system-msg-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'col-12 col-md-8 col-sm-8 form-horizontal', 'id' => 'save-form']
    ]); ?>

    <input type="hidden" name="System[id]" value="<?= $model->id ?>">
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'publish_time')
        ->textInput(['placeholder' => '发送时间，格式为 2019-01-01 12:00:00', 'value' => $model->publish_time ? date('Y-m-d H:i:s', $model->publish_time) : ''])
        ->hint('需要立即发送，则当前时间不填')
    ?>

    <?= $form->field($model, 'type')->radioList(Yii::$app->params['message_type_list'])->hint('小程序用户能收到消息，但无法收到弹窗提示') ?>

    <?= $form->field($model, 'receive_id', ['options' => ['style' => ($model->type == 2 ? 'display:none' : '')]])->textInput(['type' => 'number', 'placeholder' => '请输入用户ID']) ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJsFile('@web/js/erp/system-msg.js', ['depends' => ['backend\assets\AppAsset']]); ?>
