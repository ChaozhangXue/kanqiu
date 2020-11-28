<?php

use yii\grid\GridView;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\SuggestionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="suggestion-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view table-responsive text-nowrap', 'id' => 'list-table'],
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'label' => '微信昵称',
            ],
            'phone',
            [
                'attribute' => 'create_time',
                'label' => '上报时间',
                'value' => function ($model) {
                    return $model->create_time ? date('Y-m-d H:i:s', $model->create_time) : '';
                },
            ],
            'detail:ntext',
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
                    $url = Yii::$app->urlManager->createUrl(['/erp/suggestion/view','id'=>$data['id']]);
                    $html .= Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, ['data-title' => '查看']);
                    if ($data['status'] == '0') {
                        $url = Yii::$app->urlManager->createUrl(['/erp/suggestion/feedback']);
                        $html .= Html::a('<i class="glyphicon glyphicon-ok"></i>', 'javascript:void(0)', ['data-title' => '受理', 'class' => 'feedback-btn', 'data-id' => $data['id'], 'data-status' => 1, 'data-url' => $url]);
                    }
                    return $html;
                }
            ]
        ],
    ]); ?>
</div>
<?php $this->registerJsFile('@web/js/erp/suggest.js', ['depends' => ['backend\assets\AppAsset']]); ?>
