$(function () {
    $('#login-form').validate({
        rules: {
            username: {
                required: true
            },
            password: {
                required: true
            },
        },
        messages: {
            username: {
                required: '请输入用户名'
            },
            password: {
                required: '请输入密码'
            },
        },
        errorClass: "help-block",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass('has-success').addClass('has-error').removeClass("has-feedback").addClass("has-feedback");
            if ($(element).hasClass('select2')) {
                if ($(element).parents('.form-group').find('.form-control-feedback').get(0)) {
                    $(element).parents('.form-group').find('.form-control-feedback').removeClass("glyphicon-ok").addClass("glyphicon-remove")
                } else {
                    $(element).next('span').find('.select2-selection').after('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                }
            } else {
                if ($(element).attr('type') != 'radio' && $(element).attr('type') != 'checkbox') {
                    if ($(element).parents('.form-group').find('.form-control-feedback').get(0)) {
                        $(element).parents('.form-group').find('.form-control-feedback').removeClass("glyphicon-ok").addClass("glyphicon-remove")
                    } else {
                        $(element).after('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                    }
                }
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass('has-error').addClass('has-success').removeClass("has-feedback").addClass("has-feedback");
            if ($(element).hasClass('select2')) {
                if ($(element).parents('.form-group').find('.form-control-feedback').get(0)) {
                    $(element).parents('.form-group').find('.form-control-feedback').removeClass("glyphicon-remove").addClass("glyphicon-ok")
                } else {
                    $(element).next('span').find('.select2-selection').after('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
                }
            } else {
                if ($(element).attr('type') != 'radio' && $(element).attr('type') != 'checkbox') {
                    if ($(element).parents('.form-group').find('.form-control-feedback').get(0)) {
                        $(element).parents('.form-group').find('.form-control-feedback').removeClass("glyphicon-remove").addClass("glyphicon-ok")
                    } else {
                        $(element).after('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
                    }
                }
            }
        },
        errorPlacement: function (error, element) {
            if (element.attr('type') == 'radio' || element.attr('type') == 'checkbox') {
                error.insertAfter(element.parents('.form-radio-group'));
            } else if (element.hasClass('select2')) {
                error.insertAfter(element.next('span'));
            } else if (element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (e) {
            submitForm();
            return false;
        }
    });

    $('.captcha-box').on('click', 'img', function () {
        $(this).attr('src', $(this).data('src') + '?' + new Date().getTime());
    });
});

function submitForm() {
    var form = $('#login-form');
    var data = form.serialize();
    toastr.loading('show');
    $.post(form.attr('action'), data, function (res) {
        toastr.loading('hide');
        if (res.code == 0) {
            toastr.success(res.message);
            setTimeout(function () {
                location.href = $('input[name=home-url]').val();
            }, 2000)
        } else {
            toastr.error(res.message);
        }
    }, 'json');
}