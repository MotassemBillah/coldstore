<?php
$this->breadcrumbs = array(
    'Pallot' => [AppUrl::URL_PALLOT],
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
    <div class="col-md-8 col-sm-8">
        <div class="error mb_10" id="msg" style="border-bottom:1px solid #cdcdcd;display:none;padding:5px 0;"></div>
        <div class="row clearfix">
            <div class="col-md-6 col-sm-6">
                <div class="form-group clearfix">
                    <?php echo $form->labelEx($model, 'pallot_date', ['class' => 'col-md-6 col-xs-6 text-right']); ?>
                    <?php echo $form->textField($model, 'pallot_date', array('class' => 'col-md-6 col-xs-6', 'readonly' => 'readonly', 'value' => date('d-m-Y'))); ?>
                </div>
                <div class="form-group clearfix">
                    <?php echo $form->labelEx($model, 'pallot_number', ['class' => 'col-md-6 col-xs-6 text-right']); ?>
                    <?php echo $form->textField($model, 'pallot_number', array('class' => 'col-md-6 col-xs-6', 'required' => 'required')); ?>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="form-group clearfix">
                    <label class="col-md-6 col-xs-6 text-right" for="sr_no">SR Number <span class="required">*</span></label>
                    <input type="text" id="sr_no" name="sr_no" class="col-md-6 col-xs-6" required>
                </div>
                <div class="form-group clearfix">
                    <label class="col-md-6 col-xs-6 text-right" for="sum_quantity">Quantity <span class="required">*</span></label>
                    <input type="text" id="sum_quantity" name="sum_quantity" class="col-md-6 col-xs-6" required readonly>
                </div>
            </div>
        </div>

        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'current_location'); ?>&nbsp;<b>:</b>
            <div class="" id="tbl_current_location">
                <table class="table table-striped table-bordered tbl_invoice_view no_mrgn">
                    <tr>
                        <th style="width:16%">Date</th>
                        <th style="width:20%">Pallot Number</th>
                        <th style="width:16%">Room</th>
                        <th style="width:16%">Floor</th>
                        <th style="width:16%">Pocket</th>
                        <th style="width:16%">Quantity</th>
                    </tr>
                </table>
            </div>
        </div>

        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'new_location', ['class' => '']); ?>&nbsp;<b>:</b>
            <table class="table table-striped table-bordered tbl_invoice_view no_mrgn">
                <tr>
                    <th style="width:25%">Room</th>
                    <th style="width:25%">Floor</th>
                    <th style="width:25%">Pocket</th>
                    <th style="width:25%">Quantity</th>
                </tr>
                <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <tr>
                        <td>
                            <?php
                            $roomList = LocationRoom::model()->getList();
                            $rmList = CHtml::listData($roomList, 'id', 'name');
                            echo $form->dropDownList($modelItem, 'room[]', $rmList, array('empty' => 'Select', 'id' => "room_{$i}", 'class' => 'room', 'data-info' => $i, 'style' => 'width:100%'));
                            ?>
                        </td>
                        <td>
                            <?php
                            $floorList = LocationFloor::model()->getList();
                            $fList = CHtml::listData($floorList, 'id', 'name');
                            echo $form->dropDownList($modelItem, 'floor[]', [], array('empty' => 'Select', 'id' => "floor_{$i}", 'class' => 'floor', 'data-info' => $i, 'style' => 'width:100%'));
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $form->dropDownList($modelItem, 'pocket[]', [], array('empty' => 'Select', 'id' => "pocket_{$i}", 'class' => 'pocket', 'data-info' => $i, 'style' => 'width:100%'));
                            ?>
                        </td>
                        <td><?php echo $form->numberField($modelItem, 'quantity[]', array('id' => "quantity_{$i}", 'class' => 'qty', 'data-info' => $i, 'min' => 0, 'style' => 'width:100%')); ?></td>
                    </tr>
                <?php endfor; ?>
            </table>
        </div>

        <div class="form-group text-center">
            <?php echo CHtml::resetButton('Reset', array('class' => 'btn btn-info')); ?>
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Save' : 'Update', array('class' => 'btn btn-primary', 'id' => 'btnSubmit', 'name' => 'btnSubmit')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#Pallot_pallot_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on("focusout", "#sr_no", function() {
            var _url = ajaxUrl + '/misc/find_sr_location';

            $.post(_url, {srno: $(this).val()}, function(res) {
                if (res.success === true) {
                    $("#msg").html('').hide();
                    $("#sum_quantity").val(res.qty);
                    $("#Pallot_pallot_number").val(res.pallotNo);
                    $("#tbl_current_location").html(res.location);
                } else {
                    $("#msg").html(res.message).show();
                    $("#sum_quantity").val('');
                    $("#Pallot_pallot_number").val('');
                    $("#tbl_current_location").html(res.location);
                }
                console.log(res.location);
            }, "json");
        });

        $(document).on("change", ".room", function() {
            var _id = $(this).attr('data-info');
            $("#pocket_" + _id).html("<option value=''>Not Found</option>");
            var _url = ajaxUrl + "/misc/find_floor";

            if ($(this).val() !== "") {
                $.post(_url, {room: $(this).val()}, function(resp) {
                    if (resp.success === true) {
                        $("#floor_" + _id).html(resp.html);
                    } else {
                        $("#floor_" + _id).html(resp.html);
                    }
                }, "json");
            } else {
                $("#floor_" + _id).html("<option value=''>Not Found</option>");
                $("#pocket_" + _id).html("<option value=''>Not Found</option>");
            }
        });

        $(document).on("change", ".floor", function() {
            var _id = $(this).attr('data-info');
            var _room = $("#room_" + _id).val();
            var _url = ajaxUrl + "/misc/find_pocket";

            if ($(this).val() !== "") {
                $.post(_url, {room: _room, floor: $(this).val()}, function(resp) {
                    if (resp.success === true) {
                        $("#pocket_" + _id).html(resp.html);
                    } else {
                        $("#pocket_" + _id).html(resp.html);
                    }
                }, "json");
            } else {
                $("#pocket_" + _id).html("<option value=''>Not Found</option>");
            }
        });

        $(document).on("input", ".qty", function(e) {
            var totalSum = document.getElementById('sum_quantity').value;
            var countSum = count_sum('qty');

            if (countSum > totalSum) {
                $("#msg").html("Sum of quantity should not exceed " + totalSum).show();
                disable("#btnSubmit");
            } else {
                $("#msg").html('').hide();
                enable("#btnSubmit");
            }
            return false;
            e.preventDefault();
        });
    });
</script>