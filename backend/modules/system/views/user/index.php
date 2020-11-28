<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="user-index">
    <p>
        <?= Html::a('创建用户', ['edit'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view table-responsive'],
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'username',
            'realname',
            'email',
            'mobile',
            'dept',
            'job_position',
            [
                'attribute' => 'status',
                'filter' => [10 => '可用', 0 => '禁用'],
                'value' => function ($model) {
                    return $model->status == 10 ? '可用' : '禁用';
                },
            ],
            [
                'label' => '操作',
                'format' => 'raw',
                'value' => function ($data) {
                    $html = '';
                    if ($data['identity'] == 0 && $data['id'] != Yii::$app->user->id) {
                        $url = Yii::$app->urlManager->createUrl(['/system/user/reset-password']);
                        $html .= Html::a('<i class="glyphicon glyphicon-repeat"></i>', '#', ['title' => '重置密码', 'class' => 'reset-password-btn', 'data-url' => $url, 'data-id' => $data['id']]);
                    }
                    $url = Yii::$app->urlManager->createUrl(['/system/user/edit', 'id' => $data['id']]);
                    $html .= Html::a('<i class="glyphicon glyphicon-pencil"></i>', $url, ['title' => '编辑']);
                    $url = Yii::$app->urlManager->createUrl(['/system/user/set-status']);
                    if ($data['status'] == 10) {
                        $html .= Html::a('<i class="glyphicon glyphicon-ban-circle"></i>', '#', ['title' => '禁用', 'class' => 'set-status-btn', 'data-id' => $data['id'], 'data-status' => 0, 'data-url' => $url]);
                    } else {
                        $html .= Html::a('<i class="glyphicon glyphicon-ok"></i>', '#', ['title' => '启用', 'class' => 'set-status-btn', 'data-id' => $data['id'], 'data-status' => 10, 'data-url' => $url]);
                    }
                    return $html;

                }
            ]
        ],
    ]); ?>
</div>
<?php $this->registerJsFile('@web/js/system/user.js', ['depends' => ['backend\assets\AppAsset']]); ?>
