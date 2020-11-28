<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceStationAccount */

$this->title = Yii::t('app', '编辑服务站点');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Service Station Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', '编辑');
?>
<div class="service-station-account-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
