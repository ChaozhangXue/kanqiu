<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\DriverSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="driver-index">
    <p>
        <?php if ($driverType == 1): ?>
            <?= Html::a('创建公交司机', ['edit-bus'], ['class' => 'btn btn-success']) ?>
        <?php elseif ($driverType == 2): ?>
            <?= Html::a('创建客运司机', ['edit-transport'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view table-responsive text-nowrap', 'id' => 'list-table'],
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'realname',
            [
                'attribute' => 'gender',
                'filter' => [1 => '男', 2 => '女'],
                'value' => function ($model) {
                    return $model->gender == 1 ? '男' : '女';
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
            'dept',
            'job_position',
            'employment_time',
            [
                'attribute' => 'license',
                'filter' => Yii::$app->params['driving_license_list'],
                'visible' => $driverType == 2
            ],
            'mobile',
            'idcard',
            [
                'attribute' => 'entry_time',
                'value' => function ($model) {
                    return $model->entry_time ? date('Y-m-d', $model->entry_time) : '';
                },
            ],
            [
                'attribute' => 'create_time',
                'value' => function ($model) {
                    return $model->create_time ? date('Y-m-d H:i:s', $model->create_time) : '';
                },
            ],
            [
                'label' => '操作',
                'format' => 'raw',
                'value' => function ($data) use ($driverType) {
                    $viewUrl = 'view-bus';
                    $editUrl = 'edit-bus';
                    $deleteUrl = 'delete-bus';
                    if ($driverType == 2) {
                        $viewUrl = 'view-transport';
                        $editUrl = 'edit-transport';
                        $deleteUrl = 'delete-transport';
                    }
                    $btns = [];
                    $btns [] = Html::a('<i class="glyphicon glyphicon-eye-open"></i>', [$viewUrl, 'id' => $data['id']], ['data-title' => '查看']);
                    $btns [] = Html::a('<i class="glyphicon glyphicon-pencil"></i>', [$editUrl, 'id' => $data['id']], ['data-title' => '编辑']);
                    $btns[] = Html::a('<i class="glyphicon glyphicon-trash"></i>', [$deleteUrl, 'id' => $data['id']], ['data-title' => '删除', 'data-confirm' => '确认删除?']);
                    return implode(' ', $btns);

                }
            ]
        ],
    ]); ?>


</div>
