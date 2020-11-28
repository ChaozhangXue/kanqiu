<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '公交管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bus-index">
    <p>
        <?= Html::a('创建公交管理', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'brand',
            'model',
            'card',
            'color',
            //'num',
            [
                'attribute' => 'car_type',
                'label' => '车辆类型',
                'filter' => Yii::$app->params['car_type'],
                'value' => function ($model) {
                    return Yii::$app->params['car_type'][$model->car_type];
                },
            ],
            //'buy_time',
            //'dept',
            [
                "attribute" => "created_at",
                'label' => "创建时间",
                'value' => function ($model) {
                    return $model->created_at;
                },
            ],
            [
                "attribute" => "updated_at",
                'label' => "修改时间",
                'value' => function ($model) {
                    return $model->updated_at;
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
