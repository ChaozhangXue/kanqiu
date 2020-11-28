<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BusRepairSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$service = new \common\services\BaseService();
?>
<div class="bus-repair-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view table-responsive text-nowrap', 'id' => 'list-table'],
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'phone',
            [
                'attribute' => 'create_time',
                'label' => '报修时间',
                'value' => function ($model) {
                    return $model->create_time ? date('Y-m-d H:i:s', $model->create_time) : '';
                },
            ],
            'repair_card',
            'reason:ntext',
            [
                'attribute' => 'pic',
                'format' => 'html',
                'value' => function ($model) {
                    $picList = $model->pic ? explode(',', $model->pic) : [];
                    $html = '';
                    foreach ($picList as $v) {
                        $html .= Html::img($v, ['style' => 'width:80px;height:80px;margin-left:5px']);
                    }
                    return $html;
                },
            ],
            'remark:ntext',
            [
                'attribute' => 'status',
                'filter' => [0 => '未受理', 1 => '已受理'],
                'value' => function ($model) {
                    $statusList = [0 => '未受理', 1 => '已受理'];
                    return $statusList[$model->status];
                },
            ],
            [
                'label' => '操作',
                'format' => 'raw',
                'value' => function ($data) {
                    $html = '';
                    $url = Yii::$app->urlManager->createUrl(['/erp/bus-repair/view', 'id' => $data['id']]);
                    $html .= Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, ['data-title' => '查看']);
                    if ($data['status'] == '0') {
                        $url = Yii::$app->urlManager->createUrl(['/erp/bus-repair/feedback']);
                        $html .= Html::a('<i class="glyphicon glyphicon-ok"></i>', 'javascript:void(0)', ['data-title' => '受理', 'class' => 'feedback-btn', 'data-id' => $data['id'], 'data-status' => 1, 'data-url' => $url]);
                    }
                    return $html;
                }
            ]
        ],
    ]); ?>
</div>
<?php $this->registerJsFile('@web/js/erp/suggest.js', ['depends' => ['backend\assets\AppAsset']]); ?>
