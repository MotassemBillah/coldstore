<?php
$this->breadcrumbs = array(
    'Customer' => array(AppUrl::URL_CUSTOMER),
    $model->isNewRecord ? 'Create' : 'Update'
);
?>
<div class="row content-panel">
    <div class="col-md-12">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'frmCustomer',
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
        ));
        ?>
        <div class="clearfix">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Customer Information</h3>
                </div>
                <div class="panel-body">
                    <div class="row clearfix">
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'name'); ?>
                                <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'father_name'); ?>
                                <?php echo $form->textField($model, 'father_name', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'mobile'); ?>
                                <?php echo $form->textField($model, 'mobile', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'village'); ?>
                                <?php echo $form->textField($model, 'village', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'thana'); ?>
                                <?php echo $form->textField($model, 'thana', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'district'); ?>
                                <?php echo $form->textField($model, 'district', array('class' => 'form-control')); ?>
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
<script type="text/javascript">
    $(document).ready(function() {
//        $(document).on("change", "#Customer_district", function() {
//            var _url = ajaxUrl + '/misc/find_thana';
//            $.post(_url, {did: $(this).val()}, function(res) {
//                if (res.success === true) {
//                    enable("#Customer_thana");
//                    $("#Customer_thana").html(res.html);
//                } else {
//                    $("#Customer_thana").html(res.html);
//                    disable("#Customer_thana");
//                }
//            }, "json");
//        });
    });
</script>