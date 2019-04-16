<?php
$this->breadcrumbs = array(
    'Product Type' => array(AppUrl::URL_PRODUCT_TYPE),
    $model->isNewRecord ? 'New' : 'Update'
);
?>
<div class="row content-panel">
    <div class="col-md-12">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'frmProductType',
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
        ));
        ?>
        <div class="clearfix">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Product Type Information</h3>
                </div>
                <div class="panel-body">
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'name'); ?>
                                <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group text-center">
                <?php echo CHtml::resetButton('Reset', array('class' => 'btn btn-info')); ?>
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Save' : 'Update', array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>