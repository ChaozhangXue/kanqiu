<?php
if ($model->isNewRecord) {
    $this->title = '创建新用户';
}
?>
<form class="col-12 col-md-8 col-sm-8 form-horizontal" id="save-form" action="<?= Yii::$app->urlManager->createUrl(['/system/user/edit']) ?>" method="post">
    <input type="hidden" name="id" value="<?= isset($model['id']) ? $model['id'] : '' ?>">
    <div class="form-group">
        <label class="col-sm-2 control-label">用户名</label>
        <div class="col-sm-10">
            <input type="text" name="username" class="form-control" value="<?= $model['username'] ?>" placeholder="请输入用户名">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">真实姓名</label>
        <div class="col-sm-10">
            <input type="text" name="realname" class="form-control" value="<?= $model['realname'] ?>" placeholder="请输入真实姓名">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">部门</label>
        <div class="col-sm-10">
            <input type="text" name="dept" class="form-control" value="<?= $model['dept'] ?>" placeholder="请输入部门">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">岗位</label>
        <div class="col-sm-10">
            <input type="text" name="job_position" class="form-control" value="<?= $model['job_position'] ?>" placeholder="请输入岗位">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">邮箱</label>
        <div class="col-sm-10">
            <input type="text" name="email" class="form-control" value="<?= $model['email'] ?>" placeholder="请输入邮箱">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">手机号</label>
        <div class="col-sm-10">
            <input type="tel" name="mobile" class="form-control" value="<?= $model['mobile'] ?>" placeholder="请输入手机号">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">头像</label>
        <div class="col-sm-10">
            <?= \backend\widgets\ImageInput::widget(['value'=>$model['avatar']])?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">保存</button>
        </div>
    </div>
</form>

<?php $this->registerJsFile('@web/js/system/user.js', ['depends' => ['backend\assets\AppAsset']]); ?>