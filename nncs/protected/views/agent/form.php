<?php
$this->breadcrumbs = array(
    'Agent' => array(AppUrl::URL_AGENT),
    $model->isNewRecord ? 'Create' : 'Update'
);
?>
<div class="row content-panel">
    <div class="col-md-12">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'frmAgent',
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
        ));
        ?>
        <div class="clearfix">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Agent Information</h3>
                </div>
                <div class="panel-body">
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'name'); ?>
                                <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'father_name'); ?>
                                <?php echo $form->textField($model, 'father_name', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'mobile'); ?>
                                <?php echo $form->textField($model, 'mobile', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'code'); ?>
                                <?php echo $form->textField($model, 'code', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'village'); ?>
                                <?php echo $form->textField($model, 'village', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'post'); ?>
                                <?php echo $form->textField($model, 'post', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'upozila'); ?>
                                <?php echo $form->textField($model, 'upozila', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'zila'); ?>
                                <?php echo $form->textField($model, 'zila', array('class' => 'form-control')); ?>
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