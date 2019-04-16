<?php
$this->breadcrumbs = array(
    'Customer' => [AppUrl::URL_CUSTOMER],
    'Loan' => $this->createUrl(AppUrl::URL_CUSTOMER_LOAN, ['id' => $model->_key]),
    'Advance'
);
?>
<div class="row content-panel">
    <div class="col-md-12">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'frmCustomerLoan',
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
        ));
        ?>
        <div class="clearfix">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Advance Loan Payment Information</h3>
                </div>
                <div class="panel-body">
                    <div class="row clearfix">
                        <input type="hidden" id="customerID" name="customerID" value="<?php echo $model->id; ?>">
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group clearfix">
                                <?php echo $form->labelEx($loanForm, 'Loan Case No', ['class' => 'col-md-6 col-xs-6 text-right']); ?>
                                <?php echo $form->textField($loanForm, 'case_no', array('class' => 'col-md-6 col-xs-6')); ?>
                            </div>
                            <div class="form-group clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="pay_date">Date</label>
                                <input type="text" class="col-md-6 col-xs-6" id="pay_date" name="pay_date" readonly value="<?php echo date('d-m-Y'); ?>">
                            </div>
                            <div class="form-group clearfix">
                                <!--                                <label class="col-md-6 col-xs-6 text-right" for="loan_bag">Empty Bag</label>
                                                                <input type="text" class="col-md-6 col-xs-6" id="loan_bag" name="loan_bag">-->
                                <?php echo $form->labelEx($loanForm, 'empty_bag', ['class' => 'col-md-6 col-xs-6 text-right']); ?>
                                <?php echo $form->textField($loanForm, 'empty_bag', array('class' => 'col-md-6 col-xs-6')); ?>
                            </div>
                            <div class="form-group clearfix">
                                <!--                                <label class="col-md-6 col-xs-6 text-right" for="loan_bag_price">Empty Bag Price</label>
                                                                <input type="number" class="col-md-6 col-xs-6" id="loan_bag_price" name="loan_bag_price" min="0" step="any">-->
                                <?php echo $form->labelEx($loanForm, 'empty_bag_price', ['class' => 'col-md-6 col-xs-6 text-right']); ?>
                                <?php echo $form->numberField($loanForm, 'empty_bag_price', array('class' => 'col-md-6 col-xs-6', 'min' => 0, 'step' => 'any')); ?>
                            </div>
                            <div class="form-group clearfix">
                                <!--                                <label class="col-md-6 col-xs-6 text-right" for="carrying_amount">Carrying</label>
                                                                <input type="text" class="col-md-6 col-xs-6" id="carrying_amount" name="carrying_amount">-->
                                <?php echo $form->labelEx($loanForm, 'carrying_cost', ['class' => 'col-md-6 col-xs-6 text-right']); ?>
                                <?php echo $form->textField($loanForm, 'carrying_cost', array('class' => 'col-md-6 col-xs-6')); ?>
                            </div>
                            <div class="form-group clearfix">
                                <!--                                <label class="col-md-6 col-xs-6 text-right" for="loan_amount">Loan Amount</label>
                                                                <input type="text" class="col-md-6 col-xs-6" id="loan_amount" name="loan_amount">-->
                                <?php echo $form->labelEx($loanForm, 'loan_amount', ['class' => 'col-md-6 col-xs-6 text-right']); ?>
                                <?php echo $form->textField($loanForm, 'loan_amount', array('class' => 'col-md-6 col-xs-6')); ?>
                            </div>
                            <div class="form-group text-center clearfix">
                                <input type="submit" class="btn btn-primary" id="submit_adv_loan" name="submit_adv_loan" value="Submit">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#pay_date").datepicker({
            format: 'dd-mm-yyyy'
        });
    });
</script>