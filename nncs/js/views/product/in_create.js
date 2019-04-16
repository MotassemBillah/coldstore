$(document).ready(function() {
    $(document).on("change", ".toggle_customer", function() {
        if ($(this).val() == "exist") {
            $("#customer_exist_panel").slideDown(200);
        } else {
            $("#customer_exist_panel").slideUp(200);
        }
    });

    $(document).on("click", "#customerSearch", function(e) {
        if ($("#customer_mo").val() == "") {
            $("#customer_mo").parent().addClass('has-error');
            $("#customer_mo").focus();
            $("#ajaxMessage").showAjaxMessage({html: "Please enter mobile to search customer", type: 'info'});
            return false;
        } else {
            $("#ajaxMessage").hide();
        }

        showLoader("Processing...", true);
        var _srcForm = $("#frmProductIn");
        var _url = ajaxUrl + '/customer/search';
        $.post(_url, _srcForm.serialize(), function(data) {
            if (data.success === true) {
                $("#customer_new").prop("checked", false);
                $("#customer_exist").prop("checked", true);
                $("#customer_exist_panel").slideDown(150);
                $("#customer_new_panel").slideUp(150);
                enable("#customer");
                $("#customer").html(data.html);
                $("#customer").trigger("click");
            } else {
                //disable("#customer").html('');
                $("#customer_exist_panel").slideUp(150);
                $("#customer_new_panel").slideDown(150);
                $("#Customer_mobile").val($("#customer_mo").val());
                $("#customer_new").prop("checked", true);
                $("#customer_exist").prop("checked", false);
            }
            showLoader("", false);
        }, "json");
        e.preventDefault();
    });

    $(document).on("submit", "#frmProductIn", function() {
        showLoader("Processing...", true);
        var _form = $(this);
        var _url = baseUrl + '/product_in/save';
        var _listUrl = baseUrl + '/product_in/view?id=';

        $.post(_url, _form.serialize(), function(response) {
            if (response.success === true) {
                redirectTo(_listUrl + response.skey);
            } else {
                $("#ajaxMessage").showAjaxMessage({html: response.message, type: 'error'});
            }
            showLoader("", false);
        }, "json");
        return false;
    });
});


