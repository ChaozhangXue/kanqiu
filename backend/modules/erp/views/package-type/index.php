<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PackageTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '包裹类型';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="package-type-index">

    <p>
        <?= Html::a('创建包裹类型', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'type_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
