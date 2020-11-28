<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\DriverBillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
    <div class="driver-bill-index">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'grid-view table-responsive text-nowrap', 'id' => 'list-table'],
            'filterModel' => $searchModel,
            'columns' => [
                'id',
                'order_no',
                'driver_name',
                [
                    'attribute' => 'order_time',
                    'value' => function ($model) {
                        return $model->order_time ? date('Y-m-d H:i:s', $model->order_time) : '';
                    },
                ],
                'amount',
                'commission',
                'package_num',
                [
                    'attribute' => 'verify_time',
                    'value' => function ($model) {
                        return $model->verify_time ? date('Y-m-d H:i:s', $model->verify_time) : '';
                    },
                ],
                [
                    'attribute' => 'status',
                    'filter' => ['1' => '未审核', '2' => '已审核'],
                    'contentOptions' => [
                        'class' => 'status',
                    ],
                    'value' => function ($model) {
                        return $model->status == 1 ? '未审核' : '已审核';
                    },
                ],
                [
                    'label' => '操作',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $html = [];
                        $html [] = Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['/erp/driver-bill/view', 'id' => $model['id']], ['data-title' => '查看']);
                        if ($model['status'] == 1) {
                            $url = Yii::$app->urlManager->createUrl(['/erp/driver-bill/check']);
                            $html [] = Html::a('<i class="glyphicon glyphicon-ok-circle"></i>', 'javascript:void(0)', ['data-title' => '审核', 'class' => 'check-btn', 'data-id' => $model['id'], 'data-url' => $url]);
                        }
                        return implode(' ', $html);
                    }
                ],
            ],
        ]); ?>
    </div>
<?php $this->registerJsFile('@web/js/erp/driver-bill.js', ['depends' => ['backend\assets\AppAsset']]); ?>