<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceStationAccount */

$this->title = Yii::t('app', 'Create Service Station Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Service Station Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-station-account-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
