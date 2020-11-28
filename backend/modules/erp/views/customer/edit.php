<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */
/* @var $form yii\widgets\ActiveForm */
if (!$model->customer_id) {
    if ($customerType == 1) {
        $this->title = '添加司机';
    } else if ($customerType == 2) {
        $this->title = '添加会员';
    } else {
        $this->title = '添加站点用户';
    }
}
$station_list = [];
$station = \common\models\ServiceStation::find()->where('id != 0')->asArray()->all();
foreach ($station as $val){
    $station_list[$val['id']] = $val['station_name'];
}


?>
<div class="customer-form">

    <?php $form = \backend\widgets\ActiveForm::begin([
        'options' => ['class' => 'col-12 col-md-8 col-sm-8 form-horizontal', 'id' => 'save-form']
    ]); ?>
    <input type="hidden" name="Customer[customer_id]" value="<?= $model['customer_id'] ?>">
    <?php if ($customerType == 2): ?>
        <?= $form->field($model, 'nickname')->textInput(['maxlength' => true]) ?>
    <?php else: ?>
        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?php endif; ?>
    <?= $form->field($model, 'realname')->textInput(['maxlength' => true]) ?>
    <?php if ($customerType != 3): ?>
        <?= $form->field($model, 'gender')->radioList(['1' => '男', '2' => '女']) ?>
        <?= $form->field($model, 'birth_date')->textInput(['type' => 'date']) ?>
    <?php endif; ?>
    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?php if (!empty($show_status)): ?>
        <?= $form->field($model, 'relation_id')->label('服务站点')->dropDownList($station_list, ['prompt' => '请选择']) ?>
    <?php endif; ?>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php \backend\widgets\ActiveForm::end(); ?>

</div>
<?php $this->registerJsFile('@web/js/erp/customer.js', ['depends' => ['backend\assets\AppAsset']]); ?>
