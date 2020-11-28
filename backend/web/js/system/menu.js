$(function () {
    $('#save-form').validate({
        rules: {
            parent_id: {
                required: true
            },
            name: {
                required: true
            }
        },
        messages: {
            parent_id: {
                required: '请选择父级功能'
            },
            name: {
                required: '请输入标题'
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
        submitHandler: function (e) {
            saveForm();
            return false;
        }
    });

    $('.set-status-btn').click(function () {
        if (!confirm('确定修改菜单状态？')) {
            return false;
        }
        let args = {
            id: $(this).data('id'),
            status: $(this).data('status')
        };
        toastr.loading('show');
        $.post($(this).data('url'), args, function (res) {
            toastr.loading('hide');
            if (res.code == 0) {
                toastr.success(res.message, function () {
                    location.reload();
                });
            } else {
                toastr.error(res.message);
            }
        }, 'json');
    });

});

function saveForm() {
    var form = $('#save-form');
    var data = form.serialize(),
        type = form.find('[name=type]:checked').val();

    if ($.inArray(type, ['2', '3']) != -1 && !form.find('select[name=url]').val().length) {
        toastr.error('左部功能/列表功能 必须选择 链接地址');
        return false;
    }
    toastr.loading('show');
    $.post(form.attr('action'), data, function (res) {
        toastr.loading('hide');
        if (res.code == 0) {
            toastr.success(res.message);
        } else {
            toastr.error(res.message);
        }
    }, 'json');
}