<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StationAllocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '站点配置');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="station-allocation-index">

    <p>
        <?= Html::a(Yii::t('app', '创建站点配置'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute' => 'bus_id',
                'label' => '公交站点',
                'value' => function ($model) {
                    $bus_station = \common\models\BusStation::find()->where(['id' => $model->bus_id])->one();
                    if(!empty($bus_station)){
                        return $bus_station->station_name;
                    }else{
                        return '';
                    }
                },
            ],

//            'line_id',
            'line_name',
//            'service_id',
            'service_name',
            'in_charge_name',
            'telphone',
            'build_time',
            'create_people',
            //'create_time:datetime',
            //'update_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
