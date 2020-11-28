<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PackageOrder */
/* @var $form yii\widgets\ActiveForm */

$weight = ['1' => "0-10kg", '2' => "11-15kg", '3' => "16-20kg"];
$size = ['1' => " ≤0.03m³", '2' => " ≤0.045m³", '3' => " ≤0.06m³"];
$distance = ['1' => "15km", '2' => "16-50km", '3' => "51㎞以上"];

$service = \common\models\ServiceStation::find()->all();
if(empty($service)){
    $service_array = [];
}
foreach ($service as $val) {
    $service_array[$val['id']] = $val['station_name'];
}

$type = [];
$type_data = \common\models\PackageType::find()->indexBy('id')->all();
if(!empty($type_data)){
    foreach ($type_data as $val) {
        $type[$val['type_name']] = $val['type_name'];
    }
}

?>

<div class="package-order-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'col-12 col-md-8 col-sm-8 form-horizontal', 'id' => 'save-form']
    ]); ?>
    <input type="hidden" name="BusOrder[id]" value="<?= $model->id ?>">

    <input type="hidden" id="sender_point" name="PackageOrder[sender_point]" value="<?= $model->sender_point ?>">
    <input type="hidden" id="receive_point" name="PackageOrder[receive_point]" value="<?= $model->receive_point ?>">
    <?= $form->field($model, 'delivery_num')->label('快递订单编号')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sender_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'send_address', ['options' => ['class' => 'form-group form-type type1 type2 type3']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiver')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiver_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receive_address', ['options' => ['class' => 'form-group form-type type1 type2 type3']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->label('物品类型')->dropDownList($type, ['prompt' => '请选择']) ?>
    <?= $form->field($model, 'weight')->label('重量')->dropDownList($weight, ['prompt' => '请选择']) ?>

    <?= $form->field($model, 'size')->label('物品体积')->dropDownList($size, ['prompt' => '请选择']) ?>

    <?= $form->field($model, 'express_company')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sender_backup')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script src="https://webapi.amap.com/maps?v=1.4.15&key=a28a2dfcc0cbf47b33159d8efe55558e&plugin=AMap.Driving"></script>
<script src="https://webapi.amap.com/ui/1.0/main.js?v=1.0.11"></script>
<?php $this->registerJsFile('@web/js/erp/package-order.js', ['depends' => ['backend\assets\AppAsset']]); ?>

