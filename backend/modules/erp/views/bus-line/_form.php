<?php

use common\models\BusStation;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BusLine */
/* @var $form yii\widgets\ActiveForm */

$bus_station = BusStation::find()->select(['id', 'station_name'])->all();
if(empty($bus_station)){
    $bus_station_array = [];
}
foreach ($bus_station as $val) {
    $bus_station_array[$val['id']] = $val['station_name'];
}

?>

<div class="bus-line-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'station_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'start_time')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'end_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>

    <table class="table table-bordered" id="rule-table">
        <tr class="title-tr">
            <td>站点名</td>
            <td>操作</td>
        </tr>


                    <?php if(isset($model->station_list)) {?>
                         <?php $list = explode(',', $model->station_list);
                         foreach ($list as $val) {?>
                             <tr class="data-tr">
                                 <td>
                                    <select class="form-control" style="width:200px" id="station" name="BusLine[station][]">
                                        <option value="">请选择</option>
                                        <?php foreach ($stations as $key => $v) {?>
                                            <option value="<?= $key?>"  <?= ($key == $val) ? "selected":''?>><?=$v['station_name']?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                 <div class="glyphicon glyphicon-plus"></div>
                                 <div class="glyphicon glyphicon-minus"></div>
                                </td>
                            </tr>
                         <?php } ?>
                    <?php }else{?>
                            <tr class="data-tr">
                                <td>
                                <select class="form-control" style="width:200px" id="station" name="BusLine[station][]">
                                    <option value="">请选择</option>
                                    <?php foreach ($stations as $key => $v) {?>
                                        <option value="<?= $key?>"><?=$v['station_name']?></option>
                                    <?php } ?>
                                </select>
                                </td>
                                <td>
                                    <div class="glyphicon glyphicon-plus"></div>
                                    <div class="glyphicon glyphicon-minus"></div>
                                </td>
                            </tr>
                     <?php }?>

    <!--                <td>-->
    <!--                    <div class="glyphicon glyphicon-plus"></div>-->
    <!--                    <div class="glyphicon glyphicon-minus"></div>-->
    <!--                </td>-->
<!--            </tr>-->
    </table>


    <table class="hidden">
        <tbody id="empty-tr">
        <tr class="data-tr">
            <td>
                <select class="form-control" style="width:200px" id="station" name="BusLine[station][]">
                    <option value="">请选择</option>
                    <?php foreach ($stations as $key => $v) {?>
                        <option value="<?= $key?>"><?=$v['station_name']?></option>
                    <?php } ?>
                </select>

            </td>
            <td>
                <div class="glyphicon glyphicon-plus"></div>
                <div class="glyphicon glyphicon-minus"></div>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJsFile('@web/js/erp/bus-line.js', ['depends' => ['backend\assets\AppAsset']]); ?>
