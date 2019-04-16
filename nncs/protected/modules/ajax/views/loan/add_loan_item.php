<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Close"><span aria-hidden="true">x</span></button>
            <h3 class="modal-title">Add A New Item To :: Case No <?php echo $loanInfo['case']; ?></h3>
            <div id="ajaxModalMessage" class="alert" style="display: none"></div>
        </div>
        <form action="" id="frmAddLoanItem" method="post">
            <input type="hidden" id="loanID" name="loanID" value="<?php echo $loanInfo['id']; ?>">
            <input type="hidden" id="loanCase" name="loanCase" value="<?php echo $loanInfo['case']; ?>">
            <div class="modal-body" style="max-height:440px;overflow-y:auto;">
                <div class="clearfix">
                    <div class="table-responsive">
                        <table class="table table-bordered no_mrgn">
                            <tr>
                                <th>Sr Number</th>
                                <td><input type="text" id="sr_info" name="sr_info"></td>
                            </tr>
                            <tr>
                                <th>Quantity</th>
                                <td><input type="text" id="quantity" name="quantity" readonly></td>
                            </tr>
                            <tr>
                                <th>Per Qty Loan</th>
                                <td><input type="number" id="rent" name="rent" value="<?php echo $loanSetting->max_loan_per_qty; ?>" min="0" max="<?php echo $loanSetting->max_loan_per_qty; ?>" step="any"></td>
                            </tr>
                            <tr>
                                <th>Total Loan</th>
                                <td><input type="text" id="loan_amount" name="loan_amount" value="" readonly></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button type="button" class="btn btn-info" data-dismiss="modal" aria-label="Close" title="Close"><?php echo Yii::t("strings", "Cancel"); ?></button>
                <button type="submit" class="btn btn-primary" id="btnAddItem"><?php echo Yii::t("strings", "Save"); ?></button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("focusout", "#sr_info", function() {
            var _url = ajaxUrl + '/misc/add_srto_loan';

            showLoader("Fetching Data...", true);
            $.post(_url, {srno: $(this).val()}, function(resp) {
                if (resp.success === true) {
                    $("#quantity").val(resp.qty);
                    $("#quantity").attr('max', resp.qty);
                    $("#loan_amount").val(parseInt($("#rent").val() * resp.qty));
                } else {
                    $("#ajaxModalMessage").showAjaxMessage({html: resp.message, type: "error"});
                }
                showLoader("", false);
            }, "json");
        });

        $(document).on("input", "#quantity, #rent", function() {
            var _qty = $("#quantity").val();
            var _rent = $("#rent").val();
            var _amount = (_qty * _rent);
            $("#loan_amount").val(parseFloat(_amount).toFixed(2));
        });

        $(document).on("submit", "#frmAddLoanItem", function(e) {
            showLoader("Processing...", true);
            disable("#btnAddItem");
            var _form = $(this);
            var _url = ajaxUrl + '/loan/add_item';

            $.post(_url, _form.serialize(), function(resp) {
                if (resp.success === true) {
                    $("#ajaxModalMessage").showAjaxMessage({html: resp.message, type: 'success'});
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    $("#ajaxModalMessage").showAjaxMessage({html: resp.message, type: 'error'});
                    enable("#btnAddItem");
                }
                showLoader("", false);
            }, "json");
            e.preventDefault();
        });
    });
</script>