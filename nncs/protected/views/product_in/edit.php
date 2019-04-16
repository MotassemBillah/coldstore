<?php
$this->breadcrumbs = array(
    'Product In' => array(AppUrl::URL_PRODUCT_IN),
    'Edit'
);
?>
<div class="row content-panel">
    <div class="col-md-12 col-sm-12">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'frmProductIn',
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
            'htmlOptions' => array('class' => 'frm_product'),
        ));
        ?>
        <div class="clearfix">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Product Entry Information</h3>
                </div>
                <div class="panel-body">
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?php
                                echo $form->labelEx($model, 'customer');
                                $customerList = Customer::model()->getList();
                                $cust_list = CHtml::listData($customerList, 'id', 'name');
                                echo $form->dropDownList($model, 'customer_id', $cust_list, array('empty' => 'Customer', 'class' => 'form-control'));
                                ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'sr_no'); ?>
                                <?php echo $form->numberField($model, 'sr_no', array('class' => 'form-control', 'placeholder' => 'sr no', 'min' => 0, 'step' => 'any')); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'quantity'); ?>
                                <?php echo $form->numberField($model, 'quantity', array('class' => 'form-control', 'placeholder' => 'quantity', 'min' => 0, 'step' => 'any')); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, "lot_no"); ?>
                                <?php echo $form->textField($model, "lot_no", array("class" => "form-control", "placeholder" => "lot no")); ?>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, "loan_pack"); ?>
                                <?php echo $form->numberField($model, "loan_pack", array("class" => "form-control", "placeholder" => "loan pack", 'min' => 0, 'step' => 'any')); ?>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, "agent_code"); ?>
                                <?php echo $form->textField($model, "agent_code", array("class" => "form-control", "placeholder" => "agent code")); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'advance_booking_no'); ?>
                                <?php echo $form->numberField($model, 'advance_booking_no', array('class' => 'form-control', 'placeholder' => 'advance booking no', 'min' => 0, 'step' => 'any')); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'advance_booking_amount'); ?>
                                <?php echo $form->numberField($model, 'advance_booking_amount', array('class' => 'form-control', 'placeholder' => 'advance booking amount', 'min' => 0, 'step' => 'any')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Payment Information</h3>
                </div>
                <div class="panel-body">
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <label for="PaymentIn_carrying_cost">Carrying Cost</label>
                                <input type="number" class="form-control amount" id="PaymentIn_carrying_cost" name="PaymentIn[carrying_cost]" value="<?php echo $model->payment->carrying_cost; ?>" min="0" step="any">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                            <div class="form-group">
                                <label for="PaymentIn_labor_cost">Labor Cost</label>
                                <input type="number" class="form-control amount" id="PaymentIn_labor_cost" name="PaymentIn[labor_cost]" value="<?php echo $model->payment->labor_cost; ?>" min="0" step="any">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                            <div class="form-group">
                                <label for="PaymentIn_other_cost">Other Cost</label>
                                <input type="number" class="form-control amount" id="PaymentIn_other_cost" name="PaymentIn[other_cost]" value="<?php echo $model->payment->other_cost; ?>" min="0" step="any">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                            <div class="form-group">
                                <label for="PaymentIn_net_amount">Total Cost</label>
                                <input type="number" class="form-control" id="PaymentIn_net_amount" name="PaymentIn[net_amount]" value="<?php echo $model->payment->net_amount; ?>" min="0" step="any" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 form-group text-center">
                <?php echo CHtml::submitButton(Yii::t("strings", "Update"), array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("input", "#ProductIn_sr_no, #ProductIn_quantity", function() {
            var _srno = document.getElementById('ProductIn_sr_no').value;
            var _qty = document.getElementById('ProductIn_quantity').value;
            document.getElementById('ProductIn_lot_no').value = _srno + '/' + _qty;
        });

        $(document).on("input", ".amount", function(e) {
            get_sum('amount', 'PaymentIn_net_amount');
            e.preventDefault();
        });
    });
</script>