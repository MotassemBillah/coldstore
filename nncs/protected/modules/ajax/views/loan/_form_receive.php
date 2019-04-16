<div class="modal-dialog" role="document" style="max-width: 900px">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Close"><span aria-hidden="true">x</span></button>
            <h3 class="modal-title"><?php echo Yii::t("strings", "Get Loan Payment From "); ?> : <u><?php echo $dataset[0]->customer->name; ?></u></h3>
            <div id="ajaxModalMessage" class="alert" style="display: none"></div>
        </div>
        <form action="" id="frmLoanReceive" method="post">
            <input type="hidden" name="customer_id" value="<?php echo $dataset[0]->customer->id; ?>">
            <div class="modal-body" style="max-height:440px;overflow-y:auto;">
                <div class="clearfix">
                    <?php if (!empty($dataset) && count($dataset) > 0) : ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed">
                                <tr class="bg_gray">
                                    <th><?php echo Yii::t('strings', 'Sr No'); ?></th>
                                    <th><?php echo Yii::t('strings', 'Quantity'); ?></th>
                                    <th class="text-center" style="width: 10%"><?php echo Yii::t('strings', 'Per Qty Loan'); ?></th>
                                    <th class="text-center" style="width: 10%"><?php echo Yii::t('strings', 'Amount'); ?></th>
                                    <th class="text-center" style="width: 10%;"><?php echo Yii::t('strings', 'Days'); ?></th>
                                    <th class="text-center"><?php echo Yii::t('strings', 'Per Day Interest'); ?></th>
                                    <th class="text-center"><?php echo Yii::t('strings', 'Total Interest'); ?></th>
                                    <th class="text-center"><?php echo Yii::t('strings', 'Total Loan Amount'); ?></th>
                                </tr>
                                <?php
                                foreach ($dataset as $data):
                                    $loan_days = AppHelper::get_day_diff($data->create_date, date('Y-m-d'));
                                    $intAmount = ceil($loan_days * $data->per_day_interest);
                                    ?>
                                    <tr>
                                        <td class="no_pad">
                                            <input type="hidden" name="data[]" value="<?php echo $data->id; ?>">
                                            <input type="text" name="srno[<?php echo $data->id; ?>]" class="form-control" value="<?php echo $data->sr_no; ?>" readonly>
                                            <input type="hidden" name="loan_taken[<?php echo $data->id; ?>]" value="<?php echo AppObject::loanTakenQty($data->sr_no); ?>">
                                            <input type="hidden" name="loan_paid[<?php echo $data->id; ?>]" value="<?php echo AppObject::loanPaidQty($data->sr_no); ?>">
                                            <input type="hidden" name="lp_id[<?php echo $data->id; ?>]" value="<?php echo $data->lp_id; ?>">
                                        </td>
                                        <td class="no_pad">
                                            <input type="hidden" name="max_qty[<?php echo $data->id; ?>]" value="<?php echo $data->quantity; ?>">
                                            <input type="number" data-info="<?php echo $data->id; ?>" name="qty[<?php echo $data->id; ?>]" oninput="multiply_value(this, '#amount_<?php echo $data->id; ?>')" data-target="#rent_<?php echo $data->id; ?>" id="qty_<?php echo $data->id; ?>" class="form-control" value="<?php echo $data->quantity; ?>" min="0" max="<?php echo $data->quantity; ?>" step="any">
                                        </td>
                                        <td class="text-center" style="width: 10%">
                                            <input type="hidden" id="rent_<?php echo $data->id; ?>" name="qty_cost[<?php echo $data->id; ?>]" value="<?php echo $data->cost_per_qty; ?>">
                                            <?php echo $data->cost_per_qty; ?>
                                        </td>
                                        <td class="no_pad" data-info="<?php echo $data->id; ?>" style="width: 10%">
                                            <input type="text" id="amount_<?php echo $data->id; ?>" name="amount[<?php echo $data->id; ?>]" class="form-control amount" value="<?php echo $data->loan_amount; ?>" readonly>
                                        </td>
                                        <td class="no_pad" style="width: 10%;">
                                            <input type="text" id="loan_days_<?php echo $data->id; ?>" name="days[<?php echo $data->id; ?>]" class="form-control loan_days" value="<?php echo $loan_days; ?>" readonly>
                                        </td>
                                        <td class="no_pad">
                                            <input type="text" id="day_interest_<?php echo $data->id; ?>" name="day_interest[<?php echo $data->id; ?>]" class="form-control" value="<?php echo AppHelper::getFloat($data->per_day_interest); ?>" readonly>
                                        </td>
                                        <td class="no_pad">
                                            <input type="text" id="interest_<?php echo $data->id; ?>" name="interest_amount[<?php echo $data->id; ?>]" class="form-control interest" value="<?php echo $intAmount; ?>" readonly>
                                        </td>
                                        <td class="no_pad">
                                            <input type="text" id="loan_amount_<?php echo $data->id; ?>" name="loan_amount[<?php echo $data->id; ?>]" class="form-control la_sum" value="<?php echo ($data->loan_amount + $intAmount); ?>" readonly>
                                        </td>
                                    </tr>
                                    <?php
                                    $sum_loan_amount[] = $data->loan_amount;
                                    $sum_interest_amount[] = $intAmount;
                                    $sum_total_amount[] = $data->loan_amount + $intAmount;
                                endforeach;
                                ?>
                                <tr class="bg_gray">
                                    <th colspan="3"><?php echo Yii::t('strings', 'Total'); ?></th>
                                    <th class="no_pad" style="width: 10%;">
                                        <input type="text" id="loan_amount" name="sum_loan_amount" class="form-control lsum" value="<?php echo array_sum($sum_loan_amount); ?>" readonly>
                                    </th>
                                    <th></th>
                                    <th class="no_pad"></th>
                                    <th class="no_pad">
                                        <input type="text" id="total_interest_amount" name="sum_interest_amount" class="form-control" value="<?php echo array_sum($sum_interest_amount); ?>" readonly>
                                    </th>
                                    <th class="no_pad">
                                        <input type="text" id="total_loan_amount" name="total_loan_amount" class="form-control" value="<?php echo array_sum($sum_total_amount); ?>" readonly>
                                    </th>
                                </tr>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">No records found!</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button type="button" class="btn btn-info" data-dismiss="modal" aria-label="Close" title="Close"><?php echo Yii::t("strings", "Cancel"); ?></button>
                <button type="button" class="btn btn-primary" id="processPayment"><?php echo Yii::t("strings", "Save"); ?></button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("input", ".amount", function(e) {
            var _id = $(this).closest('td').attr('data-info');
            console.log(_id);
            e.preventDefault();
        });
    });

    function multiply_value(elm, target) {
        var id = $(elm).attr('data-info');
        var value = !isNaN($(elm).val()) ? parseInt($(elm).val()) : 0;
        var target_elm = $(elm).attr("data-target");
        var num = !isNaN($(target_elm).val()) ? parseInt($(target_elm).val()) : 0;
        var _total = value * num;
        $(target).val(_total);
        var _interest = parseInt($("#interest_" + id).val());
        var sum = add(_total, _interest);
        $("#loan_amount_" + id).val(sum);
        get_sum('amount', 'loan_amount');
        get_sum('interest', 'total_interest_amount');
        get_sum('la_sum', 'total_loan_amount');
    }
</script>