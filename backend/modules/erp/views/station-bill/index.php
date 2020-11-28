<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\StationBillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '站点结算';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="station-bill-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'order_id',
            'station_name',
            'owner',
            'time',
            'money',
            'yongjin',
            'package_num',
            'verify_time',
            [
                'attribute' => 'status',
                'label' => '结算状态',
                'filter' => Yii::$app->params['station_bill_status'],
                'value' => function ($model) {
                    return Yii::$app->params['station_bill_status'][$model->status];
                },
            ],
            'bill_time',

            [
                'label' => '操作',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = [];
                    $html [] = Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['/erp/station-bill/view', 'id' => $model['id']], ['data-title' => '查看']);
                    if ($model['status'] == 0) {
                        $url = Yii::$app->urlManager->createUrl(['/erp/station-bill/verify']);
                        $html [] = Html::a('<i class="glyphicon glyphicon-ok-circle"></i>', 'javascript:void(0)', ['data-title' => '审核', 'class' => 'check-btn', 'data-id' => $model['id'], 'data-url' => $url]);
                    }
                    return implode(' ', $html);
                }
            ],
        ],
    ]); ?>


</div>
<?php $this->registerJsFile('@web/js/erp/station-bill.js', ['depends' => ['backend\assets\AppAsset']]); ?>