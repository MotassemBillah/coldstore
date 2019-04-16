<?php
$this->breadcrumbs = array(
    'Loan' => array(AppUrl::URL_LOAN),
    'Receive Form'
);
?>
<form id="frmDeliveryLoan" name="frmDeliveryLoan" action="" method="post">
    <input type="text" id="has_interest" value="">
    <input type="text" id="loan_period" value="">
    <input type="text" id="loan_rate" value="">
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
                <label for="delivery_no"><?php echo Yii::t("strings", "Delivery Number"); ?></label>
                <input type="number" id="delivery_no" name="delivery_no" class="form-control" min="0" value="<?php echo Delivery::model()->lastNumber(); ?>" required>
            </div>
        </div>
        <div class="col-md-3 col-sm-4">
            <div class="form-group">
                <label for="delivery_person"><?php echo Yii::t("strings", "Delivery Person"); ?></label>
                <input type="text" id="delivery_person" name="delivery_person" class="form-control">
            </div>
        </div>
        <div class="col-md-3 col-sm-4">
            <div class="form-group">
                <label for="fan_charge"><?php echo Yii::t("strings", "Fan Charge"); ?></label>
                <input type="number" id="fan_charge" name="fan_charge" class="form-control" min="0" step="any">
            </div>
        </div>
    </div>
    <div class="table-responsive no_mrgn">
        <table class="table table-striped table-bordered no_mrgn" id="tbl_delivery_and_loan_receive">
            <tr class="bg_gray">
                <th style="width:9%;"><?php echo Yii::t("strings", "Sr Number"); ?></th>
                <th style="width:7%;"><?php echo Yii::t("strings", "Qty"); ?></th>
                <th style="width:7%;"><?php echo Yii::t("strings", "Per Bag Loan"); ?></th>
                <th><?php echo Yii::t("strings", "Loan Amount"); ?></th>
                <th style="width:6%;"><?php echo Yii::t("strings", "Days"); ?></th>
                <th><?php echo Yii::t("strings", "Interest"); ?></th>
                <th><?php echo Yii::t("strings", "Loan Total"); ?></th>
                <th style="width:8%;"><?php echo Yii::t("strings", "Delivery SR"); ?></th>
                <th style="width:7%;"><?php echo Yii::t("strings", "Delivery Qty"); ?></th>
                <th><?php echo Yii::t("strings", "Rent"); ?></th>
                <th><?php echo Yii::t("strings", "Rent Total"); ?></th>
                <th><?php echo Yii::t("strings", "Fan Charge"); ?></th>
                <th><?php echo Yii::t("strings", "Total"); ?></th>
            </tr>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <tr id="tbl_row_<?php echo $i; ?>">
                    <td class="no_pad">
                        <input type="text" id="sr_no_<?php echo $i; ?>" name="LoanReceived[srno][]" class="form-control search_srno" data-info="<?php echo $i; ?>">
                    </td>
                    <td class="no_pad"><input type="number" id="quantity_<?php echo $i; ?>" name="LoanReceived[quantity][]" class="form-control qty" value="" data-info="<?php echo $i; ?>" min="0"></td>
                    <td class="no_pad"><input type="number" id="per_bag_loan_<?php echo $i; ?>" name="LoanReceived[per_bag_loan][]" class="form-control" value="" readonly></td>
                    <td class="no_pad"><input type="number" id="loan_amount_<?php echo $i; ?>" name="LoanReceived[amount][]" class="form-control amount" value="" readonly></td>
                    <td class="no_pad"><input type="number" id="day_<?php echo $i; ?>" name="LoanReceived[day][]" class="form-control" value="" readonly></td>
                    <td class="no_pad"><input type="number" id="interest_<?php echo $i; ?>" name="LoanReceived[interest][]" class="form-control interest" value="" readonly></td>
                    <td class="no_pad"><input type="number" id="loan_total_<?php echo $i; ?>" name="LoanReceived[total][]" class="form-control unit_total" value="" readonly></td>
                    <td class="no_pad"><input type="number" id="delivery_sr_<?php echo $i; ?>" name="Delivery[srno][]" class="form-control delivery_sr" value="" readonly></td>
                    <td class="no_pad"><input type="number" id="delivery_qty_<?php echo $i; ?>" name="Delivery[quantity][]" class="form-control delivery_qty" value="" data-info="<?php echo $i; ?>" min="0"></td>
                    <td class="no_pad"><input type="number" id="rent_<?php echo $i; ?>" name="Delivery[rent][]" class="form-control rent" value=""></td>
                    <td class="no_pad"><input type="number" id="rent_total_<?php echo $i; ?>" name="Delivery[total][]" class="form-control unit_total" value="" readonly></td>
                    <td class="no_pad"><input type="number" id="fan_charge_<?php echo $i; ?>" name="Delivery[fan_charge][]" class="form-control fan_charge" value="" readonly></td>
                    <td class="no_pad"><input type="number" id="total_<?php echo $i; ?>" name="total[]" class="form-control total" readonly></td>
                </tr>
            <?php endfor; ?>
        </table>
    </div>

    <div class="form-group text-right clearfix">
        <button id="remove_row" type="button" class="btn btn-warning btn-xs pull-right" disabled><i class="fa fa-minus"></i>&nbsp;Remove Row</button>
        <button id="add_new_row" type="button" class="btn btn-success btn-xs pull-right" onclick="add_form_row('tbl_delivery_and_loan_receive')"><i class="fa fa-plus"></i>&nbsp;Add Row</button>
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

        $(document).on("focusout", ".search_srno", function() {
            var _id = $(this).attr('data-info');
            var _fanc = $("#fan_charge").val();
            $("#delivery_sr_" + _id).val(this.value);
            var _url = ajaxUrl + '/misc/find_srloan_info';
            if (_fanc == '') {
                _fanc = 0;
            }

            showLoader("Fetching Data...", true);
            $.post(_url, {srno: $(this).val()}, function(resp) {
                if (resp.success === true) {
                    $("#ajaxMessage").hide();
                    $("#has_interest").val(resp.hasInterest);
                    $("#loan_period").val(resp.loan_period);
                    $("#loan_rate").val(resp.loan_rate);
                    $("#quantity_" + _id).val(resp.loan_qty);
                    $("#quantity_" + _id).attr('max', resp.loan_qty);
                    $("#per_bag_loan_" + _id).val(resp.cost);
                    $("#loan_amount_" + _id).val(resp.amount);
                    $("#day_" + _id).val(resp.day);
                    $("#interest_" + _id).val(resp.interest);
                    $("#loan_total_" + _id).val(resp.loan_total);
                    $("#delivery_qty_" + _id).val(resp.qty);
                    var dqty = $("#delivery_qty_" + _id).val();
                    $("#delivery_qty_" + _id).attr('max', resp.qty);
                    $("#rent_" + _id).val(resp.rent);
                    $("#rent_total_" + _id).val(resp.qty * resp.rent);
                    $("#fan_charge_" + _id).val(dqty * _fanc);
                    if (resp.loan_qty == '') {
                        clear_loan_data(_id);
                    }
                    if (resp.qty == '') {
                        clear_data(_id);
                        $("#ajaxMessage").showAjaxMessage({html: "Already Delivered", type: "error"});
                    }
                    $("#total_" + _id).val(sum_total(_id));
                } else {
                    $("#ajaxMessage").showAjaxMessage({html: resp.message, type: "error"});
                    clear_data(_id);
                }
                showLoader("", false);
            }, "json");
        });

        $(document).on("input", ".qty", function(e) {
            var _id = $(this).attr('data-info');
            var hasInterest = $("#has_interest").val();
            var _fanc = $("#fan_charge").val();
            if (_fanc == '') {
                _fanc = 0;
            }
            $("#delivery_qty_" + _id).val(this.value);
            $("#delivery_qty_" + _id).attr('max', this.value);
            var _per_bag_loan = $("#per_bag_loan_" + _id).val();
            $("#loan_amount_" + _id).val(this.value * _per_bag_loan);
            var _loan_amount = $("#loan_amount_" + _id).val();
            var _days = $("#day_" + _id).val();
            $("#interest_" + _id).val(getInterest(_loan_amount, _days, 360, 20, hasInterest));
            var _interest = $("#interest_" + _id).val();
            $("#loan_total_" + _id).val(parseInt(_loan_amount) + parseInt(_interest));
            var _rent = $("#rent_" + _id).val();
            $("#rent_total_" + _id).val(this.value * _rent);
            $("#fan_charge_" + _id).val(this.value * _fanc);
            $("#total_" + _id).val(sum_total(_id));
            e.preventDefault();
        });

        $(document).on("input", ".delivery_qty", function(e) {
            var _id = $(this).attr('data-info');
            var _loan_total = parseInt($("#loan_total_" + _id).val());
            if ('' == _loan_total || isNaN(_loan_total)) {
                _loan_total = 0;
            }
            var _fanc = $("#fan_charge").val();
            if (_fanc == '') {
                _fanc = 0;
            }
            $("#rent_total_" + _id).val(this.value * $("#rent_" + _id).val());
            var _rent_total = $("#rent_total_" + _id).val();
            $("#fan_charge_" + _id).val(this.value * _fanc);
            var _fanCharge = $("#fan_charge_" + _id).val();
            var _total = (parseInt(_loan_total) + parseInt(_rent_total) + parseInt(_fanCharge));
            $("#total_" + _id).val(_total);
            e.preventDefault();
        });

        $(document).on('submit', '#frmDeliveryLoan', function(e) {
            showLoader("Processing...", true);
            var _form = $(this);
            var _url = baseUrl + '/delivery/save_new';

            $.post(_url, _form.serialize(), function(resp) {
                if (resp.success === true) {
                    redirectTo(baseUrl + '/delivery');
                } else {
                    $("#ajaxMessage").showAjaxMessage({html: resp.message, type: 'error'});
                }
                showLoader("", false);
            }, "json");
            e.preventDefault();
            return false;
        });

        $(document).on("click", "#remove_row", function() {
            counter--;
            var _tblRow = $('#tbl_delivery_and_loan_receive tr:last');
            var _tblRowID = $(_tblRow).attr('id').split('tbl_row_')[1];

            if (_tblRowID > 5) {
                $("#tbl_row_" + _tblRowID).remove();
                enable("#remove_row");
            } else {
                disable("#remove_row");
                counter = 6;
            }
        });
    });

    function add_form_row(table) {
        var _html = "<tr id='tbl_row_" + counter + "'>";
        _html += "<td class='no_pad'><input type='text' id='sr_no_" + counter + "' name='LoanReceived[srno][]' class='form-control search_srno' data-info='" + counter + "'></td>";
        _html += "<td class='no_pad'><input type='number' id='quantity_" + counter + "' name='LoanReceived[quantity][]' class='form-control qty' value='' data-info='" + counter + "' min='0' max=''></td>";
        _html += "<td class='no_pad'><input type='number' id='per_bag_loan_" + counter + "' name='LoanReceived[per_bag_loan][]' class='form-control' value='' readonly></td>";
        _html += "<td class='no_pad'><input type='number' id='loan_amount_" + counter + "' name='LoanReceived[amount][]' class='form-control amount' value='' readonly></td>";
        _html += "<td class='no_pad'><input type='number' id='day_" + counter + "' name='LoanReceived[day][]' class='form-control' value='' readonly></td>";
        _html += "<td class='no_pad'><input type='number' id='interest_" + counter + "' name='LoanReceived[interest][]' class='form-control interest' value='' readonly></td>";
        _html += "<td class='no_pad'><input type='number' id='loan_total_" + counter + "' name='LoanReceived[total][]' class='form-control unit_total' value='' readonly></td>";
        _html += "<td class='no_pad'><input type='number' id='delivery_sr_" + counter + "' name='Delivery[srno][]' class='form-control delivery_sr' value='' readonly></td>";
        _html += "<td class='no_pad'><input type='number' id='delivery_qty_" + counter + "' name='Delivery[quantity][]' class='form-control delivery_qty' value='' data-info='" + counter + "' min='0' max=''></td>";
        _html += "<td class='no_pad'><input type='number' id='rent_" + counter + "' name='Delivery[rent][]' class='form-control rent' value=''></td>";
        _html += "<td class='no_pad'><input type='number' id='rent_total_" + counter + "' name='Delivery[total][]' class='form-control unit_total' value='' readonly></td>";
        _html += "<td class='no_pad'><input type='number' id='fan_charge_" + counter + "' name='Delivery[fan_charge][]' class='form-control fan_charge' value='' readonly></td>";
        _html += "<td class='no_pad'><input type='number' id='total_" + counter + "' name='total[]' class='form-control total' readonly></td>";
        _html += "</tr>";
        $("#" + table + " tr:last-child").after(_html);
        enable("#remove_row");
        counter++;
    }

    function getInterest(amount, day, period, percent, hasInterest) {
        var interest = 0;
        if (hasInterest == 'no') {
            percent = 0;
        }
        interest += parseFloat((percent * day * amount) / (100 * period)).toFixed(2);
        return Math.ceil(interest);
    }

    function clear_data(_id) {
        $("#sr_no_" + _id).val('');
        $("#quantity_" + _id).val('');
        $("#quantity_" + _id).attr('max', '');
        $("#per_bag_loan_" + _id).val('');
        $("#loan_amount_" + _id).val('');
        $("#day_" + _id).val('');
        $("#interest_" + _id).val('');
        $("#loan_total_" + _id).val('');
        $("#delivery_sr_" + _id).val('');
        $("#delivery_qty_" + _id).val('');
        $("#delivery_qty_" + _id).attr('max', '');
        $("#rent_" + _id).val('');
        $("#rent_total_" + _id).val('');
        $("#fan_charge_" + _id).val('');
        $("#total_" + _id).val('');
    }

    function clear_loan_data(_id) {
        //$("#sr_no_" + _id).val('');
        $("#quantity_" + _id).val('');
        $("#quantity_" + _id).attr('max', '');
        $("#per_bag_loan_" + _id).val('');
        $("#loan_amount_" + _id).val('');
        $("#day_" + _id).val('');
        $("#interest_" + _id).val('');
        $("#loan_total_" + _id).val('');
    }

    function sum_total(_id) {
        var _total = 0;
        var loan = $("#loan_total_" + _id).val();
        var rent = $("#rent_total_" + _id).val();
        var fanC = $("#fan_charge_" + _id).val();
        if (loan == '') {
            loan = 0;
        }
        if (rent == '') {
            rent = 0;
        }
        if (fanC == '') {
            fanC = 0;
        }
        _total += parseInt(parseInt(loan) + parseInt(rent) + parseInt(fanC));
        return _total;
    }
</script>