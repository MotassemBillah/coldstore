<?php
$this->breadcrumbs = array(
    'Product In' => array(AppUrl::URL_PRODUCT_IN),
    'Invoice'
);
?>
<div class="content-panel">
    <div id="printDiv">
        <div class="row form-group clearfix text-center txt_left_xs mp_center media_print">
            <?php if (!empty($this->settings->title)): ?>
                <h1 style="font-size: 30px;margin: 0;"><?php echo $this->settings->title; ?></h1>
            <?php endif; ?>
            <?php if (!empty($this->settings->author_address)): ?>
                <?php echo $this->settings->author_address; ?><br>
            <?php endif; ?>
            <h4 class="inv_title"><u><?php echo Yii::t("strings", "Entry Invoice"); ?></u></h4>
        </div>
        <hr>

        <div class="row clearfix">
            <div class="col-md-6 col-sm-6 mpw_50 form-group">
                <h3 class="panel-title">Customer Information</h3>
                <hr>
                <?php if (!empty($model->agent_code)) : ?>
                    <div class="form-group mb_10">
                        <label class="inline_label">Agent Code</label>:&nbsp;<span class=""><?php echo $model->agent_code; ?></span>
                    </div>
                <?php endif; ?>
                <div class="form-group mb_10">
                    <label class="inline_label">Date</label>:&nbsp;<span class=""><?php echo date("j M Y", strtotime($model->create_date)); ?></span>
                </div>
                <div class="form-group mb_10">
                    <label class="inline_label">S.R Number</label>:&nbsp;<span class=""><?php echo $model->sr_no; ?></span>
                </div>
                <div class="form-group mb_10">
                    <label class="inline_label">Name</label>:&nbsp;<span class=""><?php echo $customer->name; ?></span>
                </div>
                <div class="form-group mb_10">
                    <label class="inline_label">Mobile</label>:&nbsp;<span class=""><?php echo $customer->mobile; ?></span>
                </div>
                <div class="form-group mb_10">
                    <label class="inline_label">Address</label>:&nbsp;<span class=""><?php echo $customer->village . ', ' . $customer->thana . ', ' . $customer->district; ?></span>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 mpw_50 form-group">
                <h3 class="panel-title">Payment Information</h3>
                <hr>
                <form id="frmPayInfo" action="" method="post">
                    <input type="hidden" id="paymentID" name="paymentID" value="<?php echo $model->payment->id; ?>">
                    <div class="form-group mb_10">
                        <label class="inline_label">Quantity In</label>:&nbsp;<span class=""><?php echo AppObject::stockIn($model->sr_no); ?></span>
                    </div>
                    <div class="form-group mb_10">
                        <label class="inline_label">Lot No</label>:&nbsp;<span class=""><?php echo $model->lot_no; ?></span>
                    </div>
                    <div class="form-group mb_10">
                        <label class="inline_label">Loan Pack</label>:&nbsp;<span class=""><?php echo $model->loan_pack; ?></span>
                    </div>
                    <div class="form-group mb_10">
                        <label class="inline_label">Advance</label>:&nbsp;
                        <span class="inv_show"><input type="number" oninput="update_total()" class="input_val" id="advance_amount" name="advance_amount" value="<?php echo $model->payment->advance_amount; ?>" min="0" step="any"></span>
                        <span>Tk</span>
                    </div>
                    <div class="form-group mb_10">
                        <label class="inline_label">Carrying Cost</label>:&nbsp;
                        <span class="inv_show"><input type="number" oninput="update_total()" class="input_val" id="carrying_cost" name="carrying_cost" value="<?php echo $model->payment->carrying_cost; ?>" min="0" step="any"></span>
                        <span>Tk</span>
                    </div>
                    <div class="form-group mb_10">
                        <label class="inline_label">Labor Cost</label>:&nbsp;
                        <span class="inv_show"><input type="number" oninput="update_total()" class="input_val" id="labor_cost" name="labor_cost" value="<?php echo $model->payment->labor_cost; ?>" min="0" step="any"></span>
                        <span>Tk</span>
                    </div>
                    <div class="form-group mb_10">
                        <label class="inline_label">Other Cost</label>:&nbsp;
                        <span class="inv_show"><input type="number" oninput="update_total()" class="input_val" id="other_cost" name="other_cost" value="<?php echo $model->payment->other_cost; ?>" min="0" step="any"></span>
                        <span>Tk</span>
                    </div>
                    <div class="form-group mb_10">
                        <label class="inline_label">Net Amount</label>:&nbsp;
                        <span class="inv_show"><input type="number" id="total_cost" name="total_cost" value="<?php echo $model->payment->net_amount; ?>" min="0" step="any" readonly></span>
                        <span>Tk</span>
                    </div>
                </form>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-4 form-group mpw_33" style="padding-top:50px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "Collected By"); ?></div>
            </div>
            <div class="col-md-4 form-group mpw_33" style="padding-top:50px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "Accountant"); ?></div>
            </div>
            <div class="col-md-4 form-group mpw_33" style="padding-top:50px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "Director"); ?></div>
            </div>
        </div>
    </div>
    <div class="clearfix">
        <div class="form-group text-center">
            <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_PRODUCT_IN_VIEW, ['id' => $model->_key]); ?>"><i class="fa fa-print"></i>&nbsp;<?php echo Yii::t("strings", "Print View"); ?></a>
            <a class="btn btn-primary btn-xs" id="getPaidBtn" href="javascript:void(0);"><i class="fa fa-save"></i>&nbsp;<?php echo Yii::t("strings", "Update"); ?></a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#getPaidBtn", function(e) {
            showLoader("One Moment Please...", true);
            var _form = $("#frmPayInfo");
            var _url = ajaxUrl + '/product_in/update_payment';

            $.post(_url, _form.serialize(), function(resp) {
                if (resp.success === true) {
                    $("#ajaxMessage").showAjaxMessage({html: resp.message, type: 'success'});
                    location.reload();
                } else {
                    $("#ajaxMessage").showAjaxMessage({html: resp.message, type: 'error'});
                }
                showLoader("", false);
            }, "json");
            e.preventDefault();
        });
    });

    function update_total() {
        var adv_total = parseInt(document.getElementById('advance_amount').value);
        var carrying_cost = parseInt(document.getElementById('carrying_cost').value);
        var labor_cost = parseInt(document.getElementById('labor_cost').value);
        var other_cost = parseInt(document.getElementById('other_cost').value);

        var cc_val = !isNaN(carrying_cost) ? parseInt(carrying_cost) : 0;
        var lc_val = !isNaN(labor_cost) ? parseInt(labor_cost) : 0;
        var oc_val = !isNaN(other_cost) ? parseInt(other_cost) : 0;
        var at_val = !isNaN(adv_total) ? parseInt(adv_total) : 0;
        var _total = (cc_val + lc_val + oc_val) - at_val;
        return $("#total_cost").val(_total);
    }
</script>