<?php
$this->breadcrumbs = array(
    'Cash Account' => array(AppUrl::URL_CASH_ACCOUNT),
    'Withdraw'
);
?>
<div class="row content-panel">
    <div class="col-md-4 col-sm-6">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'frmCashAccount',
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
        ));
        ?>
        <div class="clearfix">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'purpose'); ?>
                <?php echo $form->dropdownList($model, 'purpose', $model->typeList(), array('class' => 'form-control')); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'by_whom'); ?>
                <?php echo $form->textField($model, 'by_whom', array('class' => 'form-control')); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'credit'); ?>
                <div class="input-group">
                    <?php echo $form->textField($model, 'credit', array('class' => 'form-control')); ?>
                    <span class="input-group-addon">Tk</span>
                </div>
            </div>
            <div class="form-group text-right">
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Save' : 'Update', array('class' => 'btn btn-primary btn-block')); ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>