<form class="col-12 col-md-8 col-sm-8 form-horizontal" id="save-role-menu-form" action="<?= Yii::$app->urlManager->createUrl(['/system/role/set-role-menu']) ?>" method="post">
    <input type="hidden" name="id" value="<?= isset($model['id']) ? $model['id'] : '' ?>">
    <div class="form-group">
        <label class="col-sm-2 control-label">角色名称</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" readonly
                   value="<?= isset($model['name']) ? $model['name'] : '' ?>" placeholder="请输入角色名称">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">所选权限</label>
        <div class="col-sm-10">
            <ul id="menuTree" class="ztree"></ul>
            <input type="hidden" name="menu_ids">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input class="btn btn-primary" type="submit" value="保存"/>
        </div>
    </div>
</form>
<script>
    var menuList =<?=json_encode($menuList);?>;
</script>
<?php $this->registerJsFile('@web/js/system/role.js', ['depends' => ['backend\assets\AppAsset']]); ?>