<?php

use common\models\BusLine;
use common\models\BusStation;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\StationAllocation */
/* @var $form yii\widgets\ActiveForm */
//获取数据
$bus_station = BusStation::find()->select(['id', 'station_name'])->all();
if(empty($bus_station)){
    $bus_station_array = [];
}
foreach ($bus_station as $val) {
    $bus_station_array[$val['id']] = $val['station_name'] ;
}

$bus_line = BusLine::find()->all();
if(empty($bus_line)){
    $bus_line_array = [];
}
foreach ($bus_line as $val) {
    $bus_line_array[$val['id']] = $val['station_name'];
}


$service = \common\models\ServiceStation::find()->all();
if(empty($service)){
    $service_array = [];
}
foreach ($service as $val) {
    $service_array[$val['id']] = $val['station_name'];
}
?>

<div class="station-allocation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'service_id')->label("服务站点")->dropDownList($service_array, ['prompt' => '请选择']) ?>

    <?= $form->field($model, 'bus_id')->label("公交站点")->dropDownList($bus_station_array, ['prompt' => '请选择']) ?>

    <?= $form->field($model, 'line_id')->label("公交线路")->dropDownList($bus_line_array, ['prompt' => '请选择']) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
