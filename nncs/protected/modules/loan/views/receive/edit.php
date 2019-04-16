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
                                <label for="loan_case_no">Loan No:</label>
                                <input type="number" id="loan_case_no" name="loan_case_no" class="form-control" value="<?php echo $model->case_no; ?>" min="0" step="any">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="pay_date"><?php echo Yii::t("strings", "Date"); ?></label>
                                <div class="input-group">
                                    <input type="text" id="pay_date" name="pay_date" class="form-control" value="<?php echo date("d-m-Y", strtotime($model->created)); ?>" required readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="advance">Advance</label>
                                <input type="number" id="advance" name="advance" class="form-control count_total" value="<?php echo $model->advance; ?>" min="0" step="any">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="cash">Cash</label>
                                <input type="number" id="cash" name="cash" class="form-control count_total" value="<?php echo $model->cash; ?>" min="0" step="any">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="loan_bag_price">L.B Price</label>
                                <input type="number" id="loan_bag_price" name="loan_bag_price_single" class="form-control" value="<?php echo $model->loan_bag_price; ?>" min="0" max="<?php echo $loan_setting->empty_bag_price; ?>" step="any">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="loan_bag_qty_total">Sum L.B</label>
                                <input type="number" id="loan_bag_qty_total" name="loan_bag_qty_total" class="form-control" value="<?php echo $model->loan_bag_qty; ?>" min="0" readonly>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="loan_bag_price_total">L.B Price Total</label>
                                <input type="number" id="loan_bag_price_total" name="loan_bag_price_total" class="form-control count_total" value="<?php echo $model->loan_bag_price_total; ?>" min="0" step="any" readonly>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="carrying_total">Carrying Total</label>
                                <input type="number" id="carrying_total" name="carrying_total" class="form-control count_total" value="<?php echo $model->carrying_total; ?>" min="0" step="any" readonly>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="qty_total">Sum Qty</label>
                                <input type="number" id="qty_total" name="qty_total" class="form-control" value="<?php echo $model->qty_total; ?>" min="0" step="any" readonly>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="total_loan_given">Total Amount</label>
                                <input type="number" id="total_loan_given" name="total_loan_given" class="form-control" value="<?php echo $model->total_loan_amount; ?>" min="0" step="any" readonly>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="cost_per_qty">Loan Per Qty</label>
                                <input type="number" id="cost_per_qty" name="cost_per_qty" class="form-control" value="<?php echo $model->qty_price; ?>" min="0" max="<?php echo $loan_setting->max_loan_per_qty; ?>" step="any">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="loan_taken_by">Loan Taken By</label>
                                <input type="text" id="loan_taken_by" name="loan_taken_by" value="<?php echo $model->taken_person; ?>" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <?php if (!empty($model->items) && count($model->items) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered reguler_tbl">
                                        <tr>
                                            <th class="text-center"><?php echo Yii::t("strings", "SL#"); ?></th>
                                            <th style="width:10%;"><?php echo Yii::t("strings", "Date"); ?></th>
                                            <th style="width:8%;"><?php echo Yii::t("strings", "Sr Number"); ?></th>
                                            <th style="width:15%;"><?php echo Yii::t("strings", "Customer"); ?></th>
                                            <th style="width:12%;"><?php echo Yii::t("strings", "Type"); ?></th>
                                            <th style="width:5%;"><?php echo Yii::t("strings", "Agent"); ?></th>
                                            <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "Loan Bag"); ?></th>
                                            <th class="text-center" style="width:7%;"><?php echo Yii::t("strings", "L.B Price"); ?></th>
                                            <th style="width:8%;"><?php echo Yii::t("strings", "Carrying Cost"); ?></th>
                                            <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "Qty"); ?></th>
                                            <th class="text-center"><?php echo Yii::t("strings", "Loan Per Qty"); ?></th>
                                            <th class="text-center"><?php echo Yii::t("strings", "Total"); ?></th>
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
                                                        <a class="item_remove" href="javascript:void()" title="Remove This Item?" data-info="<?php echo $item->id; ?>"><i class="fa fa-trash-o fa-2x color_red"></i></a>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="no_pad"><input type="text" id="date_<?php echo $item->id; ?>" name="date[<?php echo $item->id; ?>]" class="form-control dtpicker" value="<?php echo date('d-m-Y', strtotime($item->create_date)); ?>" readonly></td>
                                                <td class="no_pad"><input type="text" id="sr_no_<?php echo $item->id; ?>" name="sr_no[<?php echo $item->id; ?>]" class="form-control search_srno" value="<?php echo $item->sr_no; ?>" data-info="<?php echo $item->id; ?>"></td>
                                                <td class="">
                                                    <input type="hidden" id="customer_id_<?php echo $item->id; ?>" name="customer_id[<?php echo $item->id; ?>]" value="<?php echo $item->customer->id; ?>">
                                                    <span id="customer_name_<?php echo $item->id; ?>"><?php echo $item->customer->name; ?></span>
                                                </td>
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
                                                <td class="no_pad"><input type="number" id="loan_bag_<?php echo $item->id; ?>" name="loan_bag[<?php echo $item->id; ?>]" class="form-control loan_bag" value="<?php echo $item->loanbag; ?>" data-info="<?php echo $item->id; ?>" min="0"></td>
                                                <td class="no_pad"><input type="number" id="loan_bag_price_<?php echo $item->id; ?>" name="loan_bag_price[<?php echo $item->id; ?>]" class="form-control loan_bag_price" value="<?php echo $item->loanbag_cost; ?>" data-info="<?php echo $item->id; ?>" min="0" max="<?php echo $loan_setting->empty_bag_price; ?>" step="any"></td>
                                                <td class="no_pad"><input type="number" id="carrying_cost_<?php echo $item->id; ?>" name="carrying_cost[<?php echo $item->id; ?>]" class="form-control carrying_cost" value="<?php echo $item->carrying_cost; ?>" data-info="<?php echo $item->id; ?>" min="0" step="any" readonly></td>
                                                <td class="no_pad"><input type="number" id="quantity_<?php echo $item->id; ?>" name="quantity[<?php echo $item->id; ?>]" class="form-control text-center qty" value="<?php echo $item->qty; ?>" data-info="<?php echo $item->id; ?>"></td>
                                                <td class="no_pad"><input type="number" id="rent_<?php echo $item->id; ?>" name="rent[<?php echo $item->id; ?>]" class="form-control text-right rent_field" value="<?php echo $item->qty_cost; ?>" min="0" max="<?php echo $loan_setting->max_loan_per_qty; ?>" step="any" data-info="<?php echo $item->id; ?>"></td>
                                                <td class="no_pad"><input type="text" id="loan_amount_<?php echo $item->id; ?>" name="loan_amount[<?php echo $item->id; ?>]" class="form-control text-right unitprice" value="<?php echo AppHelper::getFloat($item->qty_cost_total); ?>" readonly></td>
                                            </tr>
                                            <?php
                                            $loanbagSum[] = $item->loanbag;
                                            $carryingSum[] = $item->carrying_cost;
                                            $qtySum[] = $item->qty;
                                            $loanTotalSum[] = $item->qty_cost_total;
                                        endforeach;
                                        ?>
                                        <tr class="bg_gray">
                                            <th class="text-right" colspan="6">Total</th>
                                            <th><?php echo array_sum($loanbagSum); ?></th>
                                            <th></th>
                                            <th><?php echo array_sum($carryingSum); ?></th>
                                            <th class="text-center"><?php echo array_sum($qtySum); ?></th>
                                            <th></th>
                                            <th class="no_pad"><input type="text" id="sumLoanAmount" class="form-control text-right" value="<?php echo!empty($model->total_loan_amount) ? AppHelper::getFloat($model->total_loan_amount) : AppHelper::getFloat(array_sum($loanTotalSum)); ?>" readonly></th>
                                        </tr>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">No Loan.</div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-12 text-center">
                            <input class="btn btn-primary" type="submit" name="updateLoan" value="Update">
                            <?php echo CHtml::button('Calculate', array('class' => 'btn btn-info', 'id' => 'calculate')); ?>
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

        $(document).on('change', '#pay_date', function(e) {
            $(".dtpicker").val(this.value);
            e.preventDefault();
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

        $(document).on("focusout", ".search_srno", function() {
            var _id = $(this).attr('data-info');
            var _url = ajaxUrl + '/misc/find_srinfo';

            if ($(this).val() == "") {
                showLoader("Empty value.", true);
                clear_data(_id);
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
                        $("#loan_amount_" + _id).val(parseFloat($("#rent_" + _id).val() * resp.qty).toFixed(2));
                        get_sum('unitprice', 'sumLoanAmount');
                        clicked = false;
                    } else {
                        $("#ajaxMessage").showAjaxMessage({html: resp.message, type: "error"});
                        clear_data(_id);
                    }
                    showLoader("", false);
                }, "json");
            }
        });

        $(document).on('click', '#calculate', function(e) {
            $("#qty_total").val(get_sum('qty', 'qty_total'));
            getTotal();
            var _qtyTotal = !isNaN($("#qty_total").val()) ? parseInt($('#qty_total').val()) : 1;
            var _totalLoan = $('#total_loan_given').val();
            var _qty_cost = (_totalLoan / _qtyTotal);

            $("#cost_per_qty").val(parseFloat(_qty_cost).toFixed(2));
            $("#cost_per_qty").trigger('input');
            $(".rent_field").trigger('input');
            $(".loan_bag_price").val($('#loan_bag_price').val());
            $("#sumLoanAmount").val(parseFloat($("#total_loan_given").val()).toFixed(2));
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

        $(document).on("input", ".qty, .rent_field", function() {
            var _id = $(this).attr('data-info');
            var _qty = $("#quantity_" + _id).val();
            var _price = $("#rent_" + _id).val();
            var _amount = (_qty * _price);
            $("#loan_amount_" + _id).val(parseFloat(_amount).toFixed(2));
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

    function getTotal() {
        get_sum('count_total', 'total_loan_given');
    }
</script>