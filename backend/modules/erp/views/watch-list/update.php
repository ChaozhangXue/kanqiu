<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WatchList */

$this->title = 'Update Watch List: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Watch Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="watch-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
