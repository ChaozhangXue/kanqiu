<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="role-index">
    <p>
        <?= Html::a('创建角色', ['edit'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view table-responsive'],
        'columns' => [
            'id',
            'name',
            'desc',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status == 1 ? '可用' : '禁用';
                },
            ],
            [
                'attribute' => 'create_time',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->create_time);
                },
            ],

            [
                'label' => '操作',
                'format' => 'raw',
                'value' => function ($data) {
                    $html = '<a href="' . Yii::$app->urlManager->createUrl(['system/role/edit', 'id' => $data['id']]) . '" class="btn btn-default btn-sm">
                    <i class="glyphicon glyphicon-pencil"></i> 编辑
                    </a>
                    <a href="' . Yii::$app->urlManager->createUrl(['system/role/set-role-menu', 'id' => $data['id']]) . ' "class="btn btn-default btn-sm">
                    <i class="glyphicon glyphicon-align-justify"></i> 设置角色权限
                    </a>
                    <a href="' . Yii::$app->urlManager->createUrl(['system/role/set-role-user', 'id' => $data['id']]) . '" class="btn btn-default btn-sm">
                    <i class="glyphicon glyphicon-user"></i> 设置角色用户
                    </a>';
                    return $html;

                }
            ]
        ],
    ]); ?>


</div>
<?php $this->registerJsFile('@web/js/system/role.js', ['depends' => ['backend\assets\AppAsset']]); ?>