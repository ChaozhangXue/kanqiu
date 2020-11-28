<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ServiceStationAccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Service Station Accounts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-station-account-index">


    <p>
        <?= Html::a(Yii::t('app', 'Create Service Station Account'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'order_num',
            'station_name',
            'in_charge_name',
            'order_time',
            //'total_account',
            //'yongjin',
            //'package_num',
            //'verify_time',
            //'status',
            //'bill_time',
            //'create_time:datetime',
            //'update_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
