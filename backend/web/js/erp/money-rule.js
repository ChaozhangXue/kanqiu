$(function () {
    var dataList = [];
    $('#edit-btn').click(function () {
        dataList = [];
        $('#rule-table').find('.data-tr').each(function () {
            var data = {
                'start': $(this).find('.step').attr('data-start'),
                'end': $(this).find('.step').attr('data-end'),
                'money1': $(this).find('.money1').attr('data-value'),
                'money2': $(this).find('.money2').attr('data-value'),
                'money3': $(this).find('.money3').attr('data-value'),
            };
            $(this).find('td.step').html('<input type="number" class="start" value="' + data.start + '">-<input type="number" class="end" value="' + data.end + '">');
            $(this).find('td.money1').html('<input type="number" class="money1" value="' + data.money1 + '">');
            $(this).find('td.money2').html('<input type="number" class="money2" value="' + data.money2 + '">');
            $(this).find('td.money3').html('<input type="number" class="money3" value="' + data.money3 + '">');
            $(this).find('td.action-td').html('<div class="glyphicon glyphicon-plus"></div> <div class="glyphicon glyphicon-minus"></div> <div class="glyphicon glyphicon-arrow-up"></div> <div class="glyphicon glyphicon-arrow-down"></div>');
            dataList.push(data);
        });
        $(this).hide();
        $('#save-btn').show();
        $('#cancel-btn').show();
    });
    $('#cancel-btn').click(function () {
        renderHtml(dataList);
        $('#edit-btn').show();
        $('#save-btn').hide();
        $('#cancel-btn').hide();
    });
    $('#rule-table').on('click', '.glyphicon.glyphicon-plus', function () {
        var content = $('#empty-tr').clone();
        $(this).parents('.data-tr').after(content.html());
    }).on('click', '.glyphicon.glyphicon-minus', function () {
        var table = $('#rule-table');
        if (table.find('.data-tr').length == 1) {
            toastr.error('最少保留一个');
            return false;
        }
        $(this).parents('.data-tr').remove();
    }).on('click', '.glyphicon.glyphicon-arrow-up', function () {
        var tr = $(this).parents('.data-tr');
        var prev = tr.prev('tr.data-tr');
        prev.before(tr.clone());
        if (prev.get(0)) {
            tr.remove();
        }
    }).on('click', '.glyphicon.glyphicon-arrow-down', function () {
        var tr = $(this).parents('.data-tr');
        var next = tr.next('tr.data-tr');
        next.after(tr.clone());
        if (next.get(0)) {
            tr.remove();
        }
    });
    $('#save-btn').click(function () {
        var $this = $(this);
        var formData = new FormData();
        var i = 0;
        var flag = 1;
        var prev = null;
        var list = [];
        $('#rule-table').find('.data-tr').each(function () {
            var data = {
                'start': $(this).find('td.step').find('.start').val(),
                'end': $(this).find('td.step').find('.end').val(),
                'money1': $(this).find('input.money1').val(),
                'money2': $(this).find('input.money2').val(),
                'money3': $(this).find('input.money3').val(),
            };
            try {
                if (!data.start.length) {
                    throw new Error('开始公里不能为空');
                }
                if (!data.end.length) {
                    throw new Error('结束公里不能为空');
                }
                if (!data.money1.length || !data.money2.length || !data.money3.length) {
                    throw new Error('金额不能为空');
                }
                if (prev) {
                    if (prev.end == '0') {
                        throw new Error('只允许最后一个结束公里为0');
                    }
                    if (parseInt(data.start) != parseInt(prev.end) + 1) {
                        throw new Error('公里数不连贯')
                    }
                }
            } catch (e) {
                flag = 0;
                toastr.error(e.message);
                return false;
            }
            for (var j in data) {
                formData.append(j + '[' + i + ']', data[j]);
            }
            i++;
            prev = data;
            list.push(data);
        });
        if (flag == 1) {
            $.ajax({
                url: $this.attr('data-url'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res.code == 0) {
                        toastr.success(res.message);
                        renderHtml(list);
                        $('#edit-btn').show();
                        $('#save-btn').hide();
                        $('#cancel-btn').hide();
                    } else {
                        toastr.error(res.message);
                    }
                },
                complete: function () {
                    toastr.loading('hide');
                }
            });
        }
    });

    function renderHtml(list) {
        var html = '';
        for (var i in list) {
            var data = list[i];
            html += ' <tr class="data-tr">' +
                '<td class="step" data-start="' + data.start + '" data-end="' + data.end + '">' + data.start + (data.end == 0 ? '及以上' : '-' + data.end) + '</td>' +
                '<td class="money1" data-value="' + data.money1 + '">' + data.money1 + '</td>' +
                '<td class="money2" data-value="' + data.money2 + '">' + data.money2 + '</td>' +
                '<td class="money3" data-value="' + data.money3 + '">' + data.money3 + '</td>' +
                '<td class="action-td"></td>' +
                '</tr>';
        }
        var table = $('#rule-table');
        table.find('.data-tr').remove();
        table.find('.title-tr').after(html);
    }
});
