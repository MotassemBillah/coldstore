$(document).ready(function() {
    $(document).on('click', '#search', function(e) {
        var _dataset = {cid: $("#customerID").val(), sr_number: $("#sr_number").val(), agent_code: $("#agent_code").val()};

        $.ajax({
            type: "POST",
            url: ajaxUrl + "/customer/loan_form",
            data: _dataset,
            success: function(res) {
                $("#ajaxContent").html('');
                $("#ajaxContent").html(res);
            }
        });
        e.preventDefault();
    });

    $(document).on('input', '#cost_per_qty', function(e) {
        $(".rent_field").val($(this).val()).trigger('input');
        e.preventDefault();
    });

    $(document).on("input", ".qty", function(e) {
        if ($("#cost_per_qty").val() == "") {
            $("#ajaxMessage").showAjaxMessage({html: "Loan per quantity is not set yet", type: 'error'});
            $("#cost_per_qty").focus();
            return false;
        }

        var _srt = $(this).attr('data-info').split('/');
        var _curQty = parseInt(_srt[0]);
        var _srno = parseInt(_srt[1]);

        if ($(this).val() > _curQty) {
            $("#ajaxMessage").showAjaxMessage({html: "Value is not acceptable more than " + _curQty + " for " + _srno, type: 'error'});
            $(this).val(_curQty);
            return false;
        } else {
            $("#ajaxMessage").html('').hide();
            //$("#ajaxMessage").showAjaxMessage({html: "Continue...", type: 'info'}).show();
            return true;
        }
        e.preventDefault();
    });

    $(document).on('submit', '#frmCustomerLoan', function(e) {
        if ($("#cost_per_qty").val() == "") {
            $("#ajaxMessage").showAjaxMessage({html: "Please enter amount for per quantity amount.", type: 'error'});
            return false;
        }

        showLoader("Processing...", true);
        var _form = $(this);
        var _url = ajaxUrl + '/loan/create';
        var _backUrl = baseUrl + '/loan/payment';

        $.post(_url, _form.serialize(), function(resp) {
            if (resp.success === true) {
                $("#ajaxMessage").showAjaxMessage({html: resp.message, type: 'success'});
                redirectTo(_backUrl);
            } else {
                $("#ajaxMessage").showAjaxMessage({html: resp.message, type: 'error'});
            }
            showLoader("", false);
        }, "json");
        e.preventDefault();
        return false;
    });
});

function devide_value(elm, target) {
    var value = parseInt($(elm).val());
    var target_elm = $(elm).attr("data-target");
    var num = parseInt($(target_elm).val());
    var _total = parseInt(value / num);
    $(target).val(!isNaN(_total) ? _total : 0);
    $("#cost_per_qty").trigger('input');
    get_sum('unitprice', 'total_loan_amount');
}

function multiply_value(elm, target) {
    var value = !isNaN($(elm).val()) ? parseFloat($(elm).val()).toFixed(2) : 0;
    var target_elm = $(elm).attr("data-target");
    var num = !isNaN($(target_elm).val()) ? parseInt($(target_elm).val()) : 0;
    var _total = value * num;
    $(target).val(!isNaN(_total) ? _total : 0);
    var tsum = get_sum('unitprice', 'total_loan_amount');
    $("#total_loan_given").val(tsum);
}

function clear_search_fields() {
    $("#mobile_number").val('');
    $("#sr_number").val('');
    $("#agent_code").val('');
    $("#customer_name").val('');
    $("#father_name").val('');
    $("#ajaxContent").html("No data found! Please search again.");
}