$(function () {
    $('.assign-btn').click(function () {
        var $this = $(this);
        var html = '<form class="form-horizontal">' +
            '<div class="form-group">' +
            '<label for="driver_id" class="col-sm-3 control-label">司机</label>' +
            '<div class="col-sm-9">' +
            '<select class="form-control select2" style="width: 200px" id="driver_id" name="driver_id">';
        html += '<option value="">请选择</option>';
        for (var i in driverList) {
            html += '<option value="' + driverList[i]['customer_id'] + '" ' + ($this.data('driver') == driverList[i]['customer_id'] ? 'selected' : '') + '>' + driverList[i]['realname'] + '</option>'
        }
        html += '</select></div></div>';
        html += '<div class="form-group">' +
            '<label for="bus_id" class="col-sm-3 control-label">车辆</label>' +
            '<div class="col-sm-9">' +
            '<select class="form-control select2" style="width: 200px" id="bus_id" name="bus_id">';
        html += '<option value="">请选择</option>';
        for (var i in busList) {
            if ($this.data('type') != 2 && busList[i]['car_type'] != $this.data('car_type')) {
                continue;
            }
            html += '<option value="' + busList[i]['id'] + '"' + ($this.data('bus') == busList[i]['id'] ? 'selected' : '') + '>' + busList[i]['card'] + '</option>'
        }
        html += '</select></div></div>' +
            '</form>';
        toastr.showModal({
            'title': $this.attr('data-title'),
            'content': html,
            'okCallback': function () {
                var args = {
                    id: $this.data('id'),
                    driver_id: $('#modal_event_show select[name=driver_id]').val(),
                    bus_id: $('#modal_event_show select[name=bus_id]').val(),
                    status: $this.data('status'),
                };
                if (!args.driver_id.length) {
                    toastr.error('请选择司机');
                    return false;
                }
                if (!args.bus_id.length) {
                    toastr.error('请选择车辆');
                    return false;
                }
                toastr.loading('show');
                $.post($this.data('url'), args, function (res) {
                    toastr.loading('hide');
                    if (res.code == 0) {
                        $this.remove();
                        toastr.success(res.message);
                    } else {
                        toastr.error(res.message);
                    }
                }, 'json')
            }
        });
        $('#modal_event_show').find('.select2').select2();
    });
    $('.refund-btn').click(function () {
        var $this = $(this);
        if (!confirm($this.data('msg'))) {
            return false;
        }
        var args = {
            id: $this.data('id'),
        };
        toastr.loading('show');
        $.post($this.data('url'), args, function (res) {
            toastr.loading('hide');
            if (res.code == 0) {
                $this.remove();
                toastr.success(res.message);
            } else {
                toastr.error(res.message);
            }
        }, 'json')
    });

    $('input[name="BusOrder[order_type]"]').change(function () {
        var form = $(this).parents('form');
        showInput(form);
    });

    $('#save-form').validate({
        rules: {
            'BusOrder[order_type]': {
                required: true
            },
            'BusOrder[customer_id]': {
                required: true
            }
        },
        messages: {
            'BusOrder[order_type]': {
                required: '请选择业务类型'
            },
            'BusOrder[customer_id]': {
                required: '请输入会员ID'
            }
        },
        errorClass: "help-block",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass('has-success');
            $(element).parents('.form-group').addClass('has-error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass('has-error');
            $(element).parents('.form-group').addClass('has-success');
        },
        submitHandler: function (form) {
            $('#save-form').data('yiiActiveForm').validated = true;
            form.submit();
            return false;
        }
    });

    $('.pay-btn').click(function () {
        var $this = $(this);
        var html = '<form class="form-horizontal">' +
            '<div class="form-group">' +
            '<label for="pay_method" class="col-sm-4 control-label">支付方式</label>' +
            '<div class="col-sm-8">' +
            '<select class="form-control" id="pay_method" name="pay_method">';
        html += '<option value="">请选择</option>';
        html += '<option value="1">微信</option>';
        html += '<option value="2">支付宝</option>';
        html += '</select></div></div>';
        html += '<div class="form-group">' +
            '<label for="pay_money" class="col-sm-4 control-label">支付金额</label>' +
            '<div class="col-sm-8">' +
            '<input class="form-control" id="pay_money" name="pay_money">' +
            '</div></div>' +
            '<div class="form-group">' +
            '<label for="transaction_id" class="col-sm-4 control-label">交易流水号</label>' +
            '<div class="col-sm-8">' +
            '<input class="form-control" id="transaction_id" name="transaction_id">' +
            '</div></div>' +
            '</form>';
        toastr.showModal({
            'title': '修改支付状态',
            'content': html,
            'okCallback': function () {
                var args = {
                    id: $this.data('id'),
                    pay_method: $('#modal_event_show select[name=pay_method]').val(),
                    pay_money: $('#modal_event_show input[name=pay_money]').val(),
                    transaction_id: $('#modal_event_show input[name=transaction_id]').val(),
                };
                if (!args.pay_method.length) {
                    toastr.error('请选择支付方式');
                    return false;
                }
                if (!args.pay_money.length) {
                    toastr.error('请输入支付金额');
                    return false;
                }
                toastr.loading('show');
                $.post($this.data('url'), args, function (res) {
                    toastr.loading('hide');
                    if (res.code == 0) {
                        $this.remove();
                        toastr.success(res.message);
                    } else {
                        toastr.error(res.message);
                    }
                }, 'json')
            }
        });
    });

    $('.cancel-btn').click(function () {
        var $this = $(this);
        var msg = '当前订单付款金额' + $this.data('money') + '，确认后需进行退款操作？';
        if ($this.data('money') == 0) {
            msg = '当前订单无付款金额，确定取消该订单？';
        }
        if (!confirm(msg)) {
            return false;
        }
        cancelOrder($this.data('url'), {order_no: $this.data('id')});
    });

    function cancelOrder(url, args) {
        toastr.loading('show');
        $.post(url, args, function (res) {
            toastr.loading('hide');
            if (res.code == 0) {
                toastr.success(res.message, function () {
                    location.reload();
                });
            } else {
                toastr.error(res.message);
            }
        }, 'json')
    }

    if ($('#save-form').get(0)) {
        var form = $('#save-form');
        showInput(form);
    }

    function showInput(form) {
        form.find('.form-type').hide();
        form.find('.form-type').each(function () {
            $(this).find('input,select,textarea').rules('remove', 'required');
        });
        var formGroups = form.find('.type' + $('input[name="BusOrder[order_type]"]:checked').val());
        formGroups.show();
        formGroups.each(function () {
            var label = $(this).find('.control-label');
            $(this).find('input,select,textarea').rules('add', {
                required: true,
                messages: {required: label.html() + '是必填项'}
            })
        })
    }

    $('.qr-btn').click(function () {
        var $this = $(this);
        toastr.showModal({
            'title': $this.attr('data-title'),
            'content': '<div id="qrcode-modal-box" style="margin: auto;width: 200px"></div>',
        });
        var data = {
            'order_type': 'bus-order',
            'order_no': $this.data('no'),
            'package_num': 0,
            'type': $this.data('type'),
        };
        new QRCode(document.getElementById("qrcode-modal-box"), {
            width: '200',
            height: '200'
        }).makeCode(JSON.stringify(data));
    });

    $('.bus-order-no').click(function () {
        var $this = $(this);
        var args = {order_no: $this.text()};
        if (!$('#list-table').find('.list-data-info[data-key="' + args.order_no + '"]').get(0)) {
            $.post($this.data('url'), args, function (res) {
                $('#list-table').find('.list-data-info').remove();
                $this.parents('tr').after(res.data);
            }, 'json');
        } else {
            $('#list-table').find('.list-data-info[data-key="' + args.order_no + '"]').toggle();
        }
    });
    $('body').on('click', '.sugg-item', function () {
        $(this).parents('.form-group').find('input[type=text]').val($(this).find('.sugg-name').html());
    });
    var point = {
        start: null,
        end: null
    };
    if (typeof AMapUI != 'undefined') {
        AMapUI.loadUI(['misc/PoiPicker'], function (PoiPicker) {

            var poiPicker1 = new PoiPicker({
                input: 'busorder-dispatch_start'
            });

            //初始化poiPicker
            poiPickerReady(poiPicker1, 'start');
            var poiPicker2 = new PoiPicker({
                input: 'busorder-dispatch_end'
            });

            //初始化poiPicker
            poiPickerReady(poiPicker2, 'end');
        });

        function poiPickerReady(poiPicker, type) {
            window.poiPicker = poiPicker;
            //选取了某个POI
            poiPicker.on('poiPicked', function (poiResult) {
                var poi = poiResult.item;
                point[type] = poi.location.toString();
                if (point['start'] != null && point['end'] != null) {
                    var start = point['start'].split(',');
                    var end = point['end'].split(',');
                    //构造路线导航类
                    var driving = new AMap.Driving({
                        // 驾车路线规划策略，AMap.DrivingPolicy.LEAST_DISTANCE最短路
                        policy: AMap.DrivingPolicy.LEAST_DISTANCE
                    });
                    // 根据起终点经纬度规划驾车导航路线
                    driving.search(new AMap.LngLat(parseFloat(start[0]), parseFloat(start[1])), new AMap.LngLat(parseFloat(end[0]), parseFloat(end[1])), function (status, result) {
                        // result 即是对应的驾车导航信息，相关数据结构文档请参考  https://lbs.amap.com/api/javascript-api/reference/route-search#m_DrivingResult
                        if (status === 'complete') {
                            console.log(result);
                            var distance = Math.round(result['routes'][0]['distance'] / 1000);
                            var parent = $('#busorder-dispatch_end').parent();
                            var html = '起点至终点最短距离' + distance + '公里';
                            if (parent.find('.hint-block').get(0)) {
                                parent.find('.hint-block').html(html);
                            } else {
                                parent.append('<div class="hint-block">' + html + '</div>');
                            }
                            var start_point = result['start']['location']['lng'] + ',' + result['start']['location']['lat'],
                                end_point = result['end']['location']['lng'] + ',' + result['end']['location']['lat'];
                            $('#save-form input#start_point').val(start_point);
                            $('#save-form input#end_point').val(end_point);
                        } else {
                            toastr.error('获取驾车数据失败：' + result)
                        }
                    });
                }
            });

            poiPicker.onCityReady(function () {
            });
        }
    }
});
