<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceStationAccount */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Service Station Accounts'), 'url' => ['index']];
\yii\web\YiiAsset::register($this);
?>
<div class="service-station-account-view">


    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'order_num',
            'station_name',
            'in_charge_name',
            'order_time',
            'total_account',
            'yongjin',
            'package_num',
            'verify_time',
            'status',
            'bill_time',
            'create_time:datetime',
            'update_time:datetime',
        ],
    ]) ?>

</div>
