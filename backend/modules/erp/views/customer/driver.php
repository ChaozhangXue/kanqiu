<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="customer-index">
    <p>
        <?= Html::a('创建司机', ['edit-driver'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view table-responsive text-nowrap', 'id' => 'list-table'],
        'filterModel' => $searchModel,
        'columns' => [
            'customer_id',
            'username',
            'realname',
            [
                'attribute' => 'gender',
                'value' => function ($model) {
                    return $model->status == 1 ? '男' : '女';
                },
            ],
            [
                'attribute' => 'birth_date',
                'label' => '年龄',
                'value' => function ($model) {
                    $interval = date_diff(date_create($model->birth_date), date_create());
                    return $interval->y;
                },
            ],
            'mobile',
            [
                'attribute' => 'create_time',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->create_time);
                },
            ],
            [
                'attribute' => 'status',
                'filter' => [1 => '可用', 0 => '禁用'],
                'value' => function ($model) {
                    return $model->status == 1 ? '可用' : '禁用';
                },
            ],
            [
                'attribute' => 'last_login_time',
                'value' => function ($model) {
                    return $model->last_login_time ? date('Y-m-d H:i:s', $model->last_login_time) : '';
                },
            ],
            [
                'label' => '操作',
                'format' => 'raw',
                'value' => function ($data) {
                    $html = '';
                    $url = Yii::$app->urlManager->createUrl(['/erp/customer/reset-driver-password']);
                    $html .= Html::a('<i class="glyphicon glyphicon-repeat"></i>', '#', ['data-title' => '重置司机密码', 'class' => 'reset-password-btn', 'data-url' => $url, 'data-id' => $data['customer_id']]);
                    $url = Yii::$app->urlManager->createUrl(['/erp/customer/view-driver', 'customer_id' => $data['customer_id']]);
                    $html .= Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, ['data-title' => '查看']);
                    $url = Yii::$app->urlManager->createUrl(['/erp/customer/edit-driver', 'customer_id' => $data['customer_id']]);
                    $html .= Html::a('<i class="glyphicon glyphicon-pencil"></i>', $url, ['data-title' => '编辑']);
                    $url = Yii::$app->urlManager->createUrl(['/erp/customer/set-driver-status']);
                    if ($data['status'] == 1) {
                        $html .= Html::a('<i class="glyphicon glyphicon-ban-circle"></i>', 'javascript:void(0)', ['data-title' => '禁用', 'class' => 'set-status-btn', 'data-id' => $data['customer_id'], 'data-status' => 0, 'data-url' => $url]);
                    } else {
                        $html .= Html::a('<i class="glyphicon glyphicon-ok"></i>', 'javascript:void(0)', ['data-title' => '启用', 'class' => 'set-status-btn', 'data-id' => $data['customer_id'], 'data-status' => 1, 'data-url' => $url]);
                    }
                    return $html;

                }
            ]
        ],
    ]); ?>
</div>
<?php $this->registerJsFile('@web/js/erp/customer.js', ['depends' => ['backend\assets\AppAsset']]); ?>
