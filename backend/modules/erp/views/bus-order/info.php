<tr class="list-data-info" data-key="<?= $order['order_no'] ?>">
    <td colspan="100" style="background-color:#FFF;border:1px #666 solid;">
        <div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#order-log" aria-controls="order-log" role="tab" data-toggle="tab">单据日志</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="order-log">
                    <table class="table table-bordered table-list text-center" style="width:60% ">
                        <tbody>
                        <tr>
                            <td>操作人</td>
                            <td>操作内容</td>
                            <td>操作时间</td>
                        </tr>
                        <?php foreach ($traceList as $v): ?>
                            <tr>
                                <td><?= $v['operator'] ?></td>
                                <td><?= $v['detail'] ?></td>
                                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </td>
</tr>