<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BusRepair */
\yii\web\YiiAsset::register($this);
$service = new \common\services\BaseService();
?>
<div class="bus-repair-view">
    <p>
        <?php if ($model->status == 0): ?>
    <div class="btn btn-primary feedback-btn" data-id="<?= $model['id'] ?>"
         data-url="<?= Yii::$app->urlManager->createUrl(['/erp/bus-repair/feedback']) ?>">回复
    </div>
    <?php endif; ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'name',
            'phone',
            [
                'attribute' => 'create_time',
                'label' => '报修时间',
                'value' => function ($model) {
                    return $model->create_time ? date('Y-m-d H:i:s', $model->create_time) : '';
                },
            ],
            'repair_card',
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
            'feedback_msg:ntext',
            [
                'attribute' => 'status',
                'filter' => [0 => '未受理', 1 => '已受理'],
                'value' => function ($model) {
                    $statusList = [0 => '未受理', 1 => '已受理'];
                    return $statusList[$model->status];
                },
            ],
        ],
    ]) ?>
</div>
<?php $this->registerJsFile('@web/js/erp/suggest.js', ['depends' => ['backend\assets\AppAsset']]); ?>
