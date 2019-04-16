<?php
$this->breadcrumbs = array(
    'Payments' => array(AppUrl::URL_PAYMENT),
    'Create'
);
?>
<div class="row content-panel">
    <div class="col-md-12">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'frmCustomerPayment',
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
        ));
        ?>
        <input type="hidden" id="due_payment_id" name="due_payment_id" value="">
        <input type="hidden" id="due_amount" name="due_amount" value="">
        <div class="row clearfix">
            <div class="col-md-3 col-sm-4">
                <div class="form-group">
                    <?php
                    echo $form->labelEx($model, 'customer');
                    $companyList = Customer::model()->getList();
                    $list = CHtml::listData($companyList, 'id', 'name');
                    echo $form->dropDownList($model, 'customer_id', $list, array('empty' => 'Select', 'class' => 'form-control'));
                    ?>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'sr_no'); ?>
                    <?php echo $form->textField($model, 'sr_no', array('class' => 'form-control', 'required' => 'required')); ?>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="form-group">
                    <?php
                    echo $form->labelEx($model, 'payment_type');
                    $typeList = CustomerPayment::model()->typeList();
                    echo $form->dropDownList($model, 'payment_type', $typeList, array('class' => 'form-control', 'required' => 'required'));
                    ?>
                </div>
            </div>
        </div>


        <div class="row clearfix">
            <div class="col-md-3 col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'carrying_cost'); ?>
                    <?php echo $form->numberField($model, 'carrying_cost', array('class' => 'form-control amount', 'min' => 0, 'step' => 'any')); ?>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'labor_cost'); ?>
                    <div class="input-group">
                        <?php echo $form->numberField($model, 'labor_cost', array('class' => 'form-control amount', 'min' => 0, 'step' => 'any')); ?>
                        <span class="input-group-addon">Tk</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'other_cost'); ?>
                    <div class="input-group">
                        <?php echo $form->numberField($model, 'other_cost', array('class' => 'form-control amount', 'min' => 0, 'step' => 'any', 'readonly' => 'readonly')); ?>
                        <span class="input-group-addon">Tk</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-md-3 col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'due_paid'); ?>
                    <div class="input-group">
                        <?php echo $form->textField($model, 'due_paid', array('class' => 'form-control amount')); ?>
                        <span class="input-group-addon">Tk</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'net_amount'); ?>
                    <div class="input-group">
                        <?php echo $form->textField($model, 'net_amount', array('class' => 'form-control', 'readonly' => 'readonly')); ?>
                        <span class="input-group-addon">Tk</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'pay_date'); ?>
                    <div class="input-group">
                        <?php echo $form->textField($model, 'pay_date', array('class' => 'form-control', 'value' => date('d-m-Y'), 'required' => 'required', 'readonly' => 'readonly')); ?>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-md-9 col-sm-12">
                <div class="form-group text-center">
                    <?php echo CHtml::submitButton('Save', array('class' => 'btn btn-primary', 'id' => 'btnSave')); ?>

                    <?php
                    if ($model->isNewRecord) {
                        echo CHtml::button('Search', array('class' => 'btn btn-info', 'id' => 'btnSearch'));
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#CustomerPayment_pay_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on("input", ".amount", function() {
            get_sum('amount', 'CustomerPayment_net_amount');
        });

        $(document).on("change", "#CustomerPayment_payment_type", function() {
            if ($(this).val() == "Due Payment") {
                enable("#btnSearch");
            }
        });

        $(document).on("click", "#btnSearch", function(eve) {
            showLoader("Processing...", true);
            var _form = $("#frmCustomerPayment");
            var _url = ajaxUrl + '/payments/get_info';

            $.post(_url, _form.serialize(), function(resp) {
                if (resp.success === true) {
                    if ($("#CustomerPayment_payment_type").val() == "Due Payment") {
                        $("#due_payment_id").val(resp.pay_info_id);
                        $("#CustomerPayment_due_amount").val(resp.due_cost);
                        $("#due_amount").val(resp.due_cost);
                    } else {
                        $("#CustomerPayment_loan_bag").val(resp.lpack);
                        $("#CustomerPayment_delivered_qty").val(resp.delv_qty);
                    }
                    get_sum('amount', 'CustomerPayment_net_amount');
                } else {
                    $("#ajaxMessage").showAjaxMessage({html: resp.message, type: 'error'});
                    _form[0].reset();
                }
                showLoader("", false);
            }, "json");
    eve.preventDefault();
        });
        });

    function multiply_value(elm, target) {
        var value = !isNaN($(elm).val()) ? parseInt($(elm).val()) : 0;
            var target_elm = $(elm).attr("data-target");
        var num = !isNaN($(target_elm).val()) ? parseInt($(target_elm).val()) : 0;
        var _total = value * num;
            $(target).val(!isNaN(_total) ? _total : '');         get_sum('amount', 'CustomerPayment_net_amount');
    }
</script>