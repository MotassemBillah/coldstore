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
            'id' => 'frmProductOut',
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
            'htmlOptions' => array('class' => 'frm_product'),
        ));
        ?>
        <div class="clearfix">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Product Delivery Information</h3>
                </div>
                <div class="panel-body">
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'customer'); ?>
                                <?php
                                $customerList = Customer::model()->getList();
                                $cust_list = CHtml::listData($customerList, 'id', 'name');
                                echo $form->dropdownList($model, 'customer_id', $cust_list, array('empty' => 'Select', 'class' => 'form-control'));
                                ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'sr_no'); ?>
                                <?php echo $form->numberField($model, 'sr_no', array('class' => 'form-control', 'placeholder' => 'sr no')); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'delivery_sr_no'); ?>
                                <?php echo $form->numberField($model, 'delivery_sr_no', array('class' => 'form-control', 'placeholder' => 'delivery sr no')); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'advance_booking_no'); ?>
                                <?php echo $form->numberField($model, 'advance_booking_no', array('class' => 'form-control', 'placeholder' => 'advance booking no')); ?>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'quantity'); ?>
                                <?php echo $form->numberField($model, 'quantity', array('class' => 'form-control', 'min' => 0, 'max' => $model->quantity)); ?>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, "loan_pack"); ?>
                                <?php echo $form->numberField($model, "loan_pack", array("class" => "form-control", "readonly" => "readonly")); ?>
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
                                <?php echo $form->labelEx($model, "agent_code"); ?>
                                <?php echo $form->numberField($model, "agent_code", array("class" => "form-control")); ?>
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
        $(document).on("input", "#ProductOut_sr_no, #ProductOut_quantity", function() {
            var _srno = document.getElementById('ProductOut_sr_no').value;
            var _qty = document.getElementById('ProductOut_quantity').value;
            document.getElementById('ProductOut_lot_no').value = _srno + '/' + _qty;
        });
    });
</script>