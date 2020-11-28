<style>
    #rule-table input {
        width: 80px;
    }

    #rule-table .action-td div {
        cursor: pointer;
    }
</style>
<div class="col-12 col-md-8 col-sm-8">
    <table class="table table-bordered" id="rule-table">
        <tr class="title-tr">
            <td>重量范围(kg)</td>
            <td>体积范围(m³)</td>
            <td>距离范围(㎞)</td>
            <td>价格(每件)</td>
        </tr>
        <tr class="data-tr">
            <td class="step">0-10</td>
            <td class="money1">≤0.03</td>
            <td class="money2">0-15</td>
            <td class="money3">3</td>
        </tr>

        <tr class="data-tr">
            <td class="step">11-15</td>
            <td class="money1">≤0.045</td>
            <td class="money2">16-50</td>
            <td class="money3">5</td>
        </tr>
        <tr class="data-tr">
            <td class="step">16-20</td>
            <td class="money1">≤0.06</td>
            <td class="money2"> >51</td>
            <td class="money3">6</td>
        </tr>

    </table>
</div>
<?php //$this->registerJsFile('@web/js/erp/order-money-rule.js', ['depends' => ['backend\assets\AppAsset']]); ?>
