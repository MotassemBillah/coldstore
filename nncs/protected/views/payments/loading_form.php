<?php
$this->breadcrumbs = array(
    'Payments' => [AppUrl::URL_PAYMENT],
    'Loading' => [AppUrl::URL_PAYMENT_LOADING],
    'New'
);
?>
<div class="row content-panel clearfix">
    <div class="col-md-4 col-sm-6">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'frmPaymentLoading',
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
        ));
        ?>
        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'pament_for', ['class' => 'col-md-6 col-xs-6 text-right']); ?>
            <div class="col-md-6 col-xs-6">
                <?php echo $form->radioButtonList($model, 'pament_for', array('Product In' => 'Product In', 'Product Out' => 'Product Out')); ?>
            </div>
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
        <?php $this->endWidget(); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#PaymentLoading_created").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on("input", "#PaymentLoading_sr_no", function() {
            var _url = ajaxUrl + '/misc/find_sr_qty';

            $.post(_url, {srno: $(this).val()}, function(res) {
                if (res.success === true) {
                    $("#PaymentLoading_quantity").val(res.qty);
                    $("#msg").html('').hide();
                } else {
                    $("#PaymentLoading_quantity").val('');
                    $("#msg").html('No SR found').show();
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
    });
</script>