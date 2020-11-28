<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PackageList */

$this->title = '创建包裹条码';
$this->params['breadcrumbs'][] = ['label' => '包裹条码', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="package-list-create">

    <div class="package-list-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'package_id')->label("生成数量")->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
