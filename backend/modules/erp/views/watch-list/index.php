<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\WatchListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Watch Lists';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="watch-list-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Watch List', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'game_date',
            'game_time',
            'team1',
            'team2',
            //'game_link:ntext',
            //'create_time',
            //'update_time',
            //'expire_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
