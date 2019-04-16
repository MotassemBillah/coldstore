<?php
$this->breadcrumbs = array(
    'Loan' => array(AppUrl::URL_LOAN),
    'Receive Form'
);
?>
<form id="frmLoanReceive" name="frmLoanReceive" action="" method="post">
    <input type="hidden" id="both" name="form_option" value="both" readonly>
    <input type="hidden" id="loan_remain_hidden" value="" readonly>
    <input type="hidden" id="loan_duration" value="" readonly>
    <input type="hidden" id="loan_rate" value="" readonly>
    <input type="hidden" id="delivery_qty_remain_hidden" value="" readonly>
    <div class="row clearfix">
        <div class="col-md-2 col-sm-3">
            <div class="form-group">
                <label for="receipt_no"><?php echo Yii::t("strings", "Receipt No"); ?></label>
                <input type="number" id="receipt_no" name="receipt_no" class="form-control" value="<?php echo Delivery::model()->lastNumber(); ?>" required>
            </div>
        </div>
        <div class="col-md-2 col-sm-3">
            <div class="form-group">
                <label for="delivery_date"><?php echo Yii::t("strings", "Receive Date"); ?></label>
                <div class="input-group">
                    <input type="text" id="delivery_date" name="delivery_date" class="form-control" value="<?php echo date('d-m-Y'); ?>" readonly required>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-3">
            <div class="form-group">
                <label for="srno"><?php echo Yii::t("strings", "SR Number"); ?></label>
                <div class="input-group">
                    <input type="number" id="srno" name="srno" class="form-control" min="0" value="" required>
                    <span class="input-group-addon" id="search_srinfo" style="cursor: pointer;"><span class="fa fa-search"></span></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-4">
            <div class="form-group">
                <label for="delivery_person"><?php echo Yii::t("strings", "Receive Person"); ?></label>
                <input type="text" id="delivery_person" name="delivery_person" class="form-control">
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Information</h3>
                </div>
                <div class="panel-body" id="section_customer">
                    <div class="mask"></div>
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
                    <div class="mb_5 clearfix">
                        <label class="col-md-5 col-xs-6 text-right" for="product_lot">Lot No</label>
                        <input type="text" id="product_lot" name="Product[lot_no]" class="col-md-7 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-5 col-xs-6 text-right" for="product_qty">Quantity</label>
                        <input type="text" id="product_qty" name="Product[qty]" class="col-md-7 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-5 col-xs-6 text-right" for="product_empty_bag">Empty Bag</label>
                        <input type="text" id="product_empty_bag" name="Product[empty_bag]" class="col-md-7 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-5 col-xs-6 text-right" for="product_carrying">Carrying</label>
                        <input type="text" id="product_carrying" name="Product[carrying]" class="col-md-7 col-xs-6" value="" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Loan Information</h3>
                </div>
                <div class="panel-body" id="section_loan">
                    <div class="mask"></div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="loan_amount">Loan Receive</label>
                        <input type="number" id="loan_amount" name="LoanReceived[amount]" class="col-md-3 col-xs-6" value="" min="0">
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="loan_remain">Loan Remain</label>
                        <input type="number" id="loan_remain" name="LoanReceived[loan_remain]" class="col-md-3 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="loan_interest">Interest</label>
                        <input type="number" id="loan_interest" name="LoanReceived[interest]" class="col-md-3 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="loan_day">Days</label>
                        <input type="number" id="loan_day" name="LoanReceived[day]" class="col-md-3 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="delivery_total">Sub Total</label>
                        <input type="number" id="delivery_total" name="LoanReceived[total]" class="col-md-3 col-xs-6 fan_charge_total" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="delivery_discount">Discount</label>
                        <input type="number" id="delivery_discount" name="LoanReceived[discount]" class="col-md-3 col-xs-6" value="" min="0">
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="delivery_net_amount">Net Total</label>
                        <input type="number" id="delivery_net_amount" name="LoanReceived[net_amount]" class="col-md-3 col-xs-6" value="" readonly>
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

        $(document).on("click", "#search_srinfo", function() {
            var _srno = $("#srno").val();
            var _date = $("#delivery_date").val();
            var _url = ajaxUrl + '/misc/sr_loan_info';

            if (_srno == "") {
                showLoader("Sr number required", true);
                setTimeout(function() {
                    showLoader("", false);
                }, 1000);
            } else {
                showLoader("Fetching Data...", true);
                $.post(_url, {srno: _srno, dt: _date}, function(resp) {
                    if (resp.success === true) {
                        $("#ajaxMessage").hide();
                        if (resp.loan.item === false) {
                            $("#section_customer .mask").show();
                            $("#section_customer input").val('');
                            $("#section_loan .mask").show();
                            $("#section_loan input").val('');
                            disable("#btnSave");
                            $("#ajaxMessage").showAjaxMessage({html: "SR number is not found in loan.", type: "error"});
                        } else {
                            $("#section_customer .mask").hide();
                            // Customer Information
                            $("#customer_name").val(resp.customer.name);
                            $("#customer_father").val(resp.customer.father_name);
                            $("#customer_village").val(resp.customer.village);
                            $("#customer_thana").val(resp.customer.thana);
                            $("#customer_district").val(resp.customer.dist);
                            // Product Information
                            $("#product_lot").val(resp.product.lot_no);
                            $("#product_qty").val(resp.product.qty);
                            $("#product_empty_bag").val(resp.product.loan_bag);
                            $("#product_carrying").val(resp.product.carrying);
                            // Loan Information
                            $("#loan_amount").attr("max", resp.loan.amount);
                            $("#loan_remain").val(resp.loan.amount);
                            $("#loan_remain_hidden").val(resp.loan.amount);
                            $("#loan_day").val(resp.loan.day);
                            $("#loan_duration").val(resp.loan.duration);
                            $("#loan_rate").val(resp.loan.rate);
                            if (resp.loan.amount == 0) {
                                $("#section_customer .mask").show();
                                $("#section_customer input").val('');
                                $("#section_loan .mask").show();
                                $("#section_loan input").val('');
                                disable("#btnSave");
                                $("#ajaxMessage").showAjaxMessage({html: "No loan remain on SR <b>" + _srno + "</b>", type: "error"});
                            } else {
                                $("#section_customer .mask").hide();
                                $("#section_loan .mask").hide();
                                enable("#btnSave");
                            }
                        }
                    } else {
                        $("#section_customer .mask").show();
                        $("#section_customer input").val('');
                        $("#section_loan .mask").show();
                        $("#section_loan input").val('');
                        $("#ajaxMessage").showAjaxMessage({html: resp.message, type: "error"});
                    }
                    showLoader("", false);
                }, "json");
            }
        });

        $(document).on("input", "#loan_amount", function(e) {
            var _camount = parseInt($("#loan_amount").val());
            var _ramount = parseInt($("#loan_remain_hidden").val());
            if (_ramount == '') {
                _ramount = 0;
            }
            if (_camount == '' || isNaN(_camount)) {
                _camount = 0;
                $("#loan_remain").val(_ramount);
            } else {
                $("#loan_remain").val(_ramount - _camount);
            }

            var _loanAmount = $(this).val();
            var _day = parseInt($("#loan_day").val());
            var _duration = parseInt($("#loan_duration").val());
            var _percent = parseInt($("#loan_rate").val());
            $("#loan_interest").val(getInterest(_loanAmount, _day, _duration, _percent));

            update_sub_total();
            update_net_total();
            e.preventDefault();
        });

        $(document).on("input", "#delivery_discount", function(e) {
            update_net_total();
            e.preventDefault();
        });

        $(document).on('submit', '#frmLoanReceive', function(e) {
            showLoader("Processing...", true);
            var _form = $(this);
            var _url = ajaxUrl + '/loan/receive_single';

            $.post(_url, _form.serialize(), function(resp) {
                if (resp.success === true) {
                    redirectTo(baseUrl + '/loan/receive');
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

    function update_sub_total() {
        var _total = 0;
        var loan = $("#loan_amount").val();
        var interest = $("#loan_interest").val();
        if (loan == '' || isNaN(loan)) {
            loan = 0;
        }
        if (interest == '' || isNaN(interest)) {
            interest = 0;
        }

        _total += parseInt(parseInt(loan) + parseInt(interest));
        $("#delivery_total").val(_total);
    }

    function update_net_total() {
        var _total = 0;
        var sub_total = $("#delivery_total").val();
        var discount = $("#delivery_discount").val();

        if (sub_total == '') {
            sub_total = 0;
        }
        if (discount == '') {
            discount = 0;
        }

        _total += parseInt(parseInt(sub_total) - parseInt(discount));
        $("#delivery_net_amount").val(_total);
    }
</script>