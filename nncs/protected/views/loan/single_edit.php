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
						<div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label for="pay_date"><?php echo Yii::t("strings", "Date"); ?></label>
                                <div class="input-group">
                                    <input type="text" id="pay_date" name="pay_date" class="form-control" value="<?php echo date('d-m-Y', strtotime($model->created)); ?>" required readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
						
                        <div class="col-md-12 col-sm-12">
                            <table class="table table-striped table-bordered reguler_tbl">
                                <tr>
                                    <th style="width:8%;"><?php echo Yii::t("strings", "Sr Number"); ?></th>
                                    <th style="width:18%;"><?php echo Yii::t("strings", "Customer"); ?></th>
                                    <th style="width:15%;"><?php echo Yii::t("strings", "Type"); ?></th>
                                    <th style="width:6%;"><?php echo Yii::t("strings", "Agent"); ?></th>
                                    <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "Loan Bag"); ?></th>
                                    <th class="text-center" style="width:7%;"><?php echo Yii::t("strings", "L.B Price"); ?></th>
                                    <th style="width:9%;"><?php echo Yii::t("strings", "Carrying Cost"); ?></th>
                                    <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "Qty"); ?></th>
                                    <th class="text-right"><?php echo Yii::t("strings", "Loan Per Qty"); ?></th>
                                    <th class="text-right"><?php echo Yii::t("strings", "Total"); ?></th>
                                </tr>
                                <tr>
                                    <td class="no_pad">
                                        <input type="text" id="sr_no_<?php echo $model->id; ?>" name="sr_no" class="form-control search_srno" value="<?php echo $model->sr_no; ?>" data-info="<?php echo $model->id; ?>">
                                    </td>
                                    <td class="">
                                        <input type="hidden" id="customer_id_<?php echo $model->id; ?>" name="customer_id" value="<?php echo $model->customer->id; ?>">
                                        <span id="customer_name_<?php echo $model->id; ?>"><?php echo $model->customer->name; ?></span>
                                    </td>
                                    <td class="no_pad">
                                        <?php $proTypeList = ProductType::model()->getList(); ?>
                                        <select class="form-control" name="type" id="type_<?php echo $model->id; ?>">
                                            <option value="">Select</option>
                                            <?php
                                            foreach ($proTypeList as $_ptype):
                                                if ($_ptype->id == $model->type)
                                                    $_sel = ' selected';
                                                else
                                                    $_sel = '';
                                                ?>
                                                <option value="<?php echo $_ptype->id; ?>" <?php echo $_sel; ?>><?php echo $_ptype->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="no_pad"><input type="number" id="agent_<?php echo $model->id; ?>" name="agent" class="form-control" value="<?php echo $model->agent_code; ?>" readonly></td>
                                    <td class="no_pad"><input type="number" id="loan_bag_<?php echo $model->id; ?>" name="loan_bag" class="form-control loan_bag" value="<?php echo $model->loanbag; ?>" data-info="<?php echo $model->id; ?>" min="0"></td>
                                    <td class="no_pad"><input type="number" id="loan_bag_price_<?php echo $model->id; ?>" name="loan_bag_price" class="form-control loan_bag_price" value="<?php echo $model->loanbag_cost; ?>" data-info="<?php echo $model->id; ?>" min="0" max="<?php echo $loanSetting->empty_bag_price; ?>" step="any"></td>
                                    <td class="no_pad"><input type="number" id="carrying_cost_<?php echo $model->id; ?>" name="carrying_cost" class="form-control carrying_cost" value="<?php echo $model->carrying_cost; ?>" data-info="<?php echo $model->id; ?>" min="0" step="any" readonly></td>
                                    <td class="no_pad"><input type="number" id="quantity_<?php echo $model->id; ?>" name="quantity" class="form-control text-center qty" value="<?php echo $model->qty; ?>" data-info="<?php echo $model->id; ?>" readonly></td>
                                    <td class="no_pad"><input type="number" id="rent_<?php echo $model->id; ?>" name="rent" class="form-control text-right rent_field" value="<?php echo $model->qty_cost; ?>" min="0" max="<?php echo $loanSetting->max_loan_per_qty; ?>" step="any" data-info="<?php echo $model->id; ?>"></td>
                                    <td class="no_pad"><input type="text" id="loan_amount_<?php echo $model->id; ?>" name="loan_amount" class="form-control text-right unitprice" value="<?php echo AppHelper::getFloat($model->qty_cost_total); ?>" readonly></td>
                                </tr>
                            </table>
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
		
		$(document).on("input", ".search_srno", function() {
            var _id = $(this).attr('data-info');
            var _url = ajaxUrl + '/misc/find_srinfo';

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
                    $(".qty").trigger('input');
                } else {
                    $("#ajaxMessage").showAjaxMessage({html: resp.message, type: "error"});
                    clear_data(_id);
                }
            }, "json");
        });
		
        $(document).on("input", ".qty, .rent_field", function() {
            var _id = $(this).attr('data-info');
            var _qty = $("#quantity_" + _id).val();
            var _price = $("#rent_" + _id).val();
            var _amount = (_qty * _price);
            $("#loan_amount_" + _id).val(parseFloat(_amount).toFixed(2));
        });
    });

    function clear_data(_id) {
        $("#customer_id_" + _id).val('');
        $("#customer_name_" + _id).html('');
        $("#type_" + _id).val('');
        $("#type_name_" + _id).html('');
        $("#agent_" + _id).val('');
        $("#agent_name_" + _id).html('');
        $("#loan_bag_" + _id).val('');
        $("#carrying_cost_" + _id).val('');
        $("#quantity_" + _id).val('');
        $("#quantity_" + _id).attr('max', '');
    }
</script>