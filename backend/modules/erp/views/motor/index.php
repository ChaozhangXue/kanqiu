<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MotorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '机动车管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="motor-index">

    <p>
        <?= Html::a('创建机动车', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'brand',
            'model',
            'card',
            'color',
            [
                'attribute' => 'site_num',
                'label' => '座位数量',
                'filter' => Yii::$app->params['seat_type_list'],
                'value' => function ($model) {
                    return Yii::$app->params['seat_type_list'][$model->site_num];
                },
            ],
            'num',
            [
                'attribute' => 'car_type',
                'label' => '车辆类型',
                'filter' => Yii::$app->params['car_type'],
                'value' => function ($model) {
                    return Yii::$app->params['car_type'][$model->car_type];
                },
            ],
            'buy_time',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
