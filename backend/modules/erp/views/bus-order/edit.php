<?php

use backend\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusOrder */
/* @var $form yii\widgets\ActiveForm */
if ($model->isNewRecord) {
    $this->title = '创建客运订单';
}
?>

<div class="bus-order-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'col-12 col-md-8 col-sm-8 form-horizontal', 'id' => 'save-form']
    ]); ?>
    <input type="hidden" name="BusOrder[id]" value="<?= $model->id ?>">
    <input type="hidden" id="start_point" name="BusOrder[start_point]" value="<?= $model->start_point ?>">
    <input type="hidden" id="end_point" name="BusOrder[end_point]" value="<?= $model->end_point ?>">
    <?= $form->field($model, 'order_type')->radioList(Yii::$app->params['bus_order_type_list']) ?>

    <?= $form->field($model, 'customer_id')->textInput(['placeholder' => '请输入会员ID'])->hint('替用户下单请输入对应的会员ID，输入0表示系统建单') ?>

    <?= $form->field($model, 'car_type', ['options' => ['class' => 'form-group form-type type1 type3']])->radioList(Yii::$app->params['car_type_list']) ?>

    <?= $form->field($model, 'use_people', ['options' => ['class' => 'form-group form-type type1']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile', ['options' => ['class' => 'form-group form-type type1']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reason', ['options' => ['class' => 'form-group form-type type2 type3']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_time', ['options' => ['class' => 'form-group form-type type1 type3']])->textInput(['placeholder' => '开始时间，格式为 2019-01-01 12:00:00']) ?>

    <?= $form->field($model, 'end_time', ['options' => ['class' => 'form-group form-type type1 type3']])->textInput(['placeholder' => '结束时间，格式为 2019-01-01 12:00:00']) ?>

    <?= $form->field($model, 'dispatch_start', ['options' => ['class' => 'form-group form-type type1 type2 type3']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dispatch_end', ['options' => ['class' => 'form-group form-type type1 type3']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_number', ['options' => ['class' => 'form-group form-type type2']])->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'money', ['options' => ['class' => 'form-group form-type type1 type3']])->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'remark', ['options' => ['class' => 'form-group']])->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script src="https://webapi.amap.com/maps?v=1.4.15&key=a28a2dfcc0cbf47b33159d8efe55558e&plugin=AMap.Driving"></script>
<script src="https://webapi.amap.com/ui/1.0/main.js?v=1.0.11"></script>
<?php $this->registerJsFile('@web/js/erp/bus-order.js', ['depends' => ['backend\assets\AppAsset']]); ?>
