<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Driver */
$this->title = ($model['type'] == 1 ? '公交司机' : '客运司机') . '-' . $model['realname'];
?>
<div class="driver-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'realname',
            'gender',
            'birth_date',
            'dept',
            'job_position',
            'employment_time',
            'license',
            'mobile',
            'idcard',
            'entry_time:date',
            'create_time:datetime',
            'update_time:datetime',
        ],
    ]) ?>

</div>
