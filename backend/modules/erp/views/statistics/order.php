<form class="search-form" action="<?= Yii::$app->urlManager->createUrl(['/erp/statistics/car']) ?>" method="get">
    <input type="hidden" name="r" value="/erp/statistics/order">
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
            <span>统计类型</span>
            <select class="form-control search-input" name="type">
                <option value="1" <?= $params['type'] == '1' ? 'selected' : '' ?>>金额</option>
                <option value="2" <?= $params['type'] == '2' ? 'selected' : '' ?>>佣金</option>
                <option value="3" <?= $params['type'] == '3' ? 'selected' : '' ?>>数量</option>
            </select>
        </div>
        <div class="search-group">
            <span>订单类型</span>
            <select class="form-control search-input" name="order_type">
                <option value="1" <?= $params['order_type'] == '1' ? 'selected' : '' ?>>客运订单</option>
                <option value="2" <?= $params['order_type'] == '2' ? 'selected' : '' ?>>包裹订单</option>
                <option value="3" <?= $params['order_type'] == '3' ? 'selected' : '' ?>>站点订单</option>
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
            <span>车牌号</span>
            <select class="form-control select2 search-input" name="bus_card">
                <option value="">请选择</option>
                <?php foreach ($cardList as $v): ?>
                    <option value="<?= $v ?>" <?= $params['bus_card'] == $v ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="search-group">
            <span>订单状态</span>
            <select class="form-control search-input" name="status">
                <option value="">请选择</option>
                <?php foreach ($statusAllList[$params['order_type']] as $key => $v): ?>
                    <option value="<?= $key ?>" <?= (string)$params['status'] === (string)$key ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
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
    var statusAllList = <?= json_encode($statusAllList)?>;
    var $keys = <?= json_encode(array_keys($list))?>;
    var $values = <?= json_encode(array_values($list))?>;
</script>
<?php $this->registerJsFile('@web/js/plugin/echarts.min.js', ['depends' => ['backend\assets\AppAsset']]); ?>
<?php $this->registerJsFile('@web/js/erp/statistics-order.js', ['depends' => ['backend\assets\AppAsset']]); ?>