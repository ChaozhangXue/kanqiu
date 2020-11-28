<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
    <div class="menu-index">
        <p>
            <?= Html::a('创建菜单', ['edit'], ['class' => 'btn btn-success']) ?>
        </p>

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'grid-view table-responsive'],
            'columns' => [
                'id',
                'name',
                'url',
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
                        $url = Yii::$app->urlManager->createUrl(['/system/menu/edit', 'id' => $data['id']]);
                        $html = Html::a('<i class="glyphicon glyphicon-pencil"></i>', $url, ['title' => '编辑']);
                        $url = Yii::$app->urlManager->createUrl(['/system/menu/set-status']);
                        if ($data['status'] == 1) {
                            $html .= Html::a('<i class="glyphicon glyphicon-ban-circle"></i>', '#', ['title' => '禁用', 'class' => 'set-status-btn', 'data-id' => $data['id'], 'data-status' => 0, 'data-url' => $url]);
                        } else {
                            $html .= Html::a('<i class="glyphicon glyphicon-ok"></i>', '#', ['title' => '启用', 'class' => 'set-status-btn', 'data-id' => $data['id'], 'data-status' => 1, 'data-url' => $url]);

                        }
                        return $html;

                    }
                ]
            ],
        ]); ?>
    </div>
<?php $this->registerJsFile('@web/js/system/menu.js', ['depends' => ['backend\assets\AppAsset']]); ?>