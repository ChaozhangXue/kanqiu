$(function () {
    $('.feedback-btn').click(function () {
        var $this = $(this);
        var html = '<textarea name="feedback_msg" class="form-control"></textarea>';
        toastr.showModal({
            'title': '回复',
            'content': html,
            'okCallback': function () {
                var data = {
                    id: $this.data('id'),
                    feedback_msg: $('#modal_event_show textarea[name=feedback_msg]').val()
                };
                toastr.loading('show');
                $.post($this.data('url'), data, function (res) {
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
        });
    });
});
