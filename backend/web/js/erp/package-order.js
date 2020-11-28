$(function () {
    $('#generate-btn').click(function () {
        var $this = $(this);
        $.ajax({
            url: $this.attr('data-url'),
            type: 'POST',
            // data: {},
            dataType: 'json',
            // contentType: false,
            // processData: false,
            success: function (res) {
                if (res.code === 0) {
                    toastr.success("自动生成订单成功");
                    window.location.reload()
                }
            }
        });
    })

    $('.bind-btn').click(function () {
        var $this = $(this);
        var html =
            '<div class="form-group">' +
            '<label for="driver_id" class="col-sm-3 control-label">包裹码（请用机枪）</label>' +
            '<div class="col-sm-9"> <input  id="package-list-id" autofocus="autofocus"/>'

        html += '</div></div>';
        toastr.showModal({
            'title': '绑定包裹码',
            'content': html,
            'okCallback': function () {
                var args = {
                    package_id: $this.data('id'),
                    package_list_id: $('#package-list-id').val(),
                };
                toastr.loading('show');
                $.ajax({
                    url: $this.attr('data-url'),
                    type: 'POST',
                    data: args,
                    dataType: 'json',
                    // contentType: false,
                    // processData: false,
                    success: function (res) {
                        toastr.loading('hide');
                        if (res.code === 0) {
                            toastr.success("绑定包裹码成功");
                            window.location.reload()
                        } else {
                            toastr.loading('hide');
                            toastr.error(res.message);
                        }
                    },
                    error: function () {
                        toastr.loading('hide');
                        toastr.error("失败");
                    }
                });
            }
        });
        $('#modal_event_show').find('.select2').select2();
    })

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
                input: 'packageorder-send_address'
            });

            //初始化poiPicker
            poiPickerReady(poiPicker1, 'start');
            var poiPicker2 = new PoiPicker({
                input: 'packageorder-receive_address'
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
                        var distance = Math.round(result['routes'][0]['distance'] / 1000);
                        var parent = $('#packageorder-send_address').parent();
                        var html = '起点至终点最短距离' + distance + '公里';
                        if (parent.find('.hint-block').get(0)) {
                            parent.find('.hint-block').html(html);
                        } else {
                            parent.append('<div class="hint-block">' + html + '</div>');
                        }
                        var start_point = result['start']['location']['lng'] + ',' + result['start']['location']['lat'],
                            end_point = result['end']['location']['lng'] + ',' + result['end']['location']['lat'];
                        $('#save-form input#sender_point').val(start_point);
                        $('#save-form input#receive_point').val(end_point);

                    });
                }
            });

            poiPicker.onCityReady(function () {
            });
        }
    }
})