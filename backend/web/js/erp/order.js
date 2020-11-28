$(function () {
    $('.assign-btn').click(function () {
        var $this = $(this);
        var html = '<form class="form-horizontal">' +
            '<div class="form-group">' +
            '<label for="driver_id" class="col-sm-4 control-label">司机</label>' +
            '<div class="col-sm-5">' +
            '<select class="form-control select2" style="width: 200px" id="driver_id" name="driver_id">';
        html += '<option value="">请选择</option>';
        for (var i in driverList) {
            html += '<option value="' + driverList[i]['customer_id'] + '" ' + ($this.data('driver') === driverList[i]['customer_id'] ? 'selected' : '') + '>' + driverList[i]['realname'] + '</option>'
        }
        html += '</select></div></div>';


        html += '<div class="form-group">' +
            '<label for="bus_id" class="col-sm-4 control-label">车辆</label>' +
            '<div class="col-sm-5">' +
            '<select class="form-control select2" style="width:200px" id="bus_id" name="bus_id">';
        html += '<option value="">请选择</option>';
        for (var i in busList) {
            html += '<option value="' + busList[i]['id'] + '"' + ($this.data('bus') === busList[i]['id'] ? 'selected' : '') + '>' + busList[i]['card'] + '</option>'
        }
        html += '</select></div></div>';
        html += '<div class="form-group">' +
            '<label for="line" class="col-sm-4 control-label">线路</label>' +
            '<div class="col-sm-5">' +
            '<select class="form-control select2" style="width:200px" id="line" name="line">';
        html += '<option value="">请选择</option>';
        for (var i in busLine) {
            html += '<option value="' + busLine[i]['id'] + '"' + ($this.data('line') === busLine[i]['id'] ? 'selected' : '') + '>' + busLine[i]['station_name'] + '</option>'
        }
        html += '</select></div></div>';
        html += '<div class="form-group">' +
            '<label for="yongjin" class="col-sm-4 control-label">司机佣金</label>' +
            '<div class="col-sm-5">' + '<input name="yongjin" id="yongjin">' +
            '</div></div>';

        html += '<div class="form-group">' +
            '<label for="yongjin" class="col-sm-4 control-label">站点佣金</label>' +
            '<div class="col-sm-5">' + '<input name="station_yongjin" id="station_yongjin">' +
            '</div></div>';

        html += '<div class="form-group">' +
            '<label for="time" class="col-sm-4 control-label">时间</label>' +
            '<div class="col-sm-5">' + '<select name="hour" id="hour" style="line-height: inherit;display:inline;width:40%;height:120%">'
            + '<option value="0">0</option>';

            for (var num =1; num<=23; num++) {
                html +=  '<option value="' + num + '">' + num + '</option>';
            }
        html +=  '</select>' + '  <p style="display:inline;">:</p>  ' + '<select name="minutes" id="minutes" style="line-height: inherit;display:inline;width:45%;height:120%">' +'<option value="0">00</option>';
        for (var num =1; num<=60; num++) {
            if(num < 10){
                html +=  '<option value="' + num + '">' + '0' + num + '</option>';
            }else{
                html +=  '<option value="' + num + '">' + num + '</option>';
            }
        }
        html +=  '</select>';
        html +=  '</div></div>' +
            '</form>';
        toastr.showModal({
            'title': '指派',
            'content': html,
            'okCallback': function () {
                var args = {
                    id: $this.data('id'),
                    driver_id: $('#modal_event_show select[name=driver_id]').val(),
                    bus_id: $('#modal_event_show select[name=bus_id]').val(),
                    line: $('#modal_event_show select[name=line]').val(),
                    yongjin: $('#modal_event_show input[name=yongjin]').val(),
                    station_yongjin: $('#modal_event_show input[name=station_yongjin]').val(),
                    hour: $('#modal_event_show select[name=hour]').val(),
                    minutes: $('#modal_event_show select[name=minutes]').val(),
                    status: $this.data('status')
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
                        toastr.success(res.message);
                        window.location.reload()
                    } else {
                        toastr.error(res.message);
                    }
                }, 'json')
            }
        });
        $('#modal_event_show').find('.select2').select2();
    });

    $('.qr-btn').click(function () {
        var $this = $(this);
        toastr.showModal({
            'title': $this.attr('data-title'),
            'content': '<div id="qrcode-modal-box" style="margin: auto;width: 200px"></div>',
            'okCallback': function () {
                window.location.reload()
            }
        });
        var data = {
            'order_no': $this.data('no'),
            'package_num': $this.data('num'),
            'order_type': $this.data('order_type'),
            'type': 'pending'
        };
        new QRCode(document.getElementById("qrcode-modal-box"), {
            width: '200',
            height: '200'
        }).makeCode(JSON.stringify(data));
    });
})