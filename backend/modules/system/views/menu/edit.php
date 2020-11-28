<?php
if ($model->isNewRecord) {
    $this->title = '创建新菜单';
}
?>
    <form class="col-12 col-md-8 col-sm-8 form-horizontal" id="save-form"
          action="<?= Yii::$app->urlManager->createUrl(['/system/menu/edit']) ?>" method="post">
        <input type="hidden" name="id" value="<?= $model['id'] ?>">
        <div class="form-group">
            <label class="col-sm-2 control-label">父级功能</label>
            <div class="col-sm-10">
                <select class="form-control select2" name="parent_id">
                    <?php foreach ($childList as $v): ?>
                        <option value="<?= $v['id'] ?>" <?= $model['parent_id'] == $v['id'] ? 'selected' : '' ?>><?= $v['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">链接地址</label>
            <div class="col-sm-10">
                <select name="url" class="form-control">
                    <option value="">请选择</option>
                    <?php foreach ($methodList as $v): ?>
                        <option value="<?= $v ?>" <?= $model['url'] == $v ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">标题</label>
            <div class="col-sm-10">
                <input type="text" name="name" class="form-control"
                       value="<?= $model['name'] ?>" placeholder="请输入标题">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">排序</label>
            <div class="col-sm-10">
                <input type="text" name="sort" class="form-control" value="<?= $model['sort'] ? $model['sort'] : 0 ?>"
                       placeholder="请输入排序数字">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">图标样式</label>
            <div class="col-sm-10">
                <input type="text" name="icon" class="form-control"
                       value="<?= $model['icon'] ? $model['icon'] : 'glyphicon glyphicon-bookmark' ?>"
                       placeholder="glyphicon glyphicon-bookmark">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">菜单描述</label>
            <div class="col-sm-10">
           <textarea name="desc"
                     class="form-control"><?= $model['desc'] ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">保存</button>
            </div>
        </div>
    </form>

<?php $this->registerJsFile('@web/js/system/menu.js', ['depends' => ['backend\assets\AppAsset']]); ?>