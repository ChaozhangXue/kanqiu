<?php
if ($model->isNewRecord) {
    $this->title = '创建新角色';
}
?>
<form class="col-12 col-md-8 col-sm-8 form-horizontal" id="save-form" action="<?= Yii::$app->urlManager->createUrl(['/system/role/edit']) ?>" method="post">
    <input type="hidden" name="id" value="<?= isset($model['id']) ? $model['id'] : '' ?>">
    <div class="form-group">
        <label class="col-sm-2 control-label">角色名称</label>
        <div class="col-sm-10">
            <input type="text" name="name" class="form-control" value="<?= isset($model['name']) ? $model['name'] : '' ?>" placeholder="请输入角色名称">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">描述</label>
        <div class="col-sm-10">
           <textarea name="desc" class="form-control"><?= isset($model['desc']) ? $model['desc'] : '' ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">保存</button>
        </div>
    </div>
</form>

<?php $this->registerJsFile('@web/js/system/role.js', ['depends' => ['backend\assets\AppAsset']]); ?>