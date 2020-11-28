$(function () {
    $('.check-btn').click(function () {
        if (!confirm('确定审核该账单？')) {
            return false;
        }
        var $this = $(this);
        var args = {
            id: $this.data('id')
        };
        toastr.loading('show');
        $.post($this.data('url'), args, function (res) {
            toastr.loading('hide');
            if (res.code == 0) {
                toastr.success(res.message);
                $this.parents('tr').find('.status').html('已审核');
                $this.remove();
            }else{
                toastr.error(res.message);
            }

        }, 'json');
    });
});
