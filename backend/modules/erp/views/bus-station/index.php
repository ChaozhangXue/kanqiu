<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BusStationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '公交站点';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bus-station-index">


    <p>
        <?= Html::a('创建公交站点', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'station_name',
            'up_point',
            'down_point',
            'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
