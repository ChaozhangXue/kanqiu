$('.print').click(function () {
    var $this = $(this);
    $.ajax({
        url: $this.attr('data-url'),
        type: 'get',
        // data: {},
        dataType: 'json',
        // contentType: false,
        // processData: false,
        success: function (res) {
            if (res.code === 0) {
                window.open('index2.html?barcode=' + res.list,'新窗口','height=500,width=1024,top=0,left=0,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no');
            }
        }
    });

})

$('.reprint-btn').click(function () {
    var $this = $(this);
    $.ajax({
        url: $this.attr('data-url'),
        type: 'post',
        data: {
            'id': $this.attr('data-id')
        },
        dataType: 'json',
        // contentType: false,
        // processData: false,
        success: function (res) {
            if (res.code === 0) {
                window.open('index2.html?barcode=' + res.list,'新窗口','height=500,width=1024,top=0,left=0,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no');
            }
        }
    });

})