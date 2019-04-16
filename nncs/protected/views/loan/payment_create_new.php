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
                        <label for="loan_case_no">Case Number</label>
                        <input type="number" id="loan_case_no" name="loan_case_no" class="form-control" value="<?php echo $loanCaseNumber; ?>" min="0" step="any">
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="pay_date"><?php echo Yii::t("strings", "Date"); ?></label>
                        <div class="input-group">
                            <input type="text" id="pay_date" name="pay_date" class="form-control" value="<?php echo date('d-m-Y'); ?>" readonly>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="cost_per_qty">Per Qty Loan</label>
                        <input type="number" id="cost_per_qty" name="cost_per_qty" class="form-control" min="0" max="<?php echo $loanSetting->max_loan_per_qty; ?>" step="any" value="<?php echo $loanSetting->max_loan_per_qty; ?>" required>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="form-group">
                        <label for="total_qty">Total Quantity</label>
                        <input type="number" id="total_qty" name="total_qty" class="form-control" min="0" step="any" readonly>
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
                        <label for="loan_taken_by">Loan Taken Person</label>
                        <input type="text" id="loan_taken_by" name="loan_taken_by" class="form-control">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered  no_mrgn" id="loan_list_tbl">
                    <tr class="bg_gray">
                        <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                        <th style="width:10%;"><?php echo Yii::t("strings", "Sr Number"); ?></th>
                        <th style=""><?php echo Yii::t("strings", "Customer"); ?></th>
                        <th style=""><?php echo Yii::t("strings", "Type"); ?></th>
                        <th style=""><?php echo Yii::t("strings", "Agent"); ?></th>
                        <th style="width:10%;"><?php echo Yii::t("strings", "Quantity"); ?></th>
                        <th style="width:10%;"><?php echo Yii::t("strings", "Loan Per Qty"); ?></th>
                        <th style="width:15%;"><?php echo Yii::t("strings", "Total"); ?></th>
                    </tr>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <tr id="tbl_row_<?php echo $i; ?>">
                            <td class="text-center"><?php echo $i; ?></td>
                            <td class="no_pad">
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
                            <td class="no_pad"><input type="number" id="quantity_<?php echo $i; ?>" name="quantity[]" class="form-control qty" min="0" max="" step="any" data-info="<?php echo $i; ?>" value=""></td>
                            <td class="no_pad"><input type="number" id="rent_<?php echo $i; ?>" name="rent[]" class="form-control rent_field" min="0" max="<?php echo $loanSetting->max_loan_per_qty; ?>" step="any" data-info="<?php echo $i; ?>" value=""></td>
                            <td class="no_pad"><input type="text" id="loan_amount_<?php echo $i; ?>" name="loan_amount[]" class="form-control unitprice" value="" readonly></td>
                        </tr>
                    <?php endfor; ?>
                </table>
            </div>

            <div class="text-right clearfix">
                <button id="remove_row" type="button" class="btn btn-warning btn-xs pull-right" disabled><i class="fa fa-minus"></i>&nbsp;Remove Row</button>
                <button id="add_new_row" type="button" class="btn btn-success btn-xs pull-right"><i class="fa fa-plus"></i>&nbsp;Add Row</button>
                <label for="increase_row">How Many Row : <input type="number" id="increase_row" min="5" value="" style="width:18%"></label>
            </div>

            <div class="text-center">
                <?php echo CHtml::submitButton('Save', array('class' => 'btn btn-primary', 'id' => 'save_loan')); ?>
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

        $(document).on("focusout", ".search_srno", function() {
            var _id = $(this).attr('data-info');
            var _url = ajaxUrl + '/misc/find_srinfo';
            var _qty_cost = parseInt($('#cost_per_qty').val());

            showLoader("Fetching Data...", true);
            $.post(_url, {srno: $(this).val(), isNew: 'yes'}, function(resp) {
                if (resp.success === true) {
                    $("#customer_id_" + _id).val(resp.cid);
                    $("#customer_name_" + _id).html(resp.customer);
                    $("#type_" + _id).val(resp.tid);
                    $("#type_name_" + _id).html(resp.type);
                    $("#agent_" + _id).val(resp.aid);
                    $("#agent_name_" + _id).html(resp.agent);
                    $("#quantity_" + _id).val(resp.qty);
                    $("#quantity_" + _id).attr('max', resp.qty);
                    if (_qty_cost == '') {
                        _qty_cost = resp.loan_per_qty;
                    }
                    $("#rent_" + _id).val(_qty_cost);
                    $("#loan_amount_" + _id).val(resp.qty * _qty_cost);
                    getTotal();
                    enable("#save_loan");
                } else {
                    $("#ajaxMessage").showAjaxMessage({html: resp.message, type: "error"});
                    clear_data(_id);
                    getTotal();
                    disable("#save_loan");
                }
                showLoader("", false);
            }, "json");
        });

        $(document).on('input', '#cost_per_qty', function(e) {
            $(".rent_field").val(this.value);
            $(".rent_field").trigger('input');
            e.preventDefault();
        });

        $(document).on("input", ".qty", function() {
            var _id = $(this).attr('data-info');
            var _qty_cost = parseInt($('#rent_' + _id).val());
            if (isNaN(_qty_cost)) {
                _qty_cost = 1;
            }
            $("#loan_amount_" + _id).val(_qty_cost * $(this).val());
            getTotal();
        });

        $(document).on("input", ".rent_field", function() {
            var _id = $(this).attr('data-info');
            var _qty = parseInt($('#quantity_' + _id).val());
            if (isNaN(_qty)) {
                _qty = 1;
            }
            $("#loan_amount_" + _id).val(_qty * $(this).val());
            getTotal();
        });

        $(document).on("click", "#add_new_row", function() {
            var _num = $('#increase_row').val();
            var maxVal = document.getElementById('cost_per_qty').max;

            if (_num == '') {
                addField('loan_list_tbl');
            } else {
                var _html = '';
                for (var i = counter; i <= _num; i++) {
                    _html += '<tr id="tbl_row_' + i + '"><td class="text-center">' + i + '</td><td class="no_pad"><input type="text" id="sr_no_' + i + '" name="sr_no[]" class="form-control search_srno" data-info="' + i + '"></td><td><input type="hidden" id="customer_id_' + i + '" name="customer_id[]" value=""><span id="customer_name_' + i + '"></span></td><td><input type="hidden" id="type_' + i + '" name="type[]" value=""><span id="type_name_' + i + '"></span></td><td><input type="hidden" id="agent_' + i + '" name="agent[]" value=""><span id="agent_name_' + i + '"></span></td><td class="no_pad"><input type="number" id="quantity_' + i + '" name="quantity[]" class="form-control qty" min="0" max="" step="any" data-info="' + i + '" value=""></td><td class="no_pad"><input type="number" id="rent_' + i + '" name="rent[]" class="form-control rent_field" min="0" max="' + maxVal + '" step="any" data-info="' + i + '" value=""></td><td class="no_pad"><input type="text" id="loan_amount_' + i + '" name="loan_amount[]" class="form-control unitprice" value="" readonly=""></td></tr>';
                }
                $("#loan_list_tbl").last().append(_html);
            }
            enable("#remove_row");
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
            document.getElementById('increase_row').value = '';
        });

        $(document).on('submit', '#frmCustomerLoan', function(e) {
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
            e.preventDefault();
            return false;
        });
    });
    function clear_data(_id) {
        $("#sr_no_" + _id).val('');
        $("#customer_id_" + _id).val('');
        $("#customer_name_" + _id).html('');
        $("#type_" + _id).val('');
        $("#type_name_" + _id).html('');
        $("#agent_" + _id).val('');
        $("#agent_name_" + _id).html('');
        $("#quantity_" + _id).val('');
        $("#quantity_" + _id).attr('max', '');
        $("#rent_" + _id).val('');
        $("#loan_amount_" + _id).val('');
    }

    function getTotal() {
        get_sum('unitprice', 'total_loan_given');
        get_sum('qty', 'total_qty');
    }
</script>