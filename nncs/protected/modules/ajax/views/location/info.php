<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Close"><span aria-hidden="true">x</span></button>
            <h3 class="modal-title"><?php echo Yii::t("strings", "Add Location"); ?></h3>
            <div id="ajaxModalMessage" class="alert" style="display: none"></div>
        </div>
        <form id="frmLocationSet" action="" method="post">
            <input type="hidden" name="customer_id" value="<?php //echo $payment->customer_id;                             ?>">
            <div class="modal-body" style="height:440px;overflow-y:auto;">
                <div class="row clearfix">
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <label><?php echo Yii::t("strings", "Room No"); ?></label>
                            <?php
                            $roomList = LocationRoom::model()->getList();
                            $rmList = CHtml::listData($roomList, 'id', 'name');
                            echo CHtml::dropDownList('room', 'room', $rmList, array('empty' => 'Select', 'class' => 'form-control', 'required' => 'required'));
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <label><?php echo Yii::t("strings", "Floor No"); ?></label>
                            <?php
                            $floorList = LocationFloor::model()->getList();
                            $flrList = CHtml::listData($floorList, 'id', 'name');
                            echo CHtml::dropDownList('floor', 'floor', $flrList, array('empty' => 'Select', 'class' => 'form-control', 'required' => 'required'));
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <label><?php echo Yii::t("strings", "Pocket No"); ?></label>
                            <?php
                            $pocketList = LocationPocket::model()->getList();
                            $pketList = CHtml::listData($pocketList, 'id', 'pocket_no');
                            echo CHtml::dropDownList('pocket', 'pocket', $pketList, array('empty' => 'Select', 'class' => 'form-control', 'required' => 'required'));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button type="button" class="btn btn-info" data-dismiss="modal" aria-label="Close" title="Close"><?php echo Yii::t("strings", "Cancel"); ?></button>
                <button type="button" class="btn btn-primary" id="processFrm"><?php echo Yii::t("strings", "Save"); ?></button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#processPayment", function(e) {
            e.preventDefault();
            showLoader("One Moment Please...", true);
            var _form = $("#frmCustomerPayment");
            var _url = ajaxUrl + '/sales/update_payment';

            $.post(_url, _form.serialize(), function(response) {
                if (response.success === true) {
                    $("#ajaxModalMessage").removeClass('alert-danger').addClass('alert-success').html("");
                    $("#ajaxModalMessage").html(response.message).show();
                    redirectTo(response.goto);
                } else {
                    $("#ajaxModalMessage").removeClass('alert-success').addClass('alert-danger').html("");
                    $("#ajaxModalMessage").html(response.message).show();
                }
                showLoader("", false);
            }, "json");
            e.preventDefault();
        });
    });
</script>