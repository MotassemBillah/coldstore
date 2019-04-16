<?php
$this->breadcrumbs = array(
    'Payments' => array(AppUrl::URL_PAYMENT),
    'Edit'
);
?>
<div class="row content-panel">
    <div class="col-md-12">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'frmPayment',
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
        ));
        ?>
        <div class="row clearfix">
            <div class="col-md-3 col-sm-4">
                <div class="form-group">
                    <?php
                    echo $form->labelEx($model, 'customer');
                    $companyList = Customer::model()->getList();
                    $list = CHtml::listData($companyList, 'id', 'name');
//                    echo $form->dropDownList($model, 'customer_id', $list, array('empty' => 'Select', 'class' => 'form-control'));
                    ?>
                    <?php // echo $form->textField($model, 'customer_id', array('class' => 'form-control', 'readonly' => 'readonly')); ?>
                    <input type="text" class="form-control" value="<?php echo $model->customer->name; ?>" readonly>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model->payment, 'pay_date'); ?>
                    <div class="input-group">
                        <!--<input type="text" id="datepickerExample" class="form-control" name="pay_date" placeholder="(dd-mm-yyyy)" readonly value="<?php // echo!empty($model->pay_date) ? date("d-m-Y", strtotime($model->pay_date)) : '';                ?>">-->
                        <?php echo $form->textField($model->payment, 'pay_date', array('class' => 'form-control', 'readonly' => 'readonly')); ?>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'sr_no'); ?>
                    <?php echo $form->textField($model, 'sr_no', array('class' => 'form-control', 'readonly' => 'readonly')); ?>
                </div>
                <div class="form-group">
                    <?php
                    echo $form->labelEx($model, 'status');
                    $statusList = [AppConstant::ORDER_COMPLETE => 'Complete', AppConstant::ORDER_PENDING => 'Pending', AppConstant::ORDER_PAID => 'Paid'];
                    echo $form->dropDownList($model->payment, 'status', $statusList, array('empty' => 'Select', 'class' => 'form-control'));
                    ?>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model->payment, 'advance_amount'); ?>
                    <div class="input-group">
                        <?php echo $form->textField($model->payment, 'advance_amount', array('class' => 'form-control amount')); ?>
                        <span class="input-group-addon">Tk</span>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model->payment, 'carrying_cost'); ?>
                    <div class="input-group">
                        <?php echo $form->textField($model->payment, 'carrying_cost', array('class' => 'form-control amount')); ?>
                        <span class="input-group-addon">Tk</span>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model->payment, 'labor_cost'); ?>
                    <div class="input-group">
                        <?php echo $form->textField($model->payment, 'labor_cost', array('class' => 'form-control amount')); ?>
                        <span class="input-group-addon">Tk</span>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model->payment, 'other_cost'); ?>
                    <div class="input-group">
                        <?php echo $form->textField($model->payment, 'other_cost', array('class' => 'form-control amount')); ?>
                        <span class="input-group-addon">Tk</span>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model->payment, 'net_amount'); ?>
                    <div class="input-group">
                        <?php echo $form->textField($model->payment, 'net_amount', array('class' => 'form-control', 'readonly' => 'readonly')); ?>
                        <span class="input-group-addon">Tk</span>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <?php echo CHtml::submitButton('Update', array('class' => 'btn btn-primary', 'name' => 'btnCustomerPayment', 'id' => 'btnCustomerPayment')); ?>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#datepickerExample").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on("input", ".amount", function(e) {
            update_total();
            e.preventDefault();
        });
    });

    function update_total() {
        var carrying_cost = parseInt(document.getElementById('PaymentIn_carrying_cost').value);
        var labor_cost = parseInt(document.getElementById('PaymentIn_labor_cost').value);
        var other_cost = parseInt(document.getElementById('PaymentIn_other_cost').value);
        var adv_total = parseInt(document.getElementById('PaymentIn_advance_amount').value);

        var cc_val = !isNaN(carrying_cost) ? parseInt(carrying_cost) : 0;
        var lc_val = !isNaN(labor_cost) ? parseInt(labor_cost) : 0;
        var oc_val = !isNaN(other_cost) ? parseInt(other_cost) : 0;
        var at_val = !isNaN(adv_total) ? parseInt(adv_total) : 0;
        var _total = (cc_val + lc_val + oc_val) - at_val;
        return $("#PaymentIn_net_amount").val(_total);
    }
</script>