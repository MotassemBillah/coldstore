<?php
$this->breadcrumbs = array(
    'Delivery' => array(AppUrl::URL_DELIVERY),
    'Form'
);
?>
<form id="frmSingleDelivery" name="frmSingleDelivery" action="" method="post">
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
                <label for="delivery_date"><?php echo Yii::t("strings", "Delivery Date"); ?></label>
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
                <label for="delivery_person"><?php echo Yii::t("strings", "Delivery Person"); ?></label>
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
                    <h3 class="panel-title">Delivery Information</h3>
                </div>
                <div class="panel-body" id="section_delivery">
                    <div class="mask"></div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="delivery_quantity">Quantity</label>
                        <input type="number" id="delivery_quantity" name="Delivery[quantity]" class="col-md-3 col-xs-6 delivery_qty" value="" min="0" required>
                        <label class="col-md-3 col-xs-6 text-right" for="delivery_quantity_remain">Remain Quantity</label>
                        <input type="number" id="delivery_quantity_remain" name="Delivery[quantity_remain]" class="col-md-3 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="delivery_rent_total">Rent Total</label>
                        <input type="number" id="delivery_rent_total" name="Delivery[rent_total]" class="col-md-3 col-xs-6" value="" readonly>
                        <label class="col-md-3 col-xs-6 text-right" for="delivery_rent">Rent</label>
                        <input type="number" id="delivery_rent" name="Delivery[rent]" class="col-md-3 col-xs-6" value="" min="0" required readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="loan_amount">Loan Receive</label>
                        <input type="number" id="loan_amount" name="LoanReceived[amount]" class="col-md-3 col-xs-6" value="" min="0">
                        <label class="col-md-3 col-xs-6 text-right" for="loan_remain">Loan Remain</label>
                        <input type="number" id="loan_remain" name="LoanReceived[loan_remain]" class="col-md-3 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="loan_interest">Interest</label>
                        <input type="number" id="loan_interest" name="LoanReceived[interest]" class="col-md-3 col-xs-6" value="" readonly>
                        <label class="col-md-3 col-xs-6 text-right" for="loan_day">Days</label>
                        <input type="number" id="loan_day" name="LoanReceived[day]" class="col-md-3 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="empty_bag">Empty Bag</label>
                        <input type="number" id="empty_bag" name="Delivery[empty_bag]" class="col-md-3 col-xs-6" value="" min="0">
                        <label class="col-md-3 col-xs-6 text-right" for="empty_bag_price">Empty Bag Price</label>
                        <input type="number" id="empty_bag_price" name="Delivery[empty_bag_price]" class="col-md-3 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="empty_bag_amount">Empty Bag Amount</label>
                        <input type="number" id="empty_bag_amount" name="Delivery[empty_bag_amount]" class="col-md-3 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="carrying">Carrying</label>
                        <input type="number" id="carrying" name="Delivery[carrying]" class="col-md-3 col-xs-6" value="" min="0">
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="delivery_fan_charge_qty">Fan Charge Qty</label>
                        <input type="number" id="delivery_fan_charge_qty" name="Delivery[fan_charge_qty]" class="col-md-3 col-xs-6" value="" min="0">
                        <label class="col-md-3 col-xs-6 text-right" for="delivery_fan_charge">Fan Charge</label>
                        <input type="number" id="delivery_fan_charge" name="Delivery[fan_charge]" class="col-md-3 col-xs-6" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="delivery_fan_charge_total">Fan Charge Total</label>
                        <input type="number" id="delivery_fan_charge_total" name="Delivery[fan_charge_total]" class="col-md-3 col-xs-6 fan_charge_total" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="delivery_total">Sub Total</label>
                        <input type="number" id="delivery_total" name="Delivery[total]" class="col-md-3 col-xs-6 fan_charge_total" value="" readonly>
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="delivery_discount">Discount</label>
                        <input type="number" id="delivery_discount" name="Delivery[discount]" class="col-md-3 col-xs-6" value="" min="0">
                    </div>
                    <div class="mb_5 clearfix">
                        <label class="col-md-3 col-xs-6 text-right" for="delivery_net_amount">Net Total</label>
                        <input type="number" id="delivery_net_amount" name="Delivery[net_amount]" class="col-md-3 col-xs-6" value="" readonly>
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
            var _fanc = $("#delivery_fan_charge").val();
            var _url = ajaxUrl + '/misc/find_srloan_info';
            if (_fanc == '') {
                _fanc = 0;
            }

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
                        // Delivery Information
                        //$("#delivery_quantity").val(resp.delivery.qty);
                        $("#delivery_quantity").attr("max", resp.delivery.qty);
                        $("#delivery_quantity_remain").val(resp.delivery.remain);
                        $("#delivery_qty_remain_hidden").val(resp.delivery.remain);
                        $("#delivery_rent").val(resp.delivery.rent);
                        $("#delivery_rent").attr("max", resp.delivery.rent);
                        $("#delivery_rent_total").val(resp.delivery.rent_total);
                        //$("#loan_amount").val(resp.loan.amount);
                        $("#loan_amount").attr("max", resp.loan.amount);
                        $("#loan_remain").val(resp.loan.amount);
                        $("#loan_remain_hidden").val(resp.loan.amount);
                        $("#loan_day").val(resp.loan.day);
                        $("#loan_interest").val(resp.loan.interest);
                        $("#empty_bag").val(resp.product.loan_bag);
                        $("#empty_bag").attr("max", resp.product.loan_bag);
                        $("#empty_bag_price").val(resp.delivery.eb_price);
                        $("#carrying").val(resp.product.carrying);
                        $("#carrying").attr("max", resp.product.carrying);
                        $("#delivery_fan_charge").val(resp.delivery.fan_charge);
                        $("#delivery_total").val(resp.delivery.net_total);
                        $("#delivery_net_amount").val(resp.delivery.net_total);
                        $("#loan_duration").val(resp.loan.duration);
                        $("#loan_rate").val(resp.loan.rate);
                        if (resp.delivery.qty == '') {
                            $("#section_delivery .mask").show();
                            disable("#btnSave");
                            $("#ajaxMessage").showAjaxMessage({html: "Already delivered", type: "error"});
                        } else {
                            $("#section_delivery .mask").hide();
                            enable("#btnSave");
                        }
                    } else {
                        $("#ajaxMessage").showAjaxMessage({html: resp.message, type: "error"});
                        clear_delivery_data();
                    }
                    showLoader("", false);
                }, "json");
            }
        });

        $(document).on("input", "#delivery_quantity", function(e) {
            var _qty = parseInt($("#delivery_quantity").val());
            var _hidden_qty = parseInt($("#delivery_qty_remain_hidden").val());
            var _rent = parseInt($("#delivery_rent").val());
            if (_qty == '') {
                _qty = 0;
            }
            if (_rent == '') {
                _rent = 0;
            }
            $("#delivery_quantity_remain").val(_hidden_qty - _qty);
            $("#delivery_rent_total").val(_qty * _rent);
            update_sub_total();
            update_net_total();
            e.preventDefault();
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

        $(document).on("input", "#empty_bag", function(e) {
            var _qty = parseInt($("#empty_bag").val());
            var _rent = parseInt($("#empty_bag_price").val());
            if (_qty == '') {
                _qty = 0;
            }
            if (_rent == '') {
                _rent = 0;
            }
            $("#empty_bag_amount").val(_qty * _rent);
            update_sub_total();
            update_net_total();
            e.preventDefault();
        });

        $(document).on("input", "#carrying", function(e) {
            update_sub_total();
            update_net_total();
            e.preventDefault();
        });

        $(document).on("input", "#delivery_fan_charge_qty", function(e) {
            var _qty = parseInt($("#delivery_fan_charge_qty").val());
            var _fanc = parseInt($("#delivery_fan_charge").val());
            if (_qty == '') {
                _qty = 0;
            }
            if ('' == _fanc || isNaN(_fanc)) {
                _fanc = 0;
            }
            $("#delivery_fan_charge_total").val(_qty * _fanc);
            update_sub_total();
            update_net_total();
            e.preventDefault();
        });

        $(document).on("input", "#delivery_discount", function(e) {
            update_net_total();
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

    function update_sub_total() {
        var _total = 0;
        var rent = $("#delivery_rent_total").val();
        var loan = $("#loan_amount").val();
        var interest = $("#loan_interest").val();
        var empty_bag = $("#empty_bag_amount").val();
        var carrying = $("#carrying").val();
        var fan_charge = $("#delivery_fan_charge_total").val();

        if (rent == '' || isNaN(rent)) {
            rent = 0;
        }
        if (loan == '' || isNaN(loan)) {
            loan = 0;
        }
        if (interest == '' || isNaN(interest)) {
            interest = 0;
        }
        if (empty_bag == '' || isNaN(empty_bag)) {
            empty_bag = 0;
        }
        if (carrying == '' || isNaN(carrying)) {
            carrying = 0;
        }
        if (fan_charge == '' || isNaN(fan_charge)) {
            fan_charge = 0;
        }

        _total += parseInt(parseInt(rent) + parseInt(loan) + parseInt(interest) + parseInt(empty_bag) + parseInt(carrying) + parseInt(fan_charge));
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