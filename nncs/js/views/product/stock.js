$(document).ready(function() {
    $("#from_date, #to_date").datepicker({
        format: 'dd-mm-yyyy'
    });

    $(document).on("change", "#office_code", function(e) {
        if (this.checked) {
            $(this).attr('value', 1);
        } else {
            $(this).attr('value', 0);
        }
    });

    $(document).on("click", "#search", function(e) {
        showLoader("Processing...", true);
        var _form = $("#frmSearch");

        $.ajax({
            type: "POST",
            url: baseUrl + "/stock/search",
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