<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServiceStationRepairSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$service = new \common\services\BaseService();
?>
<div class="service-station-repair-index">

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
            [
                'attribute' => 'repair_type',
                'label' => '报修类型',
                'filter' => ['2' => '公交站点', '3' => '服务站点'],
                'value' => function ($model) {
                    $list = ['2' => '公交站点', '3' => '服务站点'];
                    return $list[$model->customer->type];
                }
            ],
            [
                'attribute' => 'repair_station',
                'label' => '报修站点',
                'value' => function ($model) {
                    if ($model->customer->type == 2) {
                        $station = \common\models\BusStation::findOne($model->repair_station);
                        $name = $station['station_name'];
                    } else {
                        $station = \common\models\ServiceStation::findOne($model->repair_station);
                        $name = $station['station_name'];
                    }
                    return $name;
                }
            ],
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
                    $url = Yii::$app->urlManager->createUrl(['/erp/service-station-repair/view', 'id' => $data['id']]);
                    $html .= Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, ['data-title' => '查看']);
                    if ($data['status'] == '0') {
                        $url = Yii::$app->urlManager->createUrl(['/erp/service-station-repair/feedback']);
                        $html .= Html::a('<i class="glyphicon glyphicon-ok"></i>', 'javascript:void(0)', ['data-title' => '受理', 'class' => 'feedback-btn', 'data-id' => $data['id'], 'data-status' => 1, 'data-url' => $url]);
                    }
                    return $html;
                }
            ]
        ],
    ]); ?>

</div>
<?php $this->registerJsFile('@web/js/erp/suggest.js', ['depends' => ['backend\assets\AppAsset']]); ?>

