<?php
$this->breadcrumbs = array(
    'Product Out' => array(AppUrl::URL_PRODUCT_OUT),
    'Invoice'
);
?>
<div class="content-panel">
    <div id="printDiv">
        <div class="row form-group clearfix text-center txt_left_xs mp_center media_print">
            <?php if (!empty($this->settings->title)): ?>
                <h1 style="font-size: 30px;margin: 0;"><?php echo $this->settings->title; ?></h1>
            <?php endif; ?>
            <?php if (!empty($this->settings->author_address)): ?>
                <?php echo $this->settings->author_address; ?><br>
            <?php endif; ?>
            <h4 class="inv_title"><u><?php echo Yii::t("strings", "Delivery Invoice"); ?></u></h4>
        </div>
        <hr>
        <?php
        $sr_actual_due = ProductIn::model()->srDue($model->sr_no)->net_amount;
        $sr_due_paid = ProductIn::model()->srDue($model->sr_no)->paid_amount;
        $sr_current_due = ProductIn::model()->srDue($model->sr_no)->due_amount;
        //$sr_actual_due = ($sr_due_paid + $sr_current_due);
        //$sr_due_paid = $model->customer_payment->due_paid;
        ?>
        <div class="row clearfix">
            <div class="col-md-6 col-sm-6 mpw_50 form-group">
                <h3 class="panel-title">Customer Information</h3>
                <hr>
                <?php if (!empty($model->agent_code)) : ?>
                    <div class="form-group mb_10">
                        <label class="inline_label">Agent Code</label>:&nbsp;<span class=""><?php echo $model->agent_code; ?></span>
                    </div>
                <?php endif; ?>
                <div class="form-group mb_10">
                    <label class="inline_label">S.R Number</label>:&nbsp;<span class=""><?php echo $model->sr_no; ?></span>
                </div>
                <div class="form-group mb_10">
                    <label class="inline_label">Current Stock</label>:&nbsp;<span class=""><?php echo AppObject::currentStock($model->sr_no); ?></span>
                </div>
                <div class="form-group mb_10">
                    <label class="inline_label">Name</label>:&nbsp;<span class=""><?php echo $customer->name; ?></span>
                </div>
                <div class="form-group mb_10">
                    <label class="inline_label">Mobile</label>:&nbsp;<span class=""><?php echo $customer->mobile; ?></span>
                </div>
                <div class="form-group mb_10">
                    <label class="inline_label">Address</label>:&nbsp;<span class=""><?php echo $customer->village . ', ' . $customer->thana . ', ' . $customer->district; ?></span>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 mpw_50 form-group">
                <h3 class="panel-title">Delivery Information</h3>
                <hr>
                <div class="form-group mb_10">
                    <label class="inline_label">Date</label>:&nbsp;<span class=""><?php echo date("j M Y", strtotime($model->created)); ?></span>
                </div>
                <div class="form-group mb_10">
                    <label class="inline_label">Delivery Sr</label>:&nbsp;<span class=""><?php echo $model->delivery_sr_no; ?></span>
                </div>
                <div class="form-group mb_10">
                    <label class="inline_label">Quantity Out</label>:&nbsp;<span class=""><?php echo $model->quantity; ?></span>
                </div>
                <div class="form-group mb_10">
                    <label class="inline_label">Lot No</label>:&nbsp;<span class=""><?php echo $model->lot_no; ?></span>
                </div>
                <div class="form-group mb_10">
                    <label class="inline_label">Loan Pack Paid</label>:&nbsp;<span class=""><?php echo $model->loan_pack; ?></span>
                </div>
                <div class="form-group mb_10">
                    <label class="inline_label">Previous Due</label>:&nbsp;
                    <span class=""><?php echo $sr_actual_due; ?></span>&nbsp;
                    <span>Tk</span>
                </div>
                <div class="form-group mb_10">
                    <label class="inline_label">Current Due</label>:&nbsp;
                    <span class=""><?php echo $sr_current_due; ?></span>&nbsp;
                    <span>Tk</span>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-md-12">
                <h3 class="panel-title">Payment Information</h3>
                <hr>
            </div>
            <form id="frmPayInfo" action="" method="post" class="dp_info">
                <input type="hidden" id="paymentID" name="paymentID" value="<?php echo $model->customer_payment->id; ?>">
                <input type="hidden" id="sr_current_due" name="sr_current_due" value="<?php echo $sr_current_due; ?>">
                <input type="hidden" id="actualDue" name="actualDue" value="<?php echo $sr_actual_due; ?>">
                <input type="hidden" id="mainInSr" name="mainInSr" value="<?php echo $model->sr_no; ?>">
                <div class="col-md-4 col-sm-4 mpw_33">
                    <div class="form-group mb_10">
                        <label class="inline_label">Loan Pack Paid</label>:&nbsp;
                        <span class="inv_show"><input type="number" oninput="multiply_value(this, '#loan_pack_total')" data-target="#loan_pack_cost" class="input_val" id="loan_pack" name="loan_pack" value="<?php echo $model->loan_pack; ?>" min="0" step="any"<?php AppHelper::is_readable($model->loan_pack); ?>></span>
                    </div>
                    <div class="form-group mb_10">
                        <label class="inline_label">Delivered Quantity</label>:&nbsp;
                        <span class="inv_show"><input type="number" class="input_val" id="delivered_qty" name="delivered_qty" value="<?php echo $model->quantity; ?>" min="0" step="any" readonly></span>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 mpw_33">
                    <div class="form-group mb_10">
                        <label class="inline_label">Loan Pack Cost</label>:&nbsp;
                        <span class="inv_show"><input type="number" oninput="multiply_value(this, '#loan_pack_total')" data-target="#loan_pack" class="input_val" id="loan_pack_cost" name="loan_pack_cost" value="<?php echo $model->customer_payment->loan_bag_cost; ?>" min="0" step="any"<?php AppHelper::is_readable($model->loan_pack); ?>></span>
                        <span>Tk</span>
                    </div>
                    <div class="form-group mb_10">
                        <label class="inline_label">Quantity Rent</label>:&nbsp;
                        <span class="inv_show"><input type="number" oninput="multiply_value(this, '#delivered_total')" data-target="#delivered_qty" class="input_val" id="qty_cost" name="qty_cost" value="<?php echo $model->customer_payment->delivered_cost; ?>" min="0" step="any" required="required"></span>
                        <span>Tk</span>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 mpw_33">
                    <div class="form-group mb_10">
                        <label class="inline_label"><-Total Amount</label>:&nbsp;
                        <span class="inv_show"><input type="number" class="camount" id="loan_pack_total" name="loan_pack_total" value="<?php echo $model->customer_payment->loan_bag_amount; ?>" min="0" step="any" readonly></span>
                        <span>Tk</span>
                    </div>
                    <div class="form-group mb_10">
                        <label class="inline_label"><-Total Amount</label>:&nbsp;
                        <span class="inv_show"><input type="number" class="camount" id="delivered_total" name="delivered_total" min="0" step="any" value="<?php echo $model->customer_payment->delivered_cost_amount; ?>" readonly></span>
                        <span>Tk</span>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row clearfix">
                        <div class="col-md-4 col-sm-4 mpw_33">
                            <div class="form-group mb_10">
                                <label class="inline_label">Due Paid</label>:&nbsp;
                                <span class="inv_show"><input type="number" class="camount" id="due_paid" name="due_paid" value="<?php echo $model->customer_payment->due_paid; ?>" min="0" max="<?php echo $sr_actual_due; ?>" step="any"></span>
                                <span>Tk</span>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 mpw_33">
                            <div class="form-group mb_10">
                                <label class="inline_label">Advance Paid</label>:&nbsp;
                                <span class="inv_show"><input type="number" class="input_val" id="adv_booking_cost" name="advance_amount" value="<?php echo $model->customer_payment->advance_paid; ?>" min="0" step="any" readonly></span>
                                <span>Tk</span>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 mpw_33">
                            <div class="form-group mb_10">
                                <label class="inline_label">Net Amount</label>:&nbsp;
                                <span class="inv_show"><input type="number" id="net_amount" name="net_amount" value="<?php echo $model->customer_payment->net_amount; ?>" readonly></span>
                                <span>Tk</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row clearfix">
                        <div class="col-md-4 col-sm-4 mpw_33">
                            <div class="form-group mb_10">
                                <label class="inline_label">Paid Amount</label>:&nbsp;
                                <span class="inv_show"><input type="number" id="paid_amount" name="paid_amount" value="<?php echo $model->customer_payment->paid_amount; ?>" min="0" step="any"></span>
                                <span>Tk</span>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 mpw_33">
                            <div class="form-group mb_10">
                                <label class="inline_label">Due Amount</label>:&nbsp;
                                <span class="inv_show"><input type="text" id="due_amount" name="due_amount" value="<?php echo $model->customer_payment->due_amount; ?>" min="0" step="any" readonly></span>
                                <span>Tk</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="row clearfix">
            <div class="col-md-4 form-group mpw_33" style="padding-top:50px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "Collected By"); ?></div>
            </div>
            <div class="col-md-4 form-group mpw_33" style="padding-top:50px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "Accountant"); ?></div>
            </div>
            <div class="col-md-4 form-group mpw_33" style="padding-top:50px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "Director"); ?></div>
            </div>
        </div>
    </div>
    <div class="clearfix">
        <div class="form-group text-center">
            <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_PRODUCT_OUT_VIEW, ['id' => $model->delivery_sr_no]); ?>"><i class="fa fa-print"></i>&nbsp;<?php echo Yii::t("strings", "Print View"); ?></a>
            <a class="btn btn-primary btn-xs" id="getPaidBtn" href="javascript:void(0);"><i class="fa fa-save"></i>&nbsp;<?php echo Yii::t("strings", "Update"); ?></a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#getPaidBtn", function(e) {
            if ($("#qty_cost").val() == "") {
                $("#ajaxMessage").showAjaxMessage({html: "Price must be set for each quantity", type: 'error'});
                $("#qty_cost").focus();
                $("#qty_cost").css('border-color', 'red');
                return false;
            } else if ($("#qty_cost").val() == 0) {
                $("#ajaxMessage").showAjaxMessage({html: "Price must be greater than 0", type: 'error'});
                $("#qty_cost").focus();
                $("#qty_cost").css('border-color', 'red');
                return false;
            } else {
                //showLoader("One Moment Please...", true);
                var _form = $("#frmPayInfo");
                var _url = ajaxUrl + '/product_out/update_payment';

                $.post(_url, _form.serialize(), function(resp) {
                    if (resp.success === true) {
                        $("#ajaxMessage").showAjaxMessage({html: resp.message, type: 'success'});
                        location.reload();
                    } else {
                        $("#ajaxMessage").showAjaxMessage({html: resp.message, type: 'error'});
                    }
                    showLoader("", false);
                }, "json");
            }
            e.preventDefault();
        });

        $(document).on("input", "#loan_pack", function(e) {
            var _curLoanPack = parseInt(document.getElementById('loan_pack_value').value);
            if ($(this).val() > _curLoanPack) {
                $("#ajaxMessage").showAjaxMessage({html: "Value is not acceptable more than " + _curLoanPack, type: 'error'});
                $(this).val(_curLoanPack);
                return false;
            } else {
                $("#ajaxMessage").html('').hide();
                return true;
            }
            e.preventDefault();
        });

        $(document).on("input", ".camount", function(e) {
            get_sum('camount', 'net_amount');
            update_total();
            e.preventDefault();
        });

        $(document).on("input", "#net_amount", function() {
            update_due();
        });

        $(document).on("input", "#paid_amount", function(e) {
            var _paidAmount = $(this).val();
            var _netAmount = parseInt($("#net_amount").val());
            if (_paidAmount < _netAmount) {
                $("#due_amount").val(_netAmount - _paidAmount);
            } else if (_paidAmount >= _netAmount) {
                $("#due_amount").val('');
            }
            e.preventDefault();
        });
    });

    function multiply_value(elm, target) {
        var value = !isNaN($(elm).val()) ? parseInt($(elm).val()) : 0;
        var target_elm = $(elm).attr("data-target");
        var num = !isNaN($(target_elm).val()) ? parseInt($(target_elm).val()) : 0;
        var _total = value * num;
        $(target).val(_total);
        update_total();
        update_due();
    }

    function update_total() {
        var loan_pack_total = parseInt(document.getElementById('loan_pack_total').value);
        var delivered_total = parseInt(document.getElementById('delivered_total').value);
        var cost_total = parseInt(document.getElementById('due_paid').value);
        var adv_total = parseInt(document.getElementById('adv_booking_cost').value);
        var lpt_val = !isNaN(loan_pack_total) ? parseInt(loan_pack_total) : 0;
        var dt_val = !isNaN(delivered_total) ? parseInt(delivered_total) : 0;
        var ct_val = !isNaN(cost_total) ? parseInt(cost_total) : 0;
        var at_val = !isNaN(adv_total) ? parseInt(adv_total) : 0;
        var _total = (lpt_val + dt_val + ct_val) - at_val;
        return $("#net_amount").val(_total);
    }

    function update_due() {
        var _due = '';
        var _paidAmount = parseInt($("#paid_amount").val());
        var _netAmount = parseInt($("#net_amount").val());
        if (_paidAmount < _netAmount) {
            _due = !isNaN(_netAmount - _paidAmount) ? parseInt(_netAmount - _paidAmount) : 0;
        } else if (_paidAmount >= _netAmount) {
            _due = '';
        }
        return $("#due_amount").val(_due);
    }
</script>