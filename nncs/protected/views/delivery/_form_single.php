<?php
$this->breadcrumbs = array(
    'Loan' => array(AppUrl::URL_LOAN),
    'Receive Form'
);
?>
<form id="frmSingleDelivery" name="frmSingleDelivery" action="" method="post">
    <div class="row clearfix">
        <div class="col-md-2 col-sm-3">
            <div class="form-group">
                <label for="delivery_date"><?php echo Yii::t("strings", "Date"); ?></label>
                <div class="input-group">
                    <input type="text" id="delivery_date" name="delivery_date" class="form-control" value="<?php echo date('d-m-Y'); ?>" required readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-3">
            <div class="form-group">
                <label for="srno"><?php echo Yii::t("strings", "SR Number"); ?></label>
                <input type="number" id="srno" name="srno" class="form-control" min="0" value="" required>
            </div>
        </div>
        <div class="col-md-2 col-sm-3">
            <div class="form-group">
                <label for="total_amount"><?php echo Yii::t("strings", "Total Amount"); ?></label>
                <input type="number" id="total_amount" name="total_amount" class="form-control" readonly>
            </div>
        </div>
        <div class="col-md-4 col-sm-4">
            <div class="form-group">
                <label for="delivery_person"><?php echo Yii::t("strings", "Delivery Person"); ?></label>
                <input type="text" id="delivery_person" name="delivery_person" class="form-control">
            </div>
        </div>
    </div>

    <div class="form-group clearfix">
        <label>Select Option : </label>
        <label for="loan_receive"><input type="radio" id="loan_receive" name="form_option" class="chk_no_mvam form_select" value="loan_receive">&nbsp;Loan Receive</label>
        <label for="delivery"><input type="radio" id="delivery" name="form_option" class="chk_no_mvam form_select" value="delivery">&nbsp;Delivery</label>
        <label for="both"><input type="radio" id="both" name="form_option" class="chk_no_mvam form_select" value="both" checked>&nbsp;Both</label>
        <label for="not_applicable"><input type="radio" id="not_applicable" name="form_option" class="chk_no_mvam form_select" value="not_applicable">&nbsp;Not Applicable</label>
    </div>

    <div class="row clearfix">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Customer Information</h3>
                </div>
                <div class="panel-body">
                    <div class="mb_5 clearfix">
                        <label class="col-md-5 col-xs-6 text-right" for="customer_name">Name</label>
                        <input type="text" id="customer_name" name="Customer[name]" class="col-md-7 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-5 col-xs-6 text-right" for="customer_father">Father Name</label>
                        <input type="text" id="customer_father" name="Customer[father_name]" class="col-md-7 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-5 col-xs-6 text-right" for="customer_village">Village</label>
                        <input type="text" id="customer_village" name="Customer[village]" class="col-md-7 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-5 col-xs-6 text-right" for="customer_thana">Thana</label>
                        <input type="text" id="customer_thana" name="Customer[thana]" class="col-md-7 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-5 col-xs-6 text-right" for="customer_district">District</label>
                        <input type="text" id="customer_district" name="Customer[district]" class="col-md-7 col-xs-6" value="" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Loan Information</h3>
                </div>
                <div class="panel-body"  id="section_loan_receive">
                    <div class="mask"></div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="loan_quantity">Quantity</label>
                        <input type="number" id="loan_quantity" name="LoanReceived[quantity]" class="col-md-6 col-xs-6 loan_qty" value="" min="0">
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="loan_per_bag">Per Bag Loan</label>
                        <input type="number" id="loan_per_bag" name="LoanReceived[per_bag_loan]" class="col-md-6 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="loan_amount">Loan Amount</label>
                        <input type="number" id="loan_amount" name="LoanReceived[amount]" class="col-md-6 col-xs-6 loan_amount" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="loan_day">Days</label>
                        <input type="number" id="loan_day" name="LoanReceived[day]" class="col-md-6 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="loan_interest">Interest</label>
                        <input type="number" id="loan_interest" name="LoanReceived[interest]" class="col-md-6 col-xs-6 interest" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="loan_total">Loan Total</label>
                        <input type="number" id="loan_total" name="LoanReceived[total]" class="col-md-6 col-xs-6 loan_total" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="loan_discount">Discount</label>
                        <input type="number" id="loan_discount" name="LoanReceived[discount]" class="col-md-6 col-xs-6" value="" min="0">
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="loan_net_amount">Net Amount</label>
                        <input type="number" id="loan_net_amount" name="LoanReceived[net_amount]" class="col-md-6 col-xs-6" value="" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Delivery Information</h3>
                </div>
                <div class="panel-body" id="section_delivery">
                    <div class="mask"></div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="delivery_quantity">Quantity</label>
                        <input type="number" id="delivery_quantity" name="Delivery[quantity]" class="col-md-6 col-xs-6 delivery_qty" value="" min="0" required>
                        <input type="hidden" id="delivery_quantity_hidden" value="">
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="delivery_rent">Rent</label>
                        <input type="number" id="delivery_rent" name="Delivery[rent]" class="col-md-6 col-xs-6 rent" value="" min="0" required>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="delivery_rent_total">Rent Amount</label>
                        <input type="number" id="delivery_rent_total" name="Delivery[rent_total]" class="col-md-6 col-xs-6 rent_total" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="delivery_fan_charge_qty">Fan Charge Qty</label>
                        <input type="number" id="delivery_fan_charge_qty" name="Delivery[fan_charge_qty]" class="col-md-6 col-xs-6" value="">
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="delivery_fan_charge">Fan Charge</label>
                        <input type="number" id="delivery_fan_charge" name="Delivery[fan_charge]" class="col-md-6 col-xs-6 fan_charge" value="">
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="delivery_fan_charge_total">Fan Charge Total</label>
                        <input type="number" id="delivery_fan_charge_total" name="Delivery[fan_charge_total]" class="col-md-6 col-xs-6 fan_charge_total" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="delivery_total">Total Rent</label>
                        <input type="number" id="delivery_total" name="Delivery[total]" class="col-md-6 col-xs-6 fan_charge_total" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="delivery_discount">Discount</label>
                        <input type="number" id="delivery_discount" name="Delivery[discount]" class="col-md-6 col-xs-6" value="" min="0">
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-6 col-xs-6 text-right" for="delivery_net_amount">Net Amount</label>
                        <input type="number" id="delivery_net_amount" name="Delivery[net_amount]" class="col-md-6 col-xs-6" value="" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group text-center">
        <?php echo CHtml::submitButton('Save', array('class' => 'btn btn-primary', 'id' => 'btnSave')); ?>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $("#delivery_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on("focusout", "#srno", function() {
            var _fanc = $("#delivery_fan_charge").val();
            var _url = ajaxUrl + '/misc/find_srloan_info';
            if (_fanc == '') {
                _fanc = 0;
            }

            if ($(this).val() == "") {
                showLoader("Sr number required", true);
                setTimeout(function() {
                    showLoader("", false);
                }, 1000);
            } else {
                showLoader("Fetching Data...", true);
                $.post(_url, {srno: $(this).val()}, function(resp) {
                    if (resp.success === true) {
                        $("#ajaxMessage").hide();
                        $("#customer_name").val(resp.customer.name);
                        $("#customer_father").val(resp.customer.father_name);
                        $("#customer_village").val(resp.customer.village);
                        $("#customer_thana").val(resp.customer.thana);
                        $("#customer_district").val(resp.customer.dist);
                        $("#loan_quantity").val(resp.loan_qty);
                        $("#loan_quantity").attr("max", resp.loan_qty);
                        $("#loan_per_bag").val(resp.cost);
                        $("#loan_amount").val(resp.amount);
                        $("#loan_day").val(resp.day);
                        $("#loan_interest").val(resp.interest);
                        $("#loan_total").val(resp.loan_total);
                        if (resp.loan_qty == '') {
                            clear_loan_data();
                            disable("#section_loan_receive input");
                            $("#section_loan_receive .mask").show();
                            $("#delivery").prop('checked', true);
                            //$("#ajaxMessage").showAjaxMessage({html: "All Loan Clear", type: "error"});
                        } else {
                            $("#section_loan_receive .mask").hide();
                            $("#delivery").prop('checked', false);
                            enable("#section_loan_receive input");
                        }
                        $("#delivery_quantity").val(resp.qty);
                        $("#delivery_quantity").attr("max", resp.qty);
                        $("#delivery_quantity_hidden").val(resp.qty);
                        $("#delivery_rent").val(resp.rent);
                        $("#delivery_rent").attr("max", resp.rent);
                        $("#delivery_rent_total").val(resp.rent_total);
                        $("#delivery_fan_charge").val(resp.fan_charge);
                        var _fancqty = $("#delivery_fan_charge_qty").val();
                        if (_fancqty == '') {
                            _fancqty = 0;
                        }
                        $("#delivery_fan_charge_total").val($("#_fancqty").val() * _fancqty);
                        $("#delivery_total").val(resp.rent_total);
                        $("#delivery_net_amount").val(resp.rent_total);
                        if (resp.qty == '') {
                            clear_delivery_data();
                            disable("#section_delivery input");
                            $("#section_delivery .mask").show();
                            $("#loan_receive").prop('checked', true);
                            //$("#ajaxMessage").showAjaxMessage({html: "Already Delivered", type: "error"});
                        } else {
                            $("#section_delivery .mask").hide();
                            $("#loan_receive").prop('checked', false);
                            enable("#section_delivery input");
                        }
                        if (resp.qty == '' && resp.loan_qty == '') {
                            clear_delivery_data();
                            $("#section_loan_receive .mask").show();
                            $("#section_delivery .mask").show();
                            $("#not_applicable").prop('checked', true);
                            //$("#ajaxMessage").showAjaxMessage({html: "Already Delivered", type: "error"});
                        }
                        //$("#total_amount").val(sum_total());
                        $("#loan_discount").trigger('input');
                        $("#delivery_discount").trigger('input');
                    } else {
                        $("#ajaxMessage").showAjaxMessage({html: resp.message, type: "error"});
                        clear_loan_data();
                        clear_delivery_data();
                    }
                    showLoader("", false);
                }, "json");
            }
        });

        $(document).on("change", ".form_select", function(e) {
            if (this.value == 'not_applicable') {
                disable("#section_delivery input");
                disable("#section_loan_receive input");
                $("#section_loan_receive .mask").show();
                $("#section_delivery .mask").show();
            } else if (this.value == 'loan_receive') {
                disable("#section_delivery input");
                enable("#section_loan_receive input");
                $("#section_loan_receive .mask").hide();
                $("#section_delivery .mask").show();
            } else if (this.value == 'delivery') {
                disable("#section_loan_receive input");
                enable("#section_delivery input");
                $("#section_delivery .mask").hide();
                $("#section_loan_receive .mask").show();
            } else {
                enable("#section_loan_receive input");
                enable("#section_delivery input");
                $(".mask").hide();
            }
            $("#total_amount").val(sum_total());
            e.preventDefault();
        });

        $(document).on("input", "#loan_quantity", function(e) {
            if ($('input[type="radio"]:checked').val() == 'both') {
                update_delivery_qty();
            }
            update_loan_amount();
            update_loan_total();
            update_loan_net_total();
            $("#total_amount").val(sum_total());
            e.preventDefault();
        });

        $(document).on("input", "#loan_discount", function(e) {
            update_loan_net_total();
            $("#total_amount").val(sum_total());
            e.preventDefault();
        });

        $(document).on("input", "#delivery_quantity, #delivery_rent", function(e) {
            var _qty = parseInt($("#delivery_quantity").val());
            var _rent = parseInt($("#delivery_rent").val());
            if (_qty == '') {
                _qty = 0;
            }
            if ('' == _rent || isNaN(_rent)) {
                _rent = 0;
            }
            $("#delivery_rent_total").val(_qty * _rent);
            update_delivery_total();
            update_delivery_net_total();
            $("#total_amount").val(sum_total());
            e.preventDefault();
        });

        $(document).on("input", "#delivery_fan_charge_qty, #delivery_fan_charge", function(e) {
            var _qty = parseInt($("#delivery_fan_charge_qty").val());
            var _fanc = parseInt($("#delivery_fan_charge").val());
            if (_qty == '') {
                _qty = 0;
            }
            if ('' == _fanc || isNaN(_fanc)) {
                _fanc = 0;
            }
            $("#delivery_fan_charge_total").val(_qty * _fanc);
            update_delivery_total();
            update_delivery_net_total();
            $("#total_amount").val(sum_total());
            e.preventDefault();
        });

        $(document).on("input", "#delivery_discount", function(e) {
            update_delivery_net_total();
            $("#total_amount").val(sum_total());
            e.preventDefault();
        });

        $(document).on('submit', '#frmSingleDelivery', function(e) {
            showLoader("Processing...", true);
            var _form = $(this);
            var _url = baseUrl + '/delivery/save_single';
            var _srno = $("#srno").val();

            $.post(_url, _form.serialize(), function(resp) {
                if (resp.success === true) {
                    _form[0].reset();
                    redirectTo(baseUrl + '/sr/view/id/' + _srno);
                } else {
                    $("#ajaxMessage").showAjaxMessage({html: resp.message, type: 'error'});
                }
                showLoader("", false);
            }, "json");
            e.preventDefault();
            return false;
        });
    });

    function getInterest(amount, day, period, percent) {
        var interest = 0;
        interest += parseFloat((percent * day * amount) / (100 * period)).toFixed(2);
        return Math.ceil(interest);
    }

    function clear_delivery_data() {
        $("#delivery_quantity").val('');
        $("#delivery_rent").val('');
        $("#delivery_rent_total").val('');
        $("#delivery_fan_charge").val('');
        $("#delivery_fan_charge_total").val('');
    }

    function clear_loan_data() {
        $("#loan_quantity").val('');
        $("#loan_per_bag").val('');
        $("#loan_amount").val('');
        $("#loan_day").val('');
        $("#loan_interest").val('');
        $("#loan_total").val('');
    }

    function update_loan_amount() {
        var _total = 0;
        var _qty = $("#loan_quantity").val();
        var _rent = $("#loan_per_bag").val();

        if (_qty == '') {
            _qty = 0;
        }
        if (_rent == '') {
            _rent = 0;
        }
        _total += parseInt(parseInt(_qty) * parseInt(_rent));
        $("#loan_amount").val(_total);
    }

    function update_loan_total() {
        var _total = 0;
        var loan_amount = $("#loan_amount").val();
        var loan_interest = $("#loan_interest").val();

        if (loan_amount == '') {
            loan_amount = 0;
        }
        if (loan_interest == '') {
            loan_interest = 0;
        }

        _total += parseInt(parseInt(loan_amount) + parseInt(loan_interest));
        $("#loan_total").val(_total);
    }

    function update_loan_net_total() {
        var _total = 0;
        var loan_total = $("#loan_total").val();
        var discount = $("#loan_discount").val();

        if (loan_total == '') {
            loan_total = 0;
        }
        if (discount == '') {
            discount = 0;
        }

        _total += parseInt(parseInt(loan_total) - parseInt(discount));
        $("#loan_net_amount").val(_total);
    }

    function update_delivery_qty() {
        var _loan_qty = parseInt($("#loan_quantity").val());
        var _delvery_qty = parseInt($("#delivery_quantity_hidden").val());
        var _totalQty = 0;

        if (_loan_qty == '') {
            _loan_qty = 0;
        }
        if (_delvery_qty == '' || isNaN(_delvery_qty)) {
            _delvery_qty = 0;
        }
        _totalQty += parseInt(_delvery_qty + _loan_qty);
        $("#delivery_quantity").val(_totalQty);
        $("#delivery_quantity").attr("max", _totalQty);
        $("#delivery_quantity").trigger('input');
    }

    function update_delivery_rent_total() {
        var _total = 0;
        var _qty = $("#delivery_quantity").val();
        var _rent = $("#delivery_rent").val();

        if (_qty == '') {
            _qty = 0;
        }
        if (_rent == '') {
            _rent = 0;
        }
        _total += parseInt(parseInt(_qty) * parseInt(_rent));
        $("#delivery_rent_total").val(_total);
    }

    function update_delivery_fancharge_total() {
        var _total = 0;
        var _fan_charge_qty = $("#delivery_fan_charge_qty").val();
        var _fan_charge = $("#delivery_fan_charge").val();

        if (_fan_charge_qty == '') {
            _fan_charge_qty = 0;
        }
        if (_fan_charge == '') {
            _fan_charge = 0;
        }
        _total += parseInt(parseInt(_fan_charge_qty) * parseInt(_fan_charge));
        $("#delivery_fan_charge_total").val(_total);
    }

    function update_delivery_total() {
        var _total = 0;
        var rentTotal = $("#delivery_rent_total").val();
        var fanChargeTotal = $("#delivery_fan_charge_total").val();

        if (rentTotal == '') {
            rentTotal = 0;
        }
        if (fanChargeTotal == '') {
            fanChargeTotal = 0;
        }

        _total += parseInt(parseInt(rentTotal) + parseInt(fanChargeTotal));
        $("#delivery_total").val(_total);
    }

    function update_delivery_net_total() {
        var _total = 0;
        var netTotal = $("#delivery_total").val();
        var discount = $("#delivery_discount").val();

        if (netTotal == '') {
            netTotal = 0;
        }
        if (discount == '') {
            discount = 0;
        }

        _total += parseInt(parseInt(netTotal) - parseInt(discount));
        $("#delivery_net_amount").val(_total);
    }

    function sum_total() {
        var _option = $('input[type="radio"]:checked').val();
        var _total = 0;
        var loanAmount = $("#loan_net_amount").val();
        var deliveryAmount = $("#delivery_net_amount").val();

        if (loanAmount == '') {
            loanAmount = 0;
        }
        if (deliveryAmount == '') {
            deliveryAmount = 0;
        }
        if (_option == 'loan_receive') {
            _total += parseInt(loanAmount);
        } else if (_option == 'delivery') {
            _total += parseInt(deliveryAmount);
        } else {
            _total += parseInt(parseInt(loanAmount) + parseInt(deliveryAmount));
        }
        return _total;
    }
</script>