<?php
$this->breadcrumbs = array(
    'Loan' => array(AppUrl::URL_LOAN),
    'Payment Update'
);
?>
<div class="content-panel">
    <form id="frmCustomerLoan" action="" method="post">
        <input type="hidden" id="loan_id" name="loan_id" value="<?php echo $model->id; ?>">
        <div class="clearfix">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Loan Payment Information :: Update
                        <button id="add_new_row" type="button" class="btn btn-success btn-xs pull-right"><i class="fa fa-plus"></i>&nbsp;Add A Item</button>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row clearfix">
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="loan_case_no">Case Number</label>
                                <input type="number" id="loan_case_no" name="loan_case_no" class="form-control" value="<?php echo $model->case_no; ?>" min="0" step="any">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="pay_date"><?php echo Yii::t("strings", "Date"); ?></label>
                                <div class="input-group">
                                    <input type="text" id="pay_date" name="pay_date" class="form-control" value="<?php echo date("d-m-Y", strtotime($model->created)); ?>" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="cost_per_qty">Loan Per Qty</label>
                                <input type="number" id="cost_per_qty" name="cost_per_qty" class="form-control" max="<?php echo $loanSetting->max_loan_per_qty; ?>" value="<?php echo $model->items[0]->qty_cost; ?>">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="total_loan_amount">Total Loan Amount</label>
                                <input type="number" id="total_loan_amount" name="total_loan_amount" class="form-control" value="<?php echo intval($model->sumAmount); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label for="loan_taken_by">Loan Taken Person</label>
                                <input type="text" id="loan_taken_by" name="loan_taken_by" class="form-control" value="<?php echo $model->taken_person; ?>">
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <?php if (!empty($model->items) && count($model->items) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered reguler_tbl">
                                        <tr>
                                            <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                                            <th style="width:10%;"><?php echo Yii::t("strings", "Date"); ?></th>
                                            <th style="width:10%;"><?php echo Yii::t("strings", "Sr Number"); ?></th>
                                            <th><?php echo Yii::t("strings", "Customer"); ?></th>
                                            <th><?php echo Yii::t("strings", "Type"); ?></th>
                                            <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "Agent"); ?></th>
                                            <th class="text-center" style="width:8%;"><?php echo Yii::t("strings", "Quantity"); ?></th>
                                            <th style="width:10%;"><?php echo Yii::t("strings", "Loan Per Qty"); ?></th>
                                            <th style="width:12%;"><?php echo Yii::t("strings", "Total"); ?></th>
                                        </tr>
                                        <?php
                                        $counter = 0;
                                        foreach ($model->items as $item) :
                                            $counter++;
                                            ?>
                                            <tr id="row_<?php echo $item->id; ?>">
                                                <td class="no_pad text-center" style="vertical-align: middle">
                                                    <input type="hidden" id="inp_<?php echo $item->id; ?>" name="data_key[]" value="<?php echo $item->id; ?>">
                                                    <?php if (count($model->items) > 1) : ?>
                                                        <a class="item_remove" href="javascript:void()" title="Remove This Item?" data-info="<?php echo $item->id; ?>"><i class="fa fa-trash-o color_red" style="font-size:18px;"></i></a>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="no_pad"><input type="text" id="date_<?php echo $item->id; ?>" name="date[<?php echo $item->id; ?>]" class="form-control dtpicker" value="<?php echo date('d-m-Y', strtotime($item->create_date)); ?>" readonly></td>
                                                <td class="no_pad"><input type="text" id="sr_no_<?php echo $item->id; ?>" name="sr_no[<?php echo $item->id; ?>]" class="form-control search_srno" value="<?php echo $item->sr_no; ?>" data-info="<?php echo $item->id; ?>"></td>
                                                <td><span id="customer_name_<?php echo $item->id; ?>"><?php echo $item->customer->name; ?></span></td>
                                                <td><span id="type_name_<?php echo $item->id; ?>"><?php echo!empty($item->type) ? ProductType::model()->findByPk($item->type)->name : ''; ?></span></td>
                                                <td class="text-center"><span id="agent_<?php echo $item->id; ?>"><?php echo!empty($item->agent_code) ? $item->agent_code : 0; ?></span></td>
                                                <td class="no_pad"><input type="number" id="quantity_<?php echo $item->id; ?>" name="quantity[<?php echo $item->id; ?>]" class="form-control text-center qty" value="<?php echo $item->qty; ?>" data-info="<?php echo $item->id; ?>"></td>
                                                <td class="no_pad"><input type="number" id="rent_<?php echo $item->id; ?>" name="rent[<?php echo $item->id; ?>]" class="form-control text-right rent_field" value="<?php echo $item->qty_cost; ?>" min="0" max="<?php echo $loanSetting->max_loan_per_qty; ?>" step="any" data-info="<?php echo $item->id; ?>"></td>
                                                <td class="no_pad"><input type="text" id="loan_amount_<?php echo $item->id; ?>" name="loan_amount[<?php echo $item->id; ?>]" class="form-control text-right unitprice" value="<?php echo $item->qty_cost_total; ?>" readonly></td>
                                            </tr>
                                            <?php
                                            $qtySum[] = $item->qty;
                                            $loanTotalSum[] = $item->qty_cost_total;
                                        endforeach;
                                        ?>
                                        <tr class="bg_gray">
                                            <th class="text-right" colspan="6">Total</th>
                                            <th class="text-center"><?php echo array_sum($qtySum); ?></th>
                                            <th></th>
                                            <th class="no_pad"><input type="text" id="sumLoanAmount" class="form-control text-right" value="<?php echo array_sum($loanTotalSum); ?>" readonly></th>
                                        </tr>
                                    </table>
                                </div>
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
<div class="modal fade" id="containerForItemAdd" tabindex="-1" role="dialog" aria-labelledby="containerForItemAddLabel"></div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#pay_date, .dtpicker").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on('change', '#pay_date', function(e) {
            $(".dtpicker").val(this.value);
            e.preventDefault();
        });

        $(document).on("focusout", ".search_srno", function() {
            var _id = $(this).attr('data-info');
            var _url = ajaxUrl + '/misc/find_srinfo';

            showLoader("Fetching Data...", true);
            $.post(_url, {srno: $(this).val(), isNew: 'no'}, function(resp) {
                if (resp.success === true) {
                    $("#customer_name_" + _id).html(resp.customer);
                    $("#type_name_" + _id).html(resp.type);
                    $("#agent_" + _id).html(resp.agent);
                    $("#quantity_" + _id).val(resp.qty);
                    $("#quantity_" + _id).attr('max', resp.qty);
                    $(".qty").trigger('input');
                    getTotal();
                } else {
                    $("#ajaxMessage").showAjaxMessage({html: resp.message, type: "error"});
                    clear_data(_id);
                }
                showLoader("", false);
            }, "json");
        });

        $(document).on('input', '#cost_per_qty', function(e) {
            $(".rent_field").val($(this).val());
            $(".rent_field").trigger('input');
            e.preventDefault();
        });

        $(document).on("input", ".qty", function() {
            var _id = $(this).attr('data-info');
            var _qty_cost = parseInt($('#rent_' + _id).val());

            if (!isNaN(_qty_cost)) {
                $("#loan_amount_" + _id).val(parseInt(_qty_cost * $(this).val()));
            } else {
                $("#loan_amount_" + _id).val('');
            }
            getTotal();
        });

        $(document).on("input", ".rent_field", function() {
            var _id = $(this).attr('data-info');
            var _qty = parseInt($('#quantity_' + _id).val());

            if (!isNaN(_qty)) {
                $("#loan_amount_" + _id).val(parseInt(_qty * $(this).val()));
            } else {
                $("#loan_amount_" + _id).val('');
            }
            getTotal();
        });

        $(document).on("click", "#add_new_row", function(e) {
            showLoader("Processing...", true);
            var _id = $("#loan_id").val();
            var _url = ajaxUrl + '/loan/add_item_form?loan_id=' + _id;

            $("#containerForItemAdd").load(_url, function() {
                $("#containerForItemAdd").modal({
                    backdrop: 'static',
                    keyboard: false
                });
                showLoader("", false);
            });
            e.preventDefault();
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

    function clear_data(_id) {
        $("#sr_no_" + _id).val('');
        $("#customer_name_" + _id).html('');
        $("#type_name_" + _id).html('');
        $("#agent_" + _id).html('');
        $("#quantity_" + _id).val('');
        $("#quantity_" + _id).attr('max', '');
        $("#rent_" + _id).val('');
        $("#loan_amount_" + _id).val('');
    }

    function getTotal() {
        get_sum('unitprice', 'total_loan_amount');
        get_sum('unitprice', 'sumLoanAmount');
    }
</script>