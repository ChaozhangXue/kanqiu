$(function () {
    $('#save-form').validate({
        rules: {
            name: {
                required: true
            }
        },
        messages: {
            name: {
                required: '请输入角色名称'
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
    var setting = {
        check: {
            enable: true,
            chkboxType: {'Y': 'p' + 's', 'N': 'p' + 's'}
        },
        view: {
            dblClickExpand: false
        },
        data: {
            simpleData: {
                enable: true
            }
        }
    };
    $('#save-role-menu-form').validate({
        submitHandler: function (e) {
            saveRoleMenuForm();
            return false;
        }
    });
    $('#save-role-admin-form').validate({
        submitHandler: function (e) {
            saveRoleAdminForm();
            return false;
        }
    });
    if (typeof menuList != 'undefined') {
        $.fn.zTree.init($('#menuTree'), setting, menuList);
    }
});
var nodesArr = [];

function getCheckTreeNodes(nodes) {
    $.each(nodes, function (i, obj) {
        if (obj.checked) {
            nodesArr.push(obj.id);
            if (obj.children) {
                getCheckTreeNodes(obj.children);
            }
        }
    });
}

function saveRoleAdminForm() {
    var form = $('#save-role-admin-form');
    var data = form.serialize();
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

function saveRoleMenuForm() {
    nodesArr = [];
    var zTree = $.fn.zTree.getZTreeObj('menuTree');
    getCheckTreeNodes(zTree.getNodes());
    var form = $('#save-role-menu-form');
    $('input[name=menu_ids]').val(nodesArr.join(','));
    var data = form.serialize();
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

function saveForm() {
    var form = $('#save-form');
    var data = form.serialize();
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