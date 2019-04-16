<?php
$this->breadcrumbs = array(
    'Loan Setting'
);
?>
<div class="content-panel">
    <?php if (!empty($model)) : ?>
        <form id="frmUpdateLoanSetting" action="" method="post">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tr class="bg_gray">
                        <th style="width: 15%"><?php echo Yii::t("strings", "Property"); ?></th>
                        <th><?php echo Yii::t("strings", "Value"); ?></th>
                    </tr>
                    <tr>
                        <th><?php echo Yii::t("strings", "Interest Rate"); ?></th>
                        <td>
                            <span class="show_txt" id="txtIntRate"><?php echo $model->interest_rate; ?></span>
                            <span class="show_input" style="display: none;"><input type="number" id="interest_rate" name="interest_rate" min="0" value="<?php echo $model->interest_rate; ?>"></span>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo Yii::t("strings", "Loan Period Days"); ?></th>
                        <td>
                            <span class="show_txt" id="txtPeriod"><?php echo $model->period; ?></span>
                            <span class="show_input" style="display: none;"><input type="number" id="period" name="period" min="0" value="<?php echo $model->period; ?>"></span>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo Yii::t("strings", "Minimum Days To Pay"); ?></th>
                        <td>
                            <span class="show_txt" id="txtMinDay"><?php echo $model->min_day; ?></span>
                            <span class="show_input" style="display: none;"><input type="number" id="min_day" name="min_day" min="0" value="<?php echo $model->min_day; ?>"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="2">
                            <button class="btn btn-info btn-xs" id="change_setting" type="button"><?php echo Yii::t("strings", "Change"); ?></button>
                            <button class="btn btn-primary btn-xs" id="update_setting" type="button" disabled><?php echo Yii::t("strings", "Update"); ?></button>
                            <button class="btn btn-warning btn-xs" id="cancle_setting" type="button" disabled><?php echo Yii::t("strings", "Cancel"); ?></button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-info">No records found!</div>
    <?php endif; ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(".show_input").hide();
        $(document).on("click", "#change_setting", function(e) {
            $(".show_txt").hide();
            $(".show_input").show();
            disable("#change_setting");
            enable("#update_setting");
            enable("#cancle_setting");
            e.preventDefault();
        });

        $(document).on("click", "#cancle_setting", function(e) {
            $(".show_input").hide();
            $(".show_txt").show();
            disable("#cancle_setting");
            disable("#update_setting");
            enable("#change_setting");
            e.preventDefault();
        });

        $(document).on("click", "#update_setting", function(e) {
            showLoader("Processing...", true);
            var _form = $("#frmUpdateLoanSetting");
            var _url = ajaxUrl + '/loan/update_setting';

            $.post(_url, _form.serialize(), function(resp) {
                if (resp.success === true) {
                    $("#ajaxMessage").showAjaxMessage({html: resp.message, type: 'success'});
                    document.getElementById('txtIntRate').innerHTML = document.getElementById('interest_rate').value;
                    document.getElementById('txtPeriod').innerHTML = document.getElementById('period').value;
                    document.getElementById('txtMinDay').innerHTML = document.getElementById('min_day').value;
                    $("#cancle_setting").trigger('click');
                } else {
                    $("#ajaxMessage").showAjaxMessage({html: resp.message, type: 'error'});
                    $("#cancle_setting").trigger('click');
                }
                showLoader("", false);
            }, "json");
            e.preventDefault();
        });
    });
</script>