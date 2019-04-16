<?php
$this->breadcrumbs = array(
    'Location' => array(AppUrl::URL_LOCATION),
    'Create'
);
?>
<div class="row content-panel">
    <div class="col-md-4 col-sm-6">
        <?php
//        $form = $this->beginWidget('CActiveForm', array(
//            'id' => 'frmLocation',
//            'enableClientValidation' => true,
//            'clientOptions' => array('validateOnSubmit' => true),
//        ));
        ?>
        <form action="" method="post">
            <div class="clearfix">
                <!--            <div class="form-group">
                <?php // echo $form->labelEx($model, 'floor_no'); ?>
                <?php // echo $form->textField($model, 'floor_no', array('class' => 'form-control')); ?>
                            </div>-->
                <div class="form-group">
                    <label><?php echo Yii::t("strings", "Room No"); ?></label>
                    <?php for ($i = 0; $i < 5; $i++) : ?>
                        <input class="form-control" name="floor_no[]" id="Location_floor_no" type="text">
                    <?php endfor; ?>
                    <?php //echo $form->labelEx($model, 'floor_no'); ?>
                    <?php //echo $form->textField($model, 'floor_no[]', array('class' => 'form-control')); ?>
                </div>
                <!--            <div class="form-group">
                <?php //echo $form->labelEx($model, 'pocket_no'); ?>
                <?php //echo $form->textField($model, 'pocket_no', array('class' => 'form-control')); ?>
                            </div>-->
                <div class="form-group text-right">
                    <input class="btn btn-primary btn-block" name="create_location" type="submit" value="Save">
                    <?php //echo CHtml::submitButton('Save', array('class' => 'btn btn-primary btn-block', 'name' => 'submit_location')); ?>
                </div>
            </div>
        </form>
        <?php //$this->endWidget(); ?>
    </div>
</div>