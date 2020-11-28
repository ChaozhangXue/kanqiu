<form class="search-form" action="<?= Yii::$app->urlManager->createUrl(['/erp/statistics/station']) ?>" method="get">
    <input type="hidden" name="r" value="/erp/statistics/station">
    <?php if ($errorMsg != ''): ?>
        <div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            <?= $errorMsg ?>
        </div>
    <?php endif; ?>
    <div>
        <label>统计条件：</label>
        <div class="search-group">
            <span>站点类型</span>
            <select class="form-control search-input" name="station_type">
                <option value="1" <?= $params['station_type'] == '1' ? 'selected' : '' ?>>服务站点</option>
                <option value="2" <?= $params['station_type'] == '2' ? 'selected' : '' ?>>公交站点</option>
            </select>
        </div>
        <div class="search-group">
            <span>统计方式</span>
            <select class="form-control search-input" name="show_type">
                <option value="1" <?= $params['show_type'] == '1' ? 'selected' : '' ?>>天</option>
                <option value="2" <?= $params['show_type'] == '2' ? 'selected' : '' ?>>月</option>
                <option value="3" <?= $params['show_type'] == '3' ? 'selected' : '' ?>>季</option>
            </select>
        </div>
    </div>
    <div>
        <label>过滤条件：</label>
        <div class="search-group">
            <span>乡镇</span>
            <input style="width: 100px" class="form-control search-input" name="country" value="<?= $params['country'] ?>">
        </div>
        <div class="search-group">
            <span>开始时间</span>
            <input type="date" name="start_time" value="<?= $params['start_time'] ?>" class="form-control search-input"
                   style="width: 160px">
        </div>
        <div class="search-group">
            <span>结束时间</span>
            <input type="date" name="end_time" value="<?= $params['end_time'] ?>" class="form-control search-input"
                   style="width: 160px">
        </div>
        <div class="btn btn-primary search-btn text-nowrap"><i class="glyphicon glyphicon-search"></i> 搜索</div>
    </div>

</form>
<div id="chart_main" style="width: 100%;min-height: 500px;margin-top:20px;"></div>
<script>
    var $chartTitle = '<?= $chartTitle?>';
    var $keys = <?= json_encode(array_column($list, 'name'))?>;
    var $values = <?= json_encode(array_column($list, 'value'))?>;
</script>
<?php $this->registerJsFile('@web/js/plugin/echarts.min.js', ['depends' => ['backend\assets\AppAsset']]); ?>
<?php $this->registerJsFile('@web/js/erp/statistics-station.js', ['depends' => ['backend\assets\AppAsset']]); ?>