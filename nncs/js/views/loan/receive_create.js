$(document).ready(function() {
    $(document).on('click', '#search', function(e) {
        showLoader("Processing...", true);
        var _form = $("#frmSearch");

        $.ajax({
            type: "POST",
            url: ajaxUrl + "/loan/receive_list",
            data: _form.serialize(),
            success: function(res) {
                showLoader("", false);
                $("#ajaxContent").html('');
                $("#ajaxContent").html(res);
            }
        });
        e.preventDefault();
    });

    $(document).on("click", "#get_payment", function(e) {
        var _form = $("#deleteForm");
        var _url = ajaxUrl + '/loan/receive_form?' + _form.serialize();

        if ($("#deleteForm input[type='checkbox']:checked").length < 1) {
            $("#ajaxMessage").showAjaxMessage({html: "Please select at least one row", type: 'error'});
        } else {
            $("#ajaxMessage").hide();
            $("#containerForPaymentInfo").load(_url, function() {
                $("#containerForPaymentInfo").modal({backdrop: 'static', keyboard: false});
                showLoader("", false);
            });
        }
        e.preventDefault();
    });

    $(document).on("click", "#processPayment", function(e) {
        showLoader("One Moment Please...", true);
        var _form = $("#frmLoanReceive");
        var _url = ajaxUrl + '/loan/receive_save';
        var _listUrl = baseUrl + '/loan/receive';

        $.post(_url, _form.serialize(), function(res) {
            if (res.success === true) {
                $("#ajaxModalMessage").showAjaxMessage({html: res.message, type: 'success'});
                redirectTo(_listUrl);
            } else {
                $("#ajaxModalMessage").showAjaxMessage({html: res.message, type: 'error'});
            }
            showLoader("", false);
        }, "json");
        e.preventDefault();
    });
});