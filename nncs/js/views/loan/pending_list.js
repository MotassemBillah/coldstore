$(document).ready(function() {
    $(document).on('click', '#search', function(e) {
        showLoader("Processing...", true);
        var _form = $("#frmSearch");

        $.ajax({
            type: "POST",
            url: ajaxUrl + "/loan/pending_list",
            data: _form.serialize(),
            success: function(res) {
                showLoader("", false);
                $("#ajaxContent").html('');
                $("#ajaxContent").html(res);
            }
        });
        e.preventDefault();
    });
});