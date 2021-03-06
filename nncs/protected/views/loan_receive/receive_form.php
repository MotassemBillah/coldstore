<?php
$this->breadcrumbs = array(
    'Loan' => array(AppUrl::URL_LOAN),
    'Receive Form'
);
?>
<form id="frmLoanReceive" name="frmLoanReceive" action="" method="post">
    <div class="row clearfix">
        <div class="col-md-2 col-sm-3">
            <div class="form-group">
                <label for="pay_date"><?php echo Yii::t("strings", "Date"); ?></label>
                <div class="input-group">
                    <input type="text" id="pay_date" name="pay_date" class="form-control" value="<?php echo date('d-m-Y'); ?>" required readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive no_mrgn">
        <table class="table table-striped table-bordered no_mrgn" id="loan_receive_tbl">
            <tr class="bg_gray">
                <th style="width:10%;"><?php echo Yii::t("strings", "Sr Number"); ?></th>
                <th style="width:10%;"><?php echo Yii::t("strings", "Qty"); ?></th>
                <th><?php echo Yii::t("strings", "Per Bag Loan"); ?></th>
                <th><?php echo Yii::t("strings", "Loan Amount"); ?></th>
                <th><?php echo Yii::t("strings", "Days"); ?></th>
                <th><?php echo Yii::t("strings", "Interest"); ?></th>
                <th><?php echo Yii::t("strings", "Total"); ?></th>
            </tr>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <tr id="tbl_row_<?php echo $i; ?>">
                    <td class="no_pad text-center" style="vertical-align: middle">
                        <input type="text" id="sr_no_<?php echo $i; ?>" name="sr_no[]" class="form-control search_srno" data-info="<?php echo $i; ?>">
                    </td>
                    <td class="no_pad"><input type="number" id="quantity_<?php echo $i; ?>" name="quantity[]" class="form-control qty" value="" readonly></td>
                    <td class="no_pad"><input type="number" id="per_bag_loan_<?php echo $i; ?>" name="per_bag_loan[]" class="form-control" value="" readonly></td>
                    <td class="no_pad"><input type="number" id="loan_amount_<?php echo $i; ?>" name="loan_amount[]" class="form-control amount" value="" readonly></td>
                    <td class="no_pad"><input type="number" id="day_<?php echo $i; ?>" name="day[]" class="form-control" value="" readonly></td>
                    <td class="no_pad"><input type="number" id="interest_<?php echo $i; ?>" name="interest[]" class="form-control interest" value="" readonly></td>
                    <td class="no_pad"><input type="number" id="total_<?php echo $i; ?>" name="total[]" class="form-control total" readonly></td>
                </tr>
            <?php endfor; ?>
        </table>
    </div>
    <div class="form-group text-right clearfix">
        <button id="remove_row" type="button" class="btn btn-warning btn-xs pull-right" disabled><i class="fa fa-minus"></i>&nbsp;Remove Row</button>
        <button id="add_new_row" type="button" class="btn btn-success btn-xs pull-right" onclick="addLoanReceiveFormRow('loan_receive_tbl')"><i class="fa fa-plus"></i>&nbsp;Add Row</button>
    </div>

    <div class="form-group text-center">
        <?php echo CHtml::submitButton('Save', array('class' => 'btn btn-primary', 'id' => 'btnSave')); ?>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $("#pay_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on("focusout", ".search_srno", function() {
            var _id = $(this).attr('data-info');
            var _url = ajaxUrl + '/misc/find_srloan_info';

            showLoader("Fetching Data...", true);
            $.post(_url, {srno: $(this).val()}, function(resp) {
                if (resp.success === true) {
                    $("#quantity_" + _id).val(resp.qty);
                    $("#per_bag_loan_" + _id).val(resp.cost);
                    $("#loan_amount_" + _id).val(resp.amount);
                    $("#day_" + _id).val(resp.day);
                    $("#interest_" + _id).val(resp.interest);
                    $("#total_" + _id).val(resp.total);
                } else {
                    $("#ajaxMessage").showAjaxMessage({html: resp.message, type: "error"});
                    clear_data(_id);
                }
                showLoader("", false);
            }, "json");
        });

        $(document).on("click", "#remove_row", function() {
            counter--;
            var _tblRow = $('#loan_receive_tbl tr:last');
            var _tblRowID = $(_tblRow).attr('id').split('tbl_row_')[1];

            if (_tblRowID > 5) {
                $("#tbl_row_" + _tblRowID).remove();
                enable("#remove_row");
            } else {
                disable("#remove_row");
                counter = 6;
            }
        });

        $(document).on('submit', '#frmLoanReceive', function(e) {
            showLoader("Processing...", true);
            var _form = $(this);
            var _url = ajaxUrl + '/loan/receive_save';

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

    function getTotal() {
        get_sum('count_total', 'total_loan_given');
    }

    function clear_data(_id) {
        $("#quantity_" + _id).val('');
        $("#per_bag_loan_" + _id).val('');
        $("#loan_amount_" + _id).val('');
        $("#day_" + _id).val('');
        $("#interest_" + _id).val('');
        $("#total_" + _id).val('');
    }
</script>