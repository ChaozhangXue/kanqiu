<?php

/* @var $this \yii\web\View */

/* @var $content string */

use backend\assets\AppAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode(Yii::$app->name) ?></title>
    <?php $this->head() ?>
    <?php $this->registerCssFile('@web/css/iconfont.css', ['depends' => ['backend\assets\AppAsset']]); ?>
    <?php $this->registerCssFile('@web/css/select2.min.css', ['depends' => ['backend\assets\AppAsset']]); ?>
    <?php $this->registerCssFile('@web/css/ztree.css', ['depends' => ['backend\assets\AppAsset']]); ?>
    <?php $this->registerCssFile('@web/css/bootstrap4.css', ['depends' => ['backend\assets\AppAsset']]); ?>
    <?php $this->registerCssFile('@web/css/main.css', ['depends' => ['backend\assets\AppAsset']]); ?>
    <?php $this->registerJsFile('@web/js/jquery.validate.js', ['depends' => ['backend\assets\AppAsset']]); ?>
    <?php $this->registerJsFile('@web/js/ztree.core.js', ['depends' => ['backend\assets\AppAsset']]); ?>
    <?php $this->registerJsFile('@web/js/ztree.excheck.js', ['depends' => ['backend\assets\AppAsset']]); ?>
    <?php $this->registerJsFile('@web/js/select2.min.js', ['depends' => ['backend\assets\AppAsset']]); ?>
    <?php $this->registerJsFile('@web/js/main.js', ['depends' => ['backend\assets\AppAsset']]); ?>
    <?php $this->registerJsFile('@web/js/toastr.js', ['depends' => ['backend\assets\AppAsset']]); ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap" style="padding: 0;margin: 0">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
            'style' => 'margin: 0;position: -webkit-sticky; position:sticky;border-bottom:0'
        ],
    ]);
    $menuService = new \backend\modules\system\services\MenuService();
    $topList = $menuService->getTopList();
    $activeList = $menuService->getActiveMenu();
    $menuItems = [];
    foreach ($topList as $v) {
        $menuItems[] = [
            'label' => $v['name'], 'url' => [$v['url']], 'active' => isset($activeList[$v['id']]) ? true : false
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => $menuItems,
    ]);
    $menuItems = [];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $user = Yii::$app->user->identity;
        $html = ' <li class="dropdown"><a class="nav-link dropdown-toggle" href="javascript:void(0)" role="button" data-toggle="dropdown">';
        if ($user['avatar']) {
        }
        $html .= '<span>' . $user['realname'] . '</span>';
        $html .= '</a><ul class="dropdown-menu">
                   <li><a href="' . Yii::$app->urlManager->createUrl(['/system/user/profile']) . '">个人信息</a></li>
                   <li><a href="' . Yii::$app->urlManager->createUrl(['/site/logout']) . '">登出</a></li></ul></li>';
        $menuItems[] = $html;
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    $breadcrumbs = [];
    $menu = $menuService->getCurrentMenu();
    $arr = array_reverse($activeList);
    foreach ($arr as $key => $v) {
        $tmp = ['label' => $v['name']];
        if ($key != count($arr) - 1 && $v['url'] != '') {
            $tmp['url'] = [$v['url']];
        }
        $breadcrumbs[] = $tmp;
    }
    ?>
    <div class="view-content clearfix">
        <div class="col-12 col-md-2 col-sm-2 bd-sidebar" style="padding: 0;overflow-y: auto">
            <?= \backend\widgets\Menu::widget(); ?>
        </div>
        <div class="col-12 col-md-10 col-xl-10 bd-content">
            <?= Breadcrumbs::widget([
                'links' => $breadcrumbs,
            ]) ?>
            <h3><?= $this->title != '' ? $this->title : $menu['name'] ?></h3>
            <hr>
            <?= $content ?>
        </div>
    </div>

</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
