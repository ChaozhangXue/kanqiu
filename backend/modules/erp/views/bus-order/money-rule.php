<style>
    #rule-table input {
        width: 80px;
    }

    #rule-table .action-td div {
        cursor: pointer;
    }
</style>
<div class="col-12 col-md-8 col-sm-8">
    <div style="margin-bottom: 5px">
        <div class="btn btn-primary btn-sm" id="edit-btn"><i class="glyphicon glyphicon-pencil"></i> 编辑</div>
        <div class="btn btn-success btn-sm" id="save-btn"
             data-url="<?= Yii::$app->urlManager->createUrl(['erp/bus-order/money-rule']) ?>" style="display: none">
            <i class="glyphicon glyphicon-floppy-saved"></i> 保存
        </div>
        <div class="btn btn-default btn-sm" id="cancel-btn" style="display: none">
            <i class="glyphicon glyphicon-repeat"></i> 取消
        </div>
    </div>
    <table class="table table-bordered" id="rule-table">
        <tr class="title-tr">
            <td>公里数（km）</td>
            <td>小客车</td>
            <td>中巴车</td>
            <td>大客车</td>
            <td>操作</td>
        </tr>
        <?php foreach ($list as $key => $v):
            $arr = explode('-', $key);
            ?>
            <tr class="data-tr">
                <td class="step" data-start="<?= $arr[0] ?>"
                    data-end="<?= $arr[1] ?>"><?= $arr[0] . ($arr[1] == 0 ? '及以上' : '-' . $arr[1]) ?></td>
                <td class="money1" data-value="<?= isset($v[1]) ? $v[1] : '' ?>"><?= isset($v[1]) ? $v[1] : '' ?></td>
                <td class="money2" data-value="<?= isset($v[2]) ? $v[2] : '' ?>"><?= isset($v[2]) ? $v[2] : '' ?></td>
                <td class="money3" data-value="<?= isset($v[3]) ? $v[3] : '' ?>"><?= isset($v[3]) ? $v[3] : '' ?></td>
                <td class="action-td"></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<table class="hidden">
    <tbody id="empty-tr">
    <tr class="data-tr">
        <td class="step"><input type="number" class="start">-<input type="number" class="end"></td>
        <td class="money1"><input type="number" class="money1"></td>
        <td class="money2"><input type="number" class="money2"></td>
        <td class="money3"><input type="number" class="money3"></td>
        <td>
            <div class="glyphicon glyphicon-plus"></div>
            <div class="glyphicon glyphicon-minus"></div>
            <div class="glyphicon glyphicon-arrow-up"></div>
            <div class="glyphicon glyphicon-arrow-down"></div>
        </td>
    </tr>
    </tbody>
</table>
<?php $this->registerJsFile('@web/js/erp/money-rule.js', ['depends' => ['backend\assets\AppAsset']]); ?>
