$(document).ready(function() {
    $(document).on("click", "#search", function(e) {
        showLoader("Processing...", true);
        var _form = $("#frmSearch");

        $.ajax({
            type: "POST",
            url: baseUrl + "/account/search",
            data: _form.serialize(),
            success: function(res) {
                showLoader("", false);
                $("#ajaxContent").html('');
                $("#ajaxContent").html(res);
            }
        });
        e.preventDefault();
    });

    $(document).on("input", "input#q", function(e) {
        var _form = $("#frmSearch");

        $.ajax({
            type: "POST",
            url: baseUrl + "/account/search",
            data: _form.serialize(),
            success: function(res) {
                $("#ajaxContent").html('');
                $("#ajaxContent").html(res);
            }
        });
        e.preventDefault();
    });

    $(document).on('click', '#admin_del_btn', function(e) {
        var _rc = confirm('Are you sure about this action? This cannot be undone!');

        if (_rc === true) {
            showLoader("Processing...", true);
            var _form = $("#deleteForm");
            var _url = baseUrl + '/account/deleteall';

            $.post(_url, _form.serialize(), function(res) {
                if (res.success === true) {
                    $("#ajaxMessage").showAjaxMessage({html: res.message, type: 'success'});
                    $("tr.bg-danger").remove();
                    $("#clear_from").trigger('click');
                } else {
                    $("#ajaxMessage").showAjaxMessage({html: res.message, type: 'error'});
                }
                reset_index();
                showLoader("", false);
            }, "json");
        } else {
            return false;
        }
        e.preventDefault();
    });
});