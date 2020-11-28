<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceStationRepair */
\yii\web\YiiAsset::register($this);
$service = new \common\services\BaseService();
?>
<div class="service-station-repair-view">
    <p>
        <?php if ($model->status == 0): ?>
    <div class="btn btn-primary feedback-btn" data-id="<?= $model['id'] ?>"
         data-url="<?= Yii::$app->urlManager->createUrl(['/erp/service-station-repair/feedback']) ?>">回复
    </div>
    <?php endif; ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'phone',
            [
                'attribute' => 'create_time',
                'label' => '报修时间',
                'value' => function ($model) {
                    return $model->create_time ? date('Y-m-d H:i:s', $model->create_time) : '';
                },
            ],
            [
                'attribute' => 'repair_type',
                'label' => '报修类型',
                'value' => function ($model) {
                    $list = ['2' => '公交站点', '3' => '服务站点'];
                    return $list[$model->customer->type];
                }
            ],
            [
                'attribute' => 'repair_station',
                'label' => '报修站点',
                'value' => function ($model) {
                    if ($model->customer->type == 2) {
                        $station = \common\models\BusStation::findOne($model->repair_station);
                        $name = $station['station_name'];
                    } else {
                        $station = \common\models\ServiceStation::findOne($model->repair_station);
                        $name = $station['station_name'];
                    }
                    return $name;
                }
            ],
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

