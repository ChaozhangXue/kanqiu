$(function () {
    $('input[name="SystemMsg[type]"]').change(function () {
        if ($(this).val() == '1') {
            $('.field-systemmsg-receive_id').show();
        } else {
            $('.field-systemmsg-receive_id').hide();
        }
    });
    $('.repeat-btn').click(function () {
        if (!confirm('是否重新加入推送队列？')) {
            return false;
        }
        var $this = $(this);
        toastr.loading('show');
        $.post($this.data('url'), {id: $this.data('id')}, function (res) {
            toastr.loading('hide');
            if (res.code == 0) {
                $this.remove();
                toastr.success(res.message);
            } else {
                toastr.error(res.message);
            }
        }, 'json');
    });
});
