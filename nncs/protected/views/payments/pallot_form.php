<?php
$this->breadcrumbs = array(
    'Payments' => [AppUrl::URL_PAYMENT],
    'Pallot' => [AppUrl::URL_PAYMENT_PALLOT],
    'New'
);
?>
<div class="row content-panel clearfix">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'frmPaymentLoading',
        'enableClientValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));
    ?>
    <div class="col-md-4 col-sm-6">
        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'pament_for', ['class' => 'col-md-6 col-xs-6 text-right']); ?>
            <?php echo $form->textField($model, 'pament_for', array('class' => 'col-md-6 col-xs-6')); ?>
        </div>
        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'Date', ['class' => 'col-md-6 col-xs-6 text-right']); ?>
            <?php echo $form->textField($model, 'created', array('class' => 'col-md-6 col-xs-6', 'readonly' => 'readonly', 'value' => date('d-m-Y'))); ?>
        </div>
        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'sr_no', ['class' => 'col-md-6 col-xs-6 text-right']); ?>
            <?php echo $form->textField($model, 'sr_no', array('class' => 'col-md-6 col-xs-6')); ?>
            <label class="col-md-offset-4 error text-center" id="msg" style="display:none;"></label>
        </div>
        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'quantity', ['class' => 'col-md-6 col-xs-6 text-right']); ?>
            <?php echo $form->textField($model, 'quantity', array('class' => 'col-md-6 col-xs-6')); ?>
        </div>
        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'quantity_price', ['class' => 'col-md-6 col-xs-6 text-right']); ?>
            <?php echo $form->textField($model, 'quantity_price', array('class' => 'col-md-6 col-xs-6')); ?>
        </div>
        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'price_total', ['class' => 'col-md-6 col-xs-6 text-right']); ?>
            <?php echo $form->textField($model, 'price_total', array('class' => 'col-md-6 col-xs-6', 'readonly' => 'readonly')); ?>
        </div>
        <div class="form-group text-center">
            <?php echo CHtml::resetButton('Reset', array('class' => 'btn btn-info')); ?>
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Save' : 'Update', array('class' => 'btn btn-primary')); ?>
        </div>
    </div>
    <div class="col-md-6 col-sm-6">
        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'current_location', ['class' => '']); ?>&nbsp;&nbsp;<b>:</b>
            <input type="hidden" id="cur_stock_id" name="cur_stock_id" value="">
            <input type="hidden" id="cur_room" name="cur_room" value="">
            <input type="hidden" id="cur_floor" name="cur_floor" value="">
            <input type="hidden" id="cur_pockets" name="cur_pockets" value="">
            <div id="cur_loc"></div>
        </div>
        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'new_location', ['class' => '']); ?>&nbsp;&nbsp;<b>:</b>
            <div id="new_loc">
                <div class="form-group clearfix">
                    <label class="col-md-4 col-sm-6 text-right"><?php echo Yii::t("strings", "Room"); ?></label>
                    <?php
                    $roomList = LocationRoom::model()->getList();
                    $rmList = CHtml::listData($roomList, 'id', 'name');
                    echo CHtml::dropDownList('room', 'room', $rmList, array('empty' => 'Select', 'class' => 'col-md-6 col-xs-6', 'required' => 'required'));
                    ?>
                </div>
                <div class="form-group clearfix">
                    <label class="col-md-4 col-sm-6 text-right"><?php echo Yii::t("strings", "Floor"); ?></label>
                    <?php
                    $floorList = LocationFloor::model()->getList();
                    $fList = CHtml::listData($floorList, 'id', 'name');
                    echo CHtml::dropDownList('floor', 'floor', $fList, array('empty' => 'Select', 'class' => 'col-md-6 col-xs-6', 'required' => 'required'));
                    ?>
                </div>
                <div class="form-group clearfix">
                    <ul class="block_list" id="pockets"></ul>
                </div>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#PaymentLoading_created").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on("input", "#PaymentLoading_sr_no", function() {
            var _url = ajaxUrl + '/misc/find_sr_qty';
            var _pallot_url = ajaxUrl + '/misc/find_sr_location';

            $.post(_url, {srno: $(this).val()}, function(res) {
                if (res.success === true) {
                    $("#PaymentLoading_quantity").val(res.qty);
                    $("#msg").html('').hide();
                } else {
                    $("#PaymentLoading_quantity").val('');
                    $("#msg").html('No SR found').show();
                }
            }, "json");

            $.post(_pallot_url, {srno: $(this).val()}, function(res) {
                if (res.success === true) {
                    $("#cur_loc").html(res.message);
                    $("#cur_stock_id").val(res.stk_id);
                    $("#cur_room").val(res.room);
                    $("#cur_floor").val(res.floor);
                    $("#cur_pockets").val(res.pockets);
                } else {
                    $("#cur_loc").html(res.message);
                    $("#cur_stock_id").val('');
                    $("#cur_room").val('');
                    $("#cur_floor").val('');
                    $("#cur_pockets").val('');
                }
            }, "json");
        });

        $(document).on("input", "#PaymentLoading_quantity_price", function() {
            var _val = parseInt($(this).val());
            var _qty = parseInt($("#PaymentLoading_quantity").val());
            var _amount = parseInt(_val * _qty);
            if (!isNaN(_val) && !isNaN(_qty)) {
                $("#PaymentLoading_price_total").val(_amount);
            } else {
                $("#PaymentLoading_price_total").val('');
            }
            console.log(_val);
        });

        $(document).on("change", "#room", function() {
            $("#pockets").html("<li style='width:100%'>No pocket found.<li>");
            var _url = ajaxUrl + "/misc/find_floor";

            if ($(this).val() !== "") {
                $.post(_url, {room: $(this).val()}, function(resp) {
                    if (resp.success === true) {
                        $("#floor").html(resp.html);
                    } else {
                        $("#floor").html(resp.html);
                    }
                }, "json");
            } else {
                $("#floor").html("<option value=''>Not Found</option>");
                $("#pockets").html("<li style='width:100%'>No pocket found.<li>");
            }
        });

        $(document).on("change", "#floor", function() {
            var _url = ajaxUrl + "/misc/find_pocket_by_floor";

            if ($(this).val() !== "") {
                $.post(_url, {room: $("#room").val(), floor: $(this).val()}, function(resp) {
                    if (resp.success === true) {
                        $("#pockets").html(resp.html);
                    } else {
                        $("#pockets").html(resp.html);
                    }
                }, "json");
            } else {
                $("#pockets").html("<li style='width:100%'>No pocket found.<li>");
            }
        });
    });
</script>