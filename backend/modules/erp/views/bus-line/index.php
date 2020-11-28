<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BusLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '公交线路';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bus-line-index">
    <p>
        <?= Html::a('创建公交线路', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'station_name',
            'station_num',
            'start_point',
            'end_point',
            'create_people',
            'area',
            [
                'attribute' => 'station_list',
                'label' => '站点列表',
                'value' => function ($model) {
                    $list = "";
                    if(!empty($model->station_list)){
                        $station_ary = explode(',',$model->station_list);
                        $station = \common\models\BusStation::find()
                            ->where(['in', 'id', $station_ary])
                            ->indexBy('id')
                            ->asArray()
                            ->all();

                        foreach ($station_ary as $station_id){
                            if(isset($station[$station_id])){
                                $list .= $station[$station_id]['station_name'] . "|";
                            }
                        }

                    }
                    return $list;
                },
            ],
            'created_at',
            'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
