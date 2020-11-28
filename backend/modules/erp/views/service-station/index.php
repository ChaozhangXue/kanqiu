<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ServiceStationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务站点';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-station-index">

    <p>
        <?= Html::a('创建服务站点', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'country',
            'station_name',
            'address',
//            'longitude',
            //'latitude',
            //'entity',
            'in_charge_name',
            //'id_card',
            //'build_size',
            //'code',
            'people_num',
            'telephone',
            'build_time',
            //'service_time',
            'backup',
//            'create_time:datetime',
            //'update_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
