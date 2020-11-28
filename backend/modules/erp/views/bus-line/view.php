<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BusLine */

$this->params['breadcrumbs'][] = ['label' => 'Bus Lines', 'url' => ['index']];
\yii\web\YiiAsset::register($this);
?>
<div class="bus-line-view">


    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'station_name',
            'station_num',
            'start_point',
            'end_point',
            'create_people',
            'area',
            [
                'attribute' => 'station_list',
                'label' => '站点列表',
                'value' => function ($model) {
                    $list = "";
                    if(!empty($model->station_list)){
                        $station_ary = explode(',',$model->station_list);
                        $station = \common\models\BusStation::find()
                            ->where(['in', 'id', $station_ary])
                            ->indexBy('id')
                            ->asArray()
                            ->all();

                        foreach ($station_ary as $station_id){
                            if(isset($station[$station_id])){
                                $list .= $station[$station_id]['station_name'] . "|";
                            }
                        }

                    }
                    return $list;
                },
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
