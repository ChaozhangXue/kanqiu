$(function () {
    var dataList = [];

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
    });

    // $('#save-btn').click(function () {
    //     var $this = $(this);
    //     var formData = new FormData();
    //     var list = [];
    //     $('#rule-table').find('.data-tr').each(function () {
    //         var data = {
    //             'station': $(this).find('select.station').val(),
    //         };
    //
    //         for (var j in data) {
    //             formData.append(j + '[' + i + ']', data[j]);
    //         }
    //         i++;
    //         prev = data;
    //         list.push(data);
    //     });
    //     $.ajax({
    //         url: $this.attr('data-url'),
    //         type: 'POST',
    //         data: formData,
    //         dataType: 'json',
    //         contentType: false,
    //         processData: false,
    //         success: function (res) {
    //
    //         },
    //         complete: function () {
    //             toastr.loading('hide');
    //         }
    //     });
    //
    // });
});
