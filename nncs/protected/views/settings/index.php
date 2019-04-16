<?php
$this->breadcrumbs = array(
    'Settings'
);
?>
<div class="clearfix">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'frmSettings',
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
            'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
        ));
        ?>
        <div class="clearfix">
            <ul class="nav nav-tabs nav-justified" role="tablist" style="margin-bottom: 15px;">
                <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab"><?php echo Yii::t("strings", "General"); ?></a></li>
                <li role="presentation"><a href="#misc" aria-controls="misc" role="tab" data-toggle="tab"><?php echo Yii::t("strings", "Miscellaneous"); ?></a></li>
                <li role="presentation"><a href="#extra" aria-controls="extra" role="tab" data-toggle="tab"><?php echo Yii::t("strings", "Extra"); ?></a></li>
                <li role="presentation"><a href="#logos" aria-controls="logos" role="tab" data-toggle="tab"><?php echo Yii::t("strings", "Logos"); ?></a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="general">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'site_name', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->textField($model, 'site_name', array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'title', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->textField($model, 'title', array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'description', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->textArea($model, 'description', array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'author', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->textField($model, 'author', array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'author_email', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->textField($model, 'author_email', array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'author_phone', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->textField($model, 'author_phone', array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'author_mobile', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->textField($model, 'author_mobile', array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'other_contacts', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <p>Add other contact numbers separated by coma (,)</p>
                            <?php echo $form->textArea($model, 'other_contacts', array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'author_address', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->textArea($model, 'author_address', array('class' => 'form-control')); ?>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="misc">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, Yii::t("strings", "auto_pricing"), array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8 col-xs-1">
                            &nbsp;<?php echo $form->checkbox($model, 'auto_pricing', array('class' => 'chk_no_mvam')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, Yii::t("strings", "sendmail"), array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8 col-xs-1">
                            &nbsp;<?php echo $form->checkbox($model, 'sendmail', array('class' => 'chk_no_mvam')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, Yii::t("strings", "sendsms"), array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8 col-xs-1">
                            &nbsp;<?php echo $form->checkbox($model, 'sendsms', array('class' => 'chk_no_mvam')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, Yii::t("strings", "payment_modes"), array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8 col-xs-1">
                            <ul id="payment_mode" class="clearfix">
                                <?php
                                $settingsMode = json_decode($model->payment_modes);
                                $modesArr = array('No Payment', 'Cash Payment', 'Cheque Payment', 'Credit Card', 'Debit Card');
                                for ($i = 0; $i < count($modesArr); $i++) {
                                    $bg_class = '';
                                    $selected = '';
                                    if (!empty($settingsMode)) {
                                        if (in_array($modesArr[$i], $settingsMode)) {
                                            $selected = ' checked="checked"';
                                            $bg_class = 'bg-primary';
                                        }
                                    }
                                    ?>
                                    <li class="<?php echo $bg_class; ?>" id="pay_mode_<?php echo $i; ?>" style="margin:0 0 5px;padding: 5px">
                                        <label for="payment_mode_<?php echo $i; ?>" class="no_mrgn">
                                            <input type="checkbox" class="chk_no_mvam pay_mode" id="payment_mode_<?php echo $i; ?>" name="payment_mode[]" value="<?php echo $modesArr[$i]; ?>" <?php echo $selected; ?>>&nbsp;<?php echo $modesArr[$i]; ?>
                                        </label>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="extra">
                    <div class="form-group" id="vatSetting">
                        <?php echo $form->labelEx($model, Yii::t("strings", "vat"), array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <div class="input-group">
                                <?php echo $form->textField($model, 'vat', array('class' => 'form-control')); ?>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="profitSetting">
                        <?php echo $form->labelEx($model, Yii::t("strings", "profit_count"), array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <div class="input-group">
                                <?php echo $form->textField($model, 'profit_count', array('class' => 'form-control')); ?>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'items per page', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->numberField($model, 'page_size', array('class' => 'form-control', 'min' => 20, 'max' => 500)); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'currency', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->dropDownList($model, 'currency', $model->currencyOptions(), array('empty' => 'Select', 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'theme', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->dropDownList($model, 'theme', array('default' => 'Default', 'black' => 'Black', 'blue' => 'Blue'), array('empty' => 'Select', 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'language', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->dropDownList($model, 'language', Yii::app()->params['languages'], array('empty' => 'Select', 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, Yii::t("strings", "timezone"), array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->dropDownList($model, 'timezone', AppHelper::getTimeZones(), array('empty' => 'Select', 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, Yii::t("strings", "datetime_format"), array('class' => 'col-md-4 text-right')); ?><br>
                        <div class="col-md-8">
                            <fieldset>
                                <label title="F j, Y"><input type="radio"<?php if ($this->settings->datetime_format == "j F Y, g:i A") echo ' checked="checked"'; ?> value="j F Y, g:i A" name="date_format">&nbsp;<?php echo date('j F Y, g:i A', strtotime(date('Y-m-d h:i:s'))); ?></label><br>
                                <label title="m/d/Y"><input type="radio"<?php if ($this->settings->datetime_format == "d-m-Y g:i A") echo ' checked="checked"'; ?> value="d-m-Y g:i A" name="date_format">&nbsp;<?php echo date('d-m-Y g:i A', strtotime(date('Y-m-d h:i:s'))); ?></label><br>
                                <label title="d/m/Y"><input type="radio"<?php if ($this->settings->datetime_format == "d/m/Y g:i A") echo ' checked="checked"'; ?> value="d/m/Y g:i A" name="date_format">&nbsp;<?php echo date('d/m/Y g:i A', strtotime(date('Y-m-d h:i:s'))); ?></label><br>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="logos">
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'favicon', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->fileField($model, 'favicon', array('class' => 'form-control')); ?>
                        </div>
                        <div class="col-md-8 col-md-offset-4" style="margin-top: 15px;">
                            <div class="thumbnail" style="margin-bottom:0;padding:15px;position:relative;">
                                <?php if (!empty($model->favicon)) : ?>
                                    <a class="icon_del remove_img" rel="favicon" href="javascript:void(0);" data-info="<?php echo $model->favicon; ?>"><i class="fa fa-trash-o"></i></a>
                                <?php endif; ?>
                                <img class="img-circle auto_img" rel="favicon" src="<?php echo AppObject::getImage($model->favicon); ?>" style="max-height: 60px;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'logo', array('class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo $form->fileField($model, 'logo', array('class' => 'form-control')); ?>
                        </div>
                        <div class="col-md-8 col-md-offset-4" style="margin-top: 15px;">
                            <div class="thumbnail" style="margin-bottom:0;padding:15px;position:relative;">
                                <?php if (!empty($model->logo)) : ?>
                                    <a class="icon_del remove_img" rel="logo" href="javascript:void(0);" data-info="<?php echo $model->logo; ?>"><i class="fa fa-trash-o"></i></a>
                                <?php endif; ?>
                                <img class="img-circle auto_img" rel="logo" src="<?php echo AppObject::getImage($model->logo); ?>" style="max-height: 120px;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group text-center">
                    <?php echo CHtml::submitButton(Yii::t('strings', 'Save'), array('class' => 'btn btn-primary', 'name' => 'submitSettings')); ?>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", ".pay_mode", function() {
            var _target = $(this).closest('li');

            if ($(this).is(":checked")) {
                $(this).closest('li').addClass('bg-primary');
            } else {
                $(this).closest('li').removeClass('bg-primary');
            }
            console.log(_target);
        });

        $(document).on("click", ".remove_img", function(e) {
            var $this = $(this);
            var _rel = $(this).attr("rel");
            var _info = $(this).attr("data-info");
            var _url = baseUrl + '/settings/remove_image';
            var _rc = confirm('Are you sure about this action?');

            if (_rc === true) {
                showLoader("Processing...", true);
                $.post(_url, {rel: _rel, info: _info}, function(res) {
                    if (res.success === true) {
                        $("#ajaxMessage").removeClass('alert-danger').addClass('alert-success').html("");
                        $("#ajaxMessage").html(res.message).show();
                        $("img[rel=" + _rel + "]").attr('src', baseUrl + '/img/no_photo.gif');
                        setTimeout(hide_ajax_message, 3000);
                        $this.remove();
                    } else {
                        $("#ajaxMessage").removeClass('alert-success').addClass('alert-danger').html("");
                        $("#ajaxMessage").html(res.message).show();
                    }
                    showLoader("", false);
                }, "json");
            } else {
                return false;
            }
            e.preventDefault();
        });
    });
</script>