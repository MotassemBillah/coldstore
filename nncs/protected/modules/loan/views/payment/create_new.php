<?php
$this->breadcrumbs = array(
    'Loan' => array(AppUrl::URL_LOAN),
    'Payment Create'
);
?>
<div class="content-panel">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'frmCustomerLoan',
        'enableClientValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Loan Payment Information</h3>
        </div>
        <div class="panel-body">
            <div class="row clearfix">
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="loan_case_no">Loan No:</label>
                        <input type="number" id="loan_case_no" name="loan_case_no" class="form-control" value="<?php echo $loanCaseNumber; ?>" min="0" step="any">
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="pay_date"><?php echo Yii::t("strings", "Date"); ?></label>
                        <div class="input-group">
                            <input type="text" id="pay_date" name="pay_date" class="form-control" value="<?php echo date('d-m-Y'); ?>" required readonly>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="advance">Advance</label>
                        <input type="number" id="advance" name="advance" class="form-control count_total" min="0" step="any">
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="cash">Cash</label>
                        <input type="number" id="cash" name="cash" class="form-control count_total" min="0" step="any">
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="loan_bag_price">L.B Price</label>
                        <input type="number" id="loan_bag_price" name="loan_bag_price_single" class="form-control" min="0" max="<?php echo $loanSetting->empty_bag_price; ?>" step="any">
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="loan_bag_qty_total">Sum L.B</label>
                        <input type="number" id="loan_bag_qty_total" name="loan_bag_qty_total" class="form-control" min="0" readonly>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="loan_bag_price_total">L.B Price Total</label>
                        <input type="number" id="loan_bag_price_total" name="loan_bag_price_total" class="form-control count_total" min="0" step="any" readonly>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="carrying_total">Carrying Total</label>
                        <input type="number" id="carrying_total" name="carrying_total" class="form-control count_total" min="0" step="any" readonly>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="qty_total">Sum Qty</label>
                        <input type="number" id="qty_total" name="qty_total" class="form-control" min="0" step="any" readonly>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="total_loan_given">Total Amount</label>
                        <input type="number" id="total_loan_given" name="total_loan_given" class="form-control" min="0" step="any" readonly>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="cost_per_qty">Loan Per Qty</label>
                        <input type="number" id="cost_per_qty" name="cost_per_qty" class="form-control" min="0" max="<?php echo $loanSetting->max_loan_per_qty; ?>" step="any">
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="loan_taken_by">Loan Taken By</label>
                        <input type="text" id="loan_taken_by" name="loan_taken_by" class="form-control">
                    </div>
                </div>
            </div>

            <div class="table-responsive no_mrgn">
                <table class="table table-striped table-bordered no_mrgn" id="loan_list_tbl">
                    <tr class="bg_gray">
                        <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                        <th style="width:8%;"><?php echo Yii::t("strings", "Sr Number"); ?></th>
                        <th style="width:15%;"><?php echo Yii::t("strings", "Customer"); ?></th>
                        <th style="width:12%;"><?php echo Yii::t("strings", "Type"); ?></th>
                        <th style="width:15%;"><?php echo Yii::t("strings", "Agent"); ?></th>
                        <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "Loan Bag"); ?></th>
                        <th class="text-center" style="width:7%;"><?php echo Yii::t("strings", "L.B Price"); ?></th>
                        <th style="width:9%;"><?php echo Yii::t("strings", "Carrying Cost"); ?></th>
                        <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "Qty"); ?></th>
                        <th><?php echo Yii::t("strings", "Loan Per Qty"); ?></th>
                        <th><?php echo Yii::t("strings", "Total"); ?></th>
                    </tr>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <tr id="tbl_row_<?php echo $i; ?>">
                            <td class="text-center"><?php echo $i; ?></td>
                            <td class="no_pad text-center" style="vertical-align: middle">
                                <input type="text" id="sr_no_<?php echo $i; ?>" name="sr_no[]" class="form-control search_srno" data-info="<?php echo $i; ?>">
                            </td>
                            <td>
                                <input type="hidden" id="customer_id_<?php echo $i; ?>" name="customer_id[]" value="">
                                <span id="customer_name_<?php echo $i; ?>"></span>
                            </td>
                            <td>
                                <input type="hidden" id="type_<?php echo $i; ?>" name="type[]" value="">
                                <span id="type_name_<?php echo $i; ?>"></span>
                            </td>
                            <td>
                                <input type="hidden" id="agent_<?php echo $i; ?>" name="agent[]" value="">
                                <span id="agent_name_<?php echo $i; ?>"></span>
                            </td>
                            <td class="no_pad"><input type="number" id="loan_bag_<?php echo $i; ?>" name="loan_bag[]" class="form-control loan_bag" data-info="<?php echo $i; ?>" min="0" value=""></td>
                            <td class="no_pad"><input type="number" id="loan_bag_price_<?php echo $i; ?>" name="loan_bag_price[]" class="form-control loan_bag_price" data-info="<?php echo $i; ?>" min="0" max="<?php echo $loanSetting->empty_bag_price; ?>" step="any" value=""></td>
                            <td class="no_pad"><input type="number" id="carrying_cost_<?php echo $i; ?>" name="carrying_cost[]" class="form-control carrying_cost" data-info="<?php echo $i; ?>" min="0" step="any" value=""></td>
                            <td class="no_pad"><input type="number" id="quantity_<?php echo $i; ?>" name="quantity[]" class="form-control qty" min="0" max="" step="any" data-info="<?php echo $i; ?>" value=""></td>
                            <td class="no_pad"><input type="number" id="rent_<?php echo $i; ?>" name="rent[]" class="form-control rent_field" min="0" max="<?php echo $loanSetting->max_loan_per_qty; ?>" step="any" data-info="<?php echo $i; ?>" value=""></td>
                            <td class="no_pad"><input type="text" id="loan_amount_<?php echo $i; ?>" name="loan_amount[]" class="form-control unitprice" value="" readonly></td>
                        </tr>
                    <?php endfor; ?>
                </table>
            </div>
            <div class="form-group text-right clearfix">
                <button id="remove_row" type="button" class="btn btn-warning btn-xs pull-right" disabled><i class="fa fa-minus"></i>&nbsp;Remove Row</button>
                <button id="add_new_row" type="button" class="btn btn-success btn-xs pull-right" onclick="addField('loan_list_tbl')"><i class="fa fa-plus"></i>&nbsp;Add Row</button>
            </div>

            <div class="text-center">
                <?php echo CHtml::submitButton('Save', array('class' => 'btn btn-primary', 'id' => 'save_loan', 'disabled' => 'disabled')); ?>
                <?php echo CHtml::button('Calculate', array('class' => 'btn btn-info', 'id' => 'calculate')); ?>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#pay_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on('input', '#advance, #cash, #loan_bag_price_total, #carrying_total', function(e) {
            var _loan_bag_price = parseInt($('#loan_bag_price').val());
            if (isNaN(_loan_bag_price)) {
                _loan_bag_price = 0;
            }
            var sumOfLoanBag = get_sum('loan_bag', 'loan_bag_qty_total');
            $("#loan_bag_price_total").val(_loan_bag_price * sumOfLoanBag);
            getTotal();
            clicked = false;
            e.preventDefault();
        });

        $(document).on('click', '#calculate', function(e) {
            clicked = true;
            var _qtyTotal = !isNaN($("#qty_total").val()) ? parseInt($('#qty_total').val()) : 1;
            var _totalLoan = $('#total_loan_given').val();
            var _qty_cost = (_totalLoan / _qtyTotal);

            $("#cost_per_qty").val(parseFloat(_qty_cost).toFixed(2));
            $("#cost_per_qty").trigger('input');
            $(".rent_field").trigger('input');
            $(".loan_bag_price").val($('#loan_bag_price').val());

            if (clicked === true) {
                enable("#save_loan");
            } else {
                disable("#save_loan");
            }
            e.preventDefault();
        });

        $(document).on('input', '#loan_bag_price', function(e) {
            $(".loan_bag_price").val($(this).val());
            var sumOfLoanBag = get_sum('loan_bag', 'loan_bag_qty_total');
            $("#loan_bag_price_total").val($(this).val() * sumOfLoanBag);
            getTotal();
            e.preventDefault();
        });

        $(document).on('input', '#cost_per_qty', function(e) {
            $(".rent_field").val($(this).val());
            e.preventDefault();
        });

        $(document).on('input', '#qty_total', function(e) {
            $("#calculate").trigger('click');
            clicked = false;
            e.preventDefault();
        });

        $(document).on("focusout", ".search_srno", function() {
            var _id = $(this).attr('data-info');
            var _url = ajaxUrl + '/misc/find_srinfo';
            var _loan_bag_price = parseInt($('#loan_bag_price').val());
            var _qty_cost = parseInt($('#cost_per_qty').val());

            if (isNaN(_loan_bag_price)) {
                _loan_bag_price = 0;
            }

            if ($(this).val() == "") {
                showLoader("Empty value.", true);
                clear_data(_id);
                getTotal();
                setTimeout(function() {
                    showLoader("", false);
                }, 1000);
            } else {
                showLoader("Fetching Data...", true);
                $.post(_url, {srno: $(this).val()}, function(resp) {
                    if (resp.success === true) {
                        $("#customer_id_" + _id).val(resp.cid);
                        $("#customer_name_" + _id).html(resp.customer);
                        $("#type_" + _id).val(resp.tid);
                        $("#type_name_" + _id).html(resp.type);
                        $("#agent_" + _id).val(resp.aid);
                        $("#agent_name_" + _id).html(resp.agent);
                        $("#loan_bag_" + _id).val(resp.loanBag);
                        $("#carrying_cost_" + _id).val(resp.ccost);
                        $("#quantity_" + _id).val(resp.qty);
                        $("#quantity_" + _id).attr('max', resp.qty);

                        if (!isNaN(_qty_cost)) {
                            $("#loan_amount_" + _id).val(parseFloat(_qty_cost * resp.qty).toFixed(2));
                        } else {
                            $("#loan_amount_" + _id).val('');
                        }

                        var sumOfLoanBag = get_sum('loan_bag', 'loan_bag_qty_total');
                        $("#loan_bag_price_total").val(_loan_bag_price * sumOfLoanBag);
                        get_sum('carrying_cost', 'carrying_total');
                        get_sum('qty', 'qty_total');
                        getTotal();
                        //$("#calculate").trigger('click');
                        clicked = false;
                    } else {
                        $("#ajaxMessage").showAjaxMessage({html: resp.message, type: "error"});
                        clear_data(_id);
                    }
                    showLoader("", false);
                }, "json");
            }
        });

        $(document).on("input", ".qty", function() {
            var _id = $(this).attr('data-info');
            var _qty_cost = parseInt($('#rent_' + _id).val());

            if (!isNaN(_qty_cost)) {
                $("#loan_amount_" + _id).val(parseFloat(_qty_cost * $(this).val()).toFixed(2));
            } else {
                $("#loan_amount_" + _id).val('');
            }
        });

        $(document).on("input", ".rent_field", function() {
            var _id = $(this).attr('data-info');
            var _qty = parseInt($('#quantity_' + _id).val());

            if (!isNaN(_qty)) {
                $("#loan_amount_" + _id).val(parseFloat(_qty * $(this).val()).toFixed(2));
            } else {
                $("#loan_amount_" + _id).val('');
            }
        });

        $(document).on("click", "#remove_row", function() {
            counter--;
            var _tblRow = $('#loan_list_tbl tr:last');
            var _tblRowID = $(_tblRow).attr('id').split('tbl_row_')[1];

            if (_tblRowID > 5) {
                $("#tbl_row_" + _tblRowID).remove();
                enable("#remove_row");
            } else {
                disable("#remove_row");
                counter = 6;
            }
        });

        $(document).on('submit', '#frmCustomerLoan', function(e) {
            if (clicked === false) {
                $("#ajaxMessage").showAjaxMessage({html: "Please calculate the result first.", type: 'warning'});
            } else {
                showLoader("Processing...", true);
                var _form = $(this);
                var _url = ajaxUrl + '/loan/create_new';
                var _gotoUrl = baseUrl + '/loan/payment';

                $.post(_url, _form.serialize(), function(resp) {
                    if (resp.success === true) {
                        $("#ajaxMessage").showAjaxMessage({html: resp.message, type: 'success'});
                        redirectTo(_gotoUrl);
                    } else {
                        $("#ajaxMessage").showAjaxMessage({html: resp.message, type: 'error'});
                    }
                    showLoader("", false);
                }, "json");
            }

            e.preventDefault();
            return false;
        });
    });

    function getTotal() {
        get_sum('count_total', 'total_loan_given');
    }

    function clear_data(_id) {
        var _loan_bag_price = $("#loan_bag_price").val();
        $("#customer_id_" + _id).val('');
        $("#customer_name_" + _id).html('');
        $("#type_" + _id).val('');
        $("#type_name_" + _id).html('');
        $("#agent_" + _id).val('');
        $("#agent_name_" + _id).html('');
        $("#loan_bag_" + _id).val('');
        $("#loan_bag_price_" + _id).val('');
        $("#carrying_cost_" + _id).val('');
        $("#quantity_" + _id).val('');
        $("#quantity_" + _id).attr('max', '');
        $("#rent_" + _id).val('');
        $("#loan_amount_" + _id).val('');
        var sumOfLoanBag = get_sum('loan_bag', 'loan_bag_qty_total');
        $("#loan_bag_price_total").val(_loan_bag_price * sumOfLoanBag);
        get_sum('carrying_cost', 'carrying_total');
        get_sum('qty', 'qty_total');
        getTotal();
    }
</script>