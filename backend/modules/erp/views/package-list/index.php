<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PackageListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '包裹条码数据';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="package-list-index">
        <p>
            <?= Html::a('创建包裹条码数据', ['create'], ['class' => 'btn btn-success']) ?>
        <div class="btn btn-primary print"
             data-url="<?= Yii::$app->urlManager->createUrl(['/erp/package-list/get-un-print']); ?>">打印未打印的包裹条码
        </div>
        </p>


        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'package_id',
                [
                    'attribute' => 'is_print',
                    'filter' => Yii::$app->params['is_print'],
                    'label' => "是否打印",
                    'value' => function ($model) {
                        return Yii::$app->params['is_print'][$model->is_print];
                    },
                ],
                [
                    'label' => '操作',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $html = [];
                        $html [] = Html::a('<i class="glyphicon glyphicon-print"></i>', 'javascript:void(0)', [
                            'class' => 'reprint-btn',
                            'data-title' => '打印',
                            'data-id' => $model['id'],
                            'data-url' => Yii::$app->urlManager->createUrl(['/erp/package-list/re-print', 'id' => $model['id']]),
                        ]);
                        return implode(' ', $html);
                    }
                ],
            ]
        ]); ?>
    </div>

<?php $this->registerJsFile('@web/js/erp/list.js', ['depends' => ['backend\assets\AppAsset']]); ?>