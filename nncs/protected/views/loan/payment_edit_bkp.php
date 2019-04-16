<?php
$this->breadcrumbs = array(
    'Loan' => array(AppUrl::URL_LOAN),
    'Payment Update'
);
?>
<div class="content-panel">
    <form id="frmCustomerLoan" action="" method="post">
        <div class="clearfix">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Loan Payment Information :: Update</h3>
                </div>
                <div class="panel-body">
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <label for="pay_date"><?php echo Yii::t("strings", "Date"); ?></label>
                                <div class="input-group">
                                    <input type="text" id="pay_date" name="pay_date" class="form-control" value="<?php echo date("d-m-Y", strtotime($model->created)); ?>" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <label for="loan_case_no">Loan Number</label>
                                <input type="number" id="loan_case_no" name="loan_case_no" class="form-control" value="<?php echo $model->case_no; ?>" min="0" step="any">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <label for="loan_taken_by">Loan Taken By</label>
                                <input type="text" id="loan_taken_by" name="loan_taken_by" class="form-control" value="<?php echo $model->taken_person; ?>">
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <?php if (!empty($model->items) && count($model->items) > 0): ?>
                                <table class="table table-striped table-bordered reguler_tbl">
                                    <tr>
                                        <th class="text-center"><?php echo Yii::t("strings", "SL#"); ?></th>
                                        <th style="width:15%;"><?php echo Yii::t("strings", "Customer"); ?></th>
                                        <th><?php echo Yii::t("strings", "Sr Number"); ?></th>
                                        <th style="width:15%;"><?php echo Yii::t("strings", "Type"); ?></th>
                                        <th style="width:17%;"><?php echo Yii::t("strings", "Agent"); ?></th>
                                        <th><?php echo Yii::t("strings", "Quantity"); ?></th>
                                        <th><?php echo Yii::t("strings", "Amount Per Qty"); ?></th>
                                        <th><?php echo Yii::t("strings", "Loan Amount"); ?></th>
                                    </tr>
                                    <?php
                                    $counter = 0;
                                    foreach ($model->items as $item) :
                                        $counter++;
                                        ?>
                                        <tr id="row_<?php echo $item->id; ?>">
                                            <td class="no_pad text-center" style="vertical-align: middle">
                                                <input type="hidden" id="inp_<?php echo $item->id; ?>" name="data_key[]" value="<?php echo $item->id; ?>">
                                                <a class="item_remove" href="javascript:void()" title="Remove This Item?" data-info="<?php echo $item->id; ?>"><i class="fa fa-trash-o fa-2x color_red"></i></a>
                                            </td>
                                            <td class="">
                                                <input type="hidden" name="customer_id[<?php echo $item->id; ?>]" value="<?php echo $item->customer->id; ?>">
                                                <?php echo $item->customer->name; ?>
                                            </td>
                                            <td class="no_pad"><input type="text" id="sr_no_<?php echo $item->id; ?>" name="sr_no[<?php echo $item->id; ?>]" class="form-control" value="<?php echo $item->sr_no; ?>" readonly></td>
                                            <td class="no_pad">
                                                <?php $proTypeList = ProductType::model()->getList(); ?>
                                                <select class="form-control" name="type[<?php echo $item->id; ?>]" id="type_<?php echo $item->id; ?>">
                                                    <option value="">Select</option>
                                                    <?php
                                                    foreach ($proTypeList as $_ptype):
                                                        if ($_ptype->id == $item->type)
                                                            $_sel = ' selected';
                                                        else
                                                            $_sel = '';
                                                        ?>
                                                        <option value="<?php echo $_ptype->id; ?>" <?php echo $_sel; ?>><?php echo $_ptype->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td class="no_pad"><input type="number" id="agent_<?php echo $item->id; ?>" name="agent[<?php echo $item->id; ?>]" class="form-control" value="<?php echo $item->agent_code; ?>"></td>
                                            <td class="no_pad"><input type="number" id="quantity_<?php echo $item->id; ?>" name="quantity[<?php echo $item->id; ?>]" class="form-control qty" value="<?php echo $item->qty; ?>" data-info="<?php echo $item->id; ?>"></td>
                                            <td class="no_pad"><input type="number" id="rent_<?php echo $item->id; ?>" name="rent[<?php echo $item->id; ?>]" class="form-control rent_field" value="<?php echo $item->qty_cost; ?>" min="0" max="<?php echo $loan_setting->max_loan_per_qty; ?>" step="any" data-info="<?php echo $item->id; ?>"></td>
                                            <td class="no_pad"><input type="text" id="loan_amount_<?php echo $item->id; ?>" name="loan_amount[<?php echo $item->id; ?>]" class="form-control unitprice" value="<?php echo $item->qty_cost_total; ?>" readonly></td>
                                        </tr>
                                        <?php
                                        $qtySum[] = $item->qty;
                                        $loanTotalSum[] = $item->qty_cost_total;
                                    endforeach;
                                    ?>
                                    <tr class="bg_gray">
                                        <th class="text-right" colspan="5">Total</th>
                                        <th class=""><?php echo array_sum($qtySum); ?></th>
                                        <th></th>
                                        <th class=""><?php echo array_sum($loanTotalSum); ?></th>
                                    </tr>
                                </table>
                            <?php else: ?>
                                <div class="alert alert-warning">No Loan.</div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-12 text-center">
                            <input class="btn btn-primary" type="submit" name="updateLoan" value="Update">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#pay_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on("input", ".qty, .rent_field", function() {
            var _id = $(this).attr('data-info');
            var _qty = $("#quantity_" + _id).val();
            var _price = $("#rent_" + _id).val();
            var _amount = (_qty * _price);
            $("#loan_amount_" + _id).val(_amount);
        });

        $(document).on("click", ".item_remove", function(e) {
            var _rc = confirm('Are you sure about this action? This cannot be undone!');

            if (_rc === true) {
                showLoader("Processing...", true);
                var _id = $(this).attr('data-info');
                var _url = ajaxUrl + '/loan/remove_item?id=' + _id;

                $.get(_url, function(res) {
                    if (res.success === true) {
                        $("#ajaxMessage").showAjaxMessage({html: res.message, type: 'success'});
                        $("tr#row_" + _id).remove();
                        location.reload();
                    } else {
                        $("#ajaxMessage").showAjaxMessage({html: res.message, type: 'error'});
                    }
                    reset_index();
                    showLoader("", false);
                }, "json");
            } else {
                return false;
            }
            e.preventDefault();
        });
    });
</script>