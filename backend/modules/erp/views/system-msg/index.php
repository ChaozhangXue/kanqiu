<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\SystemMsgSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
    <div class="system-msg-index">

        <p>
            <?= Html::a(Yii::t('app', '发布新消息'), ['edit'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'grid-view table-responsive text-nowrap', 'id' => 'list-table'],
            'filterModel' => $searchModel,
            'columns' => [
                'id',
                'title',
                [
                    'attribute' => 'type',
                    'filter' => Yii::$app->params['message_type_list'],
                    'value' => function ($model) {
                        return Yii::$app->params['message_type_list'][$model->type];
                    },
                ],
                [
                    'attribute' => 'customer_type',
                    'filter' => Yii::$app->params['customer_type_list'],
                    'value' => function ($model) {
                        return $model->customer_type != 0 ? Yii::$app->params['customer_type_list'][$model->customer_type] : '';
                    },
                ],
                'content:ntext',
                [
                    'attribute' => 'publish_time',
                    'label' => '发布时间',
                    'value' => function ($model) {
                        return $model->publish_time ? date('Y-m-d H:i:s', $model->publish_time) : '';
                    },
                ],
                [
                    'attribute' => 'status',
                    'filter' => Yii::$app->params['message_status_list'],
                    'value' => function ($model) {
                        return Yii::$app->params['message_status_list'][$model->status];
                    },
                ],
                'publisher',
                [
                    'attribute' => 'create_time',
                    'label' => '创建时间',
                    'value' => function ($model) {
                        return $model->create_time ? date('Y-m-d H:i:s', $model->create_time) : '';
                    },
                ],
                [
                    'label' => '操作',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $html = [];
                        $html[] = Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['/erp/system-msg/view', 'id' => $data['id']], ['data-title' => '查看']);
                        if ($data['status'] == '1' && $data['publish_time'] > time()) {
                            $html[] = Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['/erp/system-msg/edit', 'id' => $data['id']], ['data-title' => '编辑']);
                        }
                        if ($data['status'] == 3) {
                            $url = Yii::$app->urlManager->createUrl(['/erp/system-msg/add-to-send']);
                            $html[] = Html::a('<i class="glyphicon glyphicon-repeat"></i>', 'javascript:void(0)', ['data-title' => '加入队列', 'class' => 'repeat-btn', 'data-url' => $url, 'data-id' => $data['id']]);
                        }
                        $html[] = Html::a('<i class="glyphicon glyphicon-trash"></i>', ['/erp/system-msg/delete', 'id' => $data['id']], ['data-title' => '删除']);
                        return implode(' ', $html);
                    }
                ],
            ],
        ]); ?>
    </div>
<?php $this->registerJsFile('@web/js/erp/system-msg.js', ['depends' => ['backend\assets\AppAsset']]); ?>