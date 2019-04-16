<?php
if (!empty($dataset) && count($dataset) > 0) :
    foreach ($dataset as $sdt) {
        $currentStock[] = AppObject::currentStock($sdt->sr_no);
    }
    ?>
    <div class="row clearfix">
        <div class="col-md-3 col-sm-3">
            <div class="form-group">
                <input type="hidden" id="curStock" name="curStock" value="<?php echo array_sum($currentStock); ?>">
                <input type="hidden" id="agent_code" name="agent_code" value="<?php echo!empty($dataset[0]->agent_code) ? $dataset[0]->agent_code : ''; ?>">
                <label class=""><?php echo Yii::t("strings", "Total Stock"); ?>: <?php echo array_sum($currentStock); ?></label>
                <div class="input-group">
                    <input type="text" id="pay_date" name="pay_date" class="form-control" value="<?php echo date('d-m-Y'); ?>" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-3">
            <div class="form-group">
                <label>Loan Per Quantity</label>
                <input type="number" id="cost_per_qty" name="cost_per_qty" class="form-control" oninput="multiply_value(this, '#total_loan_amount')" data-target="#curStock" min="0" max="<?php echo $loanSetting->max_loan_per_qty; ?>" step="any">
            </div>
        </div>
        <div class="col-md-3 col-sm-3">
            <div class="form-group">
                <label>Loan Paid Amount</label>
                <input type="number" id="total_loan_given" name="total_loan_given" class="form-control" data-target="#curStock" min="0" step="any" required>
            </div>
        </div>
        <div class="col-md-3 col-sm-3">
            <div class="form-group">
                <label>Loan Case Number</label>
                <input type="number" id="loan_case_no" name="loan_case_no" class="form-control" value="<?php echo $loanCaseNumber; ?>" min="0" step="any">
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr class="bg_gray">
                <th class="text-center"><?php echo Yii::t("strings", "SL#"); ?></th>
                <th style="width:15%;"><?php echo Yii::t("strings", "Customer"); ?></th>
                <th><?php echo Yii::t("strings", "Sr Number"); ?></th>
                <th style="width:15%;"><?php echo Yii::t("strings", "Type"); ?></th>
                <th><?php echo Yii::t("strings", "Loan Taken Qty"); ?></th>
                <th><?php echo Yii::t("strings", "Applied Quantity"); ?></th>
                <th><?php echo Yii::t("strings", "Amount Per Qty"); ?></th>
                <th><?php echo Yii::t("strings", "Loan Amount"); ?></th>
            </tr>
            <?php
            $counter = 0;
            foreach ($dataset as $data):
                $counter++;
                $product_type = !empty($data->type) ? ProductType::model()->findByPk($data->type)->name : '';
                $stock = AppObject::currentStock($data->sr_no);
                $taken = AppObject::loanTakenQty($data->sr_no);
                $restQty = $stock - $taken;
                if ($restQty > 0):
                    ?>
                    <tr>
                        <td class="no_pad text-center" style="vertical-align: middle">
                            <input type="checkbox" id="inp_<?php echo $data->id; ?>" name="data[]" value="<?php echo $data->id; ?>" style="height:20px;width:20px;">
                            <input type="hidden" id="cur_stock_<?php echo $data->id; ?>" name="cur_stock[<?php echo $data->id; ?>]" value="<?php echo AppObject::currentStock($data->sr_no); ?>">
                            <input type="hidden" id="agent_<?php echo $data->id; ?>" name="agent[<?php echo $data->id; ?>]" value="<?php echo $data->agent_code; ?>">
                        </td>
                        <td class="">
                            <input type="hidden" name="customer_id[<?php echo $data->id; ?>]" value="<?php echo $data->customer->id; ?>">
                            <?php echo $data->customer->name; ?>
                        </td>
                        <td class="no_pad"><input type="text" id="sr_no_<?php echo $data->id; ?>" name="sr_no[<?php echo $data->id; ?>]" class="form-control" value="<?php echo $data->sr_no; ?>" readonly></td>
                        <td class=""><?php echo $product_type; ?><input type="hidden" id="type_<?php echo $data->id; ?>" name="type[<?php echo $data->id; ?>]" class="form-control" value="<?php echo!empty($data->type) ? $data->type : ''; ?>" readonly></td>
                        <td class="no_pad"><input type="text" id="taken_qty_<?php echo $data->id; ?>" name="taken_qty[<?php echo $data->id; ?>]" class="form-control" value="<?php echo AppObject::loanTakenQty($data->sr_no); ?>" readonly></td>
                        <td class="no_pad"><input type="number" id="quantity_<?php echo $data->id; ?>" name="quantity[<?php echo $data->id; ?>]" data-info="<?php echo $restQty . '/' . $data->sr_no; ?>" class="form-control qty" value="<?php echo!empty($restQty) ? $restQty : ''; ?>" min="0" max="<?php echo $restQty; ?>" step="any" oninput="multiply_value(this, '#loan_amount_<?php echo $data->id; ?>')" data-target="#rent_<?php echo $data->id; ?>"<?php echo empty($restQty) ? 'readonly' : ''; ?>></td>
                        <td class="no_pad"><input type="text" id="rent_<?php echo $data->id; ?>" name="rent[<?php echo $data->id; ?>]" class="form-control rent_field" oninput="multiply_value(this, '#loan_amount_<?php echo $data->id; ?>')" data-target="#quantity_<?php echo $data->id; ?>" readonly></td>
                        <td class="no_pad"><input type="text" id="loan_amount_<?php echo $data->id; ?>" name="loan_amount[<?php echo $data->id; ?>]" class="form-control unitprice" readonly></td>
                    </tr>
                    <?php
                else:
                    echo "<tr><td colspan='8'>SR <b>{$data->sr_no}</b> is already in loan.</td></tr>";
                endif;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th colspan="7" class="text-right">Total Loan Amount</th>
                <th class="no_pad">
                    <input type="text" id="total_loan_amount" name="total_loan_amount" class="form-control" min="0" step="any" readonly>
                </th>
            </tr>
        </table>
    </div>

    <div class="text-center">
        <?php echo CHtml::submitButton('Save', array('class' => 'btn btn-primary')); ?>
    </div>
<?php else: ?>
    No data found! Please search again.
<?php endif; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#pay_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        var _sumQty = 0;
        $(document).on("input", "#total_loan_given", function() {
            $("#total_loan_amount").val(this.value);
        });

        $(document).on("change", "input[type='checkbox']", function() {
            $(".rent_field").val('');
            var _total_loan_amount = parseInt($("#total_loan_given").val());
            var _num = this.value;
            var _qty = 0;
            var _cost_per_qty = '';

            if ($("#total_loan_given").val() != '') {
                $("#total_loan_given").removeAttr('style');
                if (this.checked) {
                    _qty += $("#quantity_" + _num).val();
                    _sumQty += +_qty;
                    _cost_per_qty = parseFloat(_total_loan_amount / _sumQty).toFixed(2);
                } else {
                    _qty += -($("#quantity_" + _num).val());
                    _sumQty += +_qty;
                    _cost_per_qty = parseFloat(_total_loan_amount / _sumQty).toFixed(2);
                    $("#rent_" + _num).val('');
                    $("#loan_amount_" + _num).val('');
                }

                var _chkboxs = $("#frmCustomerLoan input[type='checkbox']:checked");
                for (var i = 0; i < _chkboxs.length; i++) {
                    var _numVal = _chkboxs[i].value;
                    var _inpQty = $("#quantity_" + _numVal).val();
                    $("#rent_" + _numVal).val(_cost_per_qty);
                    $("#loan_amount_" + _numVal).val(parseFloat(_cost_per_qty * _inpQty).toFixed(2));
                }
            } else {
                $(this).prop("checked", false);
                $("#total_loan_given").focus();
                $("#total_loan_given").css('border-color', 'red');
                $("#ajaxMessage").showAjaxMessage({html: "Please set value for Loan Paid Amount", type: "warning"});
                console.log("Please set value for Loan Paid Amount");
            }

            $("#cost_per_qty").val(_cost_per_qty);
            //get_sum('unitprice', 'total_loan_amount');
        });
    });

    function set_value(element, val) {
        return $(element).val(!isNaN(val) ? val : 0);
    }
</script>