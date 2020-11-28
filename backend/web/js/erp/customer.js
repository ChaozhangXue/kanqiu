$(function () {
    $('.set-status-btn').click(function () {
        if (!confirm('确定修改状态？')) {
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
    $('.reset-password-btn').click(function () {
        if (!confirm('是否重置密码为123456?')) {
            return false;
        }
        toastr.loading('show');
        $.post($(this).data('url'), {id: $(this).data('id')}, function (res) {
            toastr.loading('hide');
            if (res.code == 0) {
                toastr.success(res.message);
            } else {
                toastr.error(res.message);
            }
        }, 'json');
    });
});
