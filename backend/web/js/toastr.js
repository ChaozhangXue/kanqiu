var toastr = {
    count: 1,
    option: {
        'successClass': 'alert-success',
        'errorClass': 'alert-danger',
    },
    success: function (msg, callback, timeout) {
        if (timeout == null) {
            timeout = 2000;
        }
        var alertC = 'alert' + toastr.count;
        toastr.count++;
        var html = '<div class="alert ' + toastr.option.successClass + ' ' + alertC + '" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button>' +
            '<span>' + msg + '</span>' +
            '</div>';
        var popupContent = $('body').find('.popup-content');
        if (!$('body').find('.popup-content').get(0)) {
            $('body').append('<div class="popup-content"></div>');
            popupContent = $('body').find('.popup-content');
        }
        popupContent.append(html);
        setTimeout(function () {
            $('.' + alertC).alert('close');
            if (typeof callback == 'function') {
                callback();
            }
        }, timeout);

    },
    error: function (msg, callback, timeout) {
        if (timeout == null) {
            timeout = 2000;
        }
        var alertC = 'alert' + toastr.count;
        toastr.count++;
        var html = '<div class="alert ' + toastr.option.errorClass + ' ' + alertC + '" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">×</span>' +
            '</button>' +
            '<span style="margin-right: 10px">' + msg + '</span>' +
            '</div>';
        var popupContent = $('body').find('.popup-content');
        if (!$('body').find('.popup-content').get(0)) {
            $('body').append('<div class="popup-content"></div>');
            popupContent = $('body').find('.popup-content');
        }
        popupContent.append(html);
        setTimeout(function () {
            $('.' + alertC).alert('close');
            if (typeof callback == 'function') {
                callback();
            }
        }, timeout);
    },
    loading: function (type, msg) {
        if (type == null) {
            type = 'show';
        }
        if (msg == null) {
            msg = '数据加载中，请稍后...';
        }
        if (type == 'show') {
            if (!$('#loadingModel').get(0)) {
                var html = '<div id="loadingModel" class="modal">' +
                    '<div class="modal-body">' +
                    '<span><img src="./image/load.gif"/></span>' +
                    '<span style="color:red;font-size:15px;">' + msg + '</span>' +
                    '</div></div>';
                $('body').append(html);
                var width = document.documentElement.clientWidth || document.body.clientWidth,
                    height = document.documentElement.clientHeight || document.body.clientHeight,
                    loadingModal = $('#loadingModel');
                var top = (height - loadingModal.height()) / 2;
                var left = (width - loadingModal.width()) / 2;
                loadingModal.css({
                    top: top,
                    left: left
                });
                loadingModal.find('.modal-body').css('padding', 0);
                loadingModal.modal({backdrop: 'static'});
                loadingModal.modal('show');
            }
        } else {
            if ($('#loadingModel').get(0)) {
                $('#loadingModel').modal('hide');
                $('#loadingModel').remove();
            }
        }
    },
    showModal: function (options) {
        var opts = {
            'title': '添加模块',
            'width': '340px',
            'content': '',
            'okTitle': '确定',
            'okCallback': function () {

            }
        };
        for (var i in opts) {
            if (options.hasOwnProperty(i)) {
                opts[i] = options[i];
            }
        }
        var html = '<div id="modal_event_show" class="modal fade">\
                        <div class="modal-dialog" style="width:' + opts['width'] + ';">\
                        <div class="modal-content">\
                        <div class="modal-header">\
                          <button class="close" type="button" id="modal_event_show_close">×</button>\
                          <h4 class="modal-title" >' + opts['title'] + '</h4>\
                        </div>\
                        <div class="modal-body">' + opts['content'] + '</div>\
                        <div class="modal-footer"> \
                            <a class="btn btn-default" href="#" id="modal_event_show_cannel">取消</a>\
                            <a class="btn btn-primary" id="modal_event_show_submit" href="#">' + opts['okTitle'] + '</a>\
                        </div></div></div>\
                   </div>';
        $(document.body).append(html);
        var modal_event_view = $('#modal_event_show');
        modal_event_view.modal('show');
        if (options['afterShowCallback'] && typeof options['afterShowCallback'] === 'function') {
            options['afterShowCallback']();
        }
        $('#modal_event_show_cannel,#modal_event_show_close').click(function (e) {
            e.preventDefault();
            $(document.body).css({"overflow-y": "auto"});
            modal_event_view.modal('hide');
            if (options['cannelCallback'] && typeof options['cannelCallback'] === 'function') {
                options['cannelCallback']();
            }
        });
        $('#modal_event_show_submit').click(function (e) {
            e.preventDefault();
            if (options['okCallback'] && typeof options['okCallback'] === 'function') {
                var bol = options['okCallback']();
                if (bol !== false) {
                    modal_event_view.modal('hide');
                }
            }
        });
        modal_event_view.on('hidden.bs.modal', function () {
            modal_event_view.remove()
        })
    }
};
