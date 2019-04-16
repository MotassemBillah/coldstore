$(document).ready(function() {
    $("#from_date, #to_date").datepicker({
        format: 'dd-mm-yyyy'
    });

    $(document).on("click", "#search", function(e) {
        showLoader("Processing...", true);
        var _form = $("#frmSearch");

        $.ajax({
            type: "POST",
            url: ajaxUrl + "/payments",
            data: _form.serialize(),
            success: function(res) {
                showLoader("", false);
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
            var _url = ajaxUrl + '/payments/deleteall';

            $.post(_url, _form.serialize(), function(res) {
                if (res.success === true) {
                    $("#ajaxMessage").removeClass('alert-danger').addClass('alert-success').html("");
                    $("#ajaxMessage").html(res.message).show();
                    $("tr.bg-danger").remove();
                    setTimeout(hide_ajax_message, 3000);
                    setTimeout(location.reload(), 3000);
                } else {
                    $("#ajaxMessage").removeClass('alert-success').addClass('alert-danger').html("");
                    $("#ajaxMessage").html(res.message).show();
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