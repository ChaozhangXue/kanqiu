<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PackageOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '包裹数据';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="package-order-index">

    <p>
        <?= Html::a('创建包裹数据', ['create'], ['class' => 'btn btn-success']) ?>
        <button id="generate-btn" type="submit" class="btn btn-primary" data-url=<?=Yii::$app->urlManager->createUrl(['/erp/order/generate']);?>>自动生成订单</button>
        <?= Html::a('计费规则', ['money-rule'], ['class' => 'btn btn-info']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view table-responsive text-nowrap', 'id' => 'list-table'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'delivery_num',
            'package_list_id',
            'order_num',
            'sender',
            'sender_phone',
            'send_address',
            'receiver',
            'receiver_phone',
            'receive_address',
            //'type',
            //'weight',
            //'size',
            //'distance',
            //'express_company',
            //'is_on_door',
            //'sender_backup:ntext',
            'deliver_time',
            'station_time',
            'receiver_time',
            [
                'attribute' => 'source',
                'label' => '订单来源',
                'filter' => Yii::$app->params['order_source'],
                'value' => function ($model) {
                    return Yii::$app->params['order_source'][$model->source];
                },
            ],
            'total_account',
            [
                'attribute' => 'status',
                'label' => '包裹状态',
                'filter' => Yii::$app->params['package_status_list'],
                'value' => function ($model) {
                    if($model->station_check == 1 && $model->status == 2){
                        return "待审核";
                    }
                    if($model->station_check == 2 && $model->status == 2){
                        return "已审核";
                    }
                    return Yii::$app->params['package_status_list'][$model->status];
                },
            ],            //'submit_station_id',
            //'submit_station_name',
            //'receive_station_id',
            //'receive_station_name',
            //'created_at',
            //'updated_at',

//            ['class' => 'yii\grid\ActionColumn'],

        [
                'label' => '操作',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = [];
                    $url = Yii::$app->urlManager->createUrl(['/erp/package-order/view', 'id' => $model['id']]);
                    $html [] = Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, ['data-title' => '查看']);

                    if (in_array($model['status'], [Yii::$app->params['package_status']['wait'],]) && $model['source'] == 2) {
                          $html [] = Html::a('<i class="glyphicon glyphicon-barcode"></i>', 'javascript:void(0)', [
                              'class' => 'bind-btn',
                              'data-title' => "绑定包裹码",
                              'data-id' => $model['id'],
                              'data-url' => Yii::$app->urlManager->createUrl(['/erp/package-order/bind-list']),
                          ]);
                    }

                    if (in_array($model['status'], [Yii::$app->params['package_status']['wait_arrive_center'],]) && $model['source'] == 1 && empty($model['package_list_id'])) {
                        $html [] = Html::a('<i class="glyphicon glyphicon-barcode"></i>', 'javascript:void(0)', [
                            'class' => 'bind-btn',
                            'data-title' => "绑定包裹码",
                            'data-id' => $model['id'],
                            'data-url' => Yii::$app->urlManager->createUrl(['/erp/package-order/bind-list']),
                        ]);
                    }
                    return implode(' ', $html);
                }
            ],
//            'headerOptions' => ['width' => '80'],
        ],
    ]); ?>
</div>
<?php $this->registerJsFile('@web/js/erp/package-order.js', ['depends' => ['backend\assets\AppAsset']]); ?>

