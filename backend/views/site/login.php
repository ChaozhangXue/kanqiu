<?php $this->registerCssFile('@web/css/login.css', ['depends' => ['backend\assets\AppAsset']]); ?>
<div class="container">
    <div class="login-head"><h2><?= Yii::$app->name ?></h2></div>
    <div class="login-box">
        <div class="login-title">用户登录</div>
        <form id="login-form" action="<?= Yii::$app->urlManager->createUrl(['/site/login']) ?>" method="post">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" id='csrf'
                   value="<?= Yii::$app->request->getCsrfToken() ?>">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input type="text" class="form-control" name="username" placeholder="用户名">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="password" class="form-control" name="password" placeholder="密码">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="width: 100%">登录</button>
            </div>
        </form>
    </div>
</div>
<input type="hidden" name="home-url" value="<?= Yii::$app->getHomeUrl() ?>">
<?php $this->registerJsFile('@web/js/login.js', ['depends' => ['backend\assets\AppAsset']]); ?>
