<form class="col-12 col-md-8 col-sm-8 form-horizontal" id="save-role-admin-form" action="<?= Yii::$app->urlManager->createUrl(['/system/role/set-role-user']) ?>" method="post">
    <input type="hidden" name="id" value="<?= isset($model['id']) ? $model['id'] : '' ?>">
    <div class="form-group">
        <label class="col-sm-2 col-form-label">角色名称</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" readonly
                   value="<?= isset($model['name']) ? $model['name'] : '' ?>" placeholder="请输入角色名称">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 col-form-label">所选用户</label>
        <div class="col-sm-10">
            <select name="user_id[]" class="form-control select2" multiple>
                <?php foreach ($userList as $v): ?>
                    <option value="<?= $v['id'] ?>" <?= in_array($v['id'], $userIdList) ? 'selected' : '' ?>><?= $v['realname'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input class="btn btn-primary" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->registerJsFile('@web/js/system/role.js', ['depends' => ['backend\assets\AppAsset']]); ?>