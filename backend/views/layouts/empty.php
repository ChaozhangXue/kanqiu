<?php
\backend\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,maximum-scale=1.0, initial-scale=1, user-scalable=0">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?=\yii\helpers\Html::encode(Yii::$app->name)?></title>
    <?php $this->head() ?>
    <?php $this->registerJsFile('@web/js/bootstrap.min.js', ['depends' => ['backend\assets\AppAsset']]); ?>
    <?php $this->registerJsFile('@web/js/jquery.validate.js', ['depends' => ['backend\assets\AppAsset']]); ?>
    <?php $this->registerJsFile('@web/js/toastr.js', ['depends' => ['backend\assets\AppAsset']]); ?>

</head>
<body>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>