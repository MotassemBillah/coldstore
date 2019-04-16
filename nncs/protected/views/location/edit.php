<?php
$this->breadcrumbs = array(
    'Location' => array(AppUrl::URL_LOCATION),
    'Edit'
);
?>
<div class="row content-panel">
    <div class="col-md-4 col-sm-6">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'frmLocation',
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
        ));
        ?>
        <div class="clearfix">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'floor_no'); ?>
                <?php echo $form->textField($model, 'floor_no', array('class' => 'form-control')); ?>
            </div>
            <div class="form-group">
                <?php if (!empty($model->rooms)): ?>
                    <label>Room No</label>
                    <?php foreach ($model->rooms as $room): ?>
                        <input type="text" name="room_no[<?php echo $model->id . '_' . $room->id; ?>]" class="form-control" value="<?php echo $room->room_no; ?>">
                        <div style="padding-left: 30px;">
                            <?php if (!empty($room->pockets)): ?>
                                <label>Pocket No</label>
                                <?php foreach ($room->pockets as $pocket): ?>
                                    <input type="text" name="pocket_no[<?php echo $room->id . '_' . $pocket->id; ?>]" class="form-control" value="<?php echo $pocket->pocket_no; ?>">
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <label>Room No</label>
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <input type="text" name="room_no[<?php echo $i; ?>]" class="form-control" value="">
                        <div style="padding-left: 30px;">
                            <label>Pocket No</label>
                            <?php for ($j = 5; $j < 10; $j++): ?>
                                <input type="text" name="pocket_no[<?php echo $i . '_' . $j; ?>]" class="form-control" value="">
                            <?php endfor; ?>
                        </div>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
            <div class="form-group text-right">
                <?php echo CHtml::submitButton('Update', array('class' => 'btn btn-primary btn-block')); ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>