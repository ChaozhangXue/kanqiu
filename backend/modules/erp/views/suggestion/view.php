<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Suggestion */
?>
    <div class="suggestion-view">
        <p>
            <?php if ($model->status == 0): ?>
        <div class="btn btn-primary feedback-btn" data-id="<?= $model['id'] ?>"
             data-url="<?= Yii::$app->urlManager->createUrl(['/erp/suggestion/feedback']) ?>">受理
        </div>
        <?php endif; ?>
        </p>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
//                'id',
                'name',
                'phone',
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
                    'attribute' => 'create_time',
                    'label' => '上报时间',
                    'value' => function ($model) {
                        return $model->create_time ? date('Y-m-d H:i:s', $model->create_time) : '';
                    },
                ],
                'feedback_msg:ntext',
            ],
        ]) ?>
    </div>
<?php $this->registerJsFile('@web/js/erp/suggest.js', ['depends' => ['backend\assets\AppAsset']]); ?>