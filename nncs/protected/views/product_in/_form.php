<?php
$this->breadcrumbs = array(
    'Product In' => array(AppUrl::URL_PRODUCT_IN),
    $model->isNewRecord ? 'Create' : 'Update'
);
?>
<div class="content-panel">
    <div class="col-md-12">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'frmProductIn',
            'enableClientValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
            'htmlOptions' => array('class' => 'frm_product'),
        ));
        ?>
        <div class="clearfix">
            <?php if (!$model->isNewRecord): ?>
                <div class="form-group" style="font-size: 16px;">
                    <label>Do you want to transfer this information to another person?</label>
                    <label for="answer_yes" style="margin-left: 10px;"><input type="radio" class="toggle_answer" id="answer_yes" name="answer" value="<?php echo AppConstant::YES; ?>">&nbsp;<?php echo Yii::t('strings', AppConstant::YES); ?></label>
                    <label for="answer_no" style="margin-left: 10px;"><input type="radio" class="toggle_answer" id="answer_no" name="answer" value="<?php echo AppConstant::NO; ?>" checked>&nbsp;<?php echo Yii::t('strings', AppConstant::NO); ?></label>
                </div>
            <?php endif; ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Customer SR Entry Form<?php echo $model->isNewRecord ? '' : " Update"; ?></h3>
                </div>
                <div class="panel-body">
                    <div class="row form-group clearfix">
                        <div class="col-md-4 col-sm-6">
                            <?php if (!$model->isNewRecord): ?>
                                <input type="hidden" id="customer_id" name="Customer[id]" value="<?php echo $model->customer_id; ?>">
                            <?php endif; ?>
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right required" for="">এস আরঃ</label>
                                <?php if ($model->isNewRecord): ?>
                                    <?php echo $form->textField($model, 'sr_no', array('class' => 'col-md-6 col-xs-6', 'value' => $srno, 'required' => 'required')); ?>
                                <?php else: ?>
                                    <?php echo $form->textField($model, 'sr_no', array('class' => 'col-md-6 col-xs-6', 'required' => 'required')); ?>
                                <?php endif; ?>
                            </div>
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="">তারিখঃ</label>
                                <?php if ($model->isNewRecord): ?>
                                    <?php echo $form->textField($model, 'create_date', array('class' => 'col-md-6 col-xs-6', 'readonly' => 'readonly', 'value' => date('d-m-Y'), 'required' => 'required')); ?>
                                <?php else: ?>
                                    <?php echo $form->textField($model, 'create_date', array('class' => 'col-md-6 col-xs-6', 'readonly' => 'readonly', 'value' => date('d-m-Y', strtotime($model->create_date)), 'required' => 'required')); ?>
                                <?php endif; ?>
                            </div>
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="">বুকিং নম্বরঃ</label>
                                <?php echo $form->textField($model, 'advance_booking_no', array('class' => 'col-md-6 col-xs-6')); ?>
                            </div>
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="">এজেন্ট নম্বরঃ</label>
                                <?php echo $form->textField($model, 'agent_code', array('class' => 'col-md-6 col-xs-6', 'autocmplete' => 'off')); ?>
                            </div>
                        </div>
                        <?php if ($model->isNewRecord): ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">এজেন্টের নামঃ</label>
                                    <input class="col-md-6 col-xs-6" readonly="readonly" name="Agent[name]" id="Agent_name" type="text">
                                </div>
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">এজেন্টের গ্রামঃ</label>
                                    <input class="col-md-6 col-xs-6" readonly="readonly" name="Agent[village]" id="Agent_village" type="text">
                                </div>
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">এজেন্টের জেলাঃ</label>
                                    <input class="col-md-6 col-xs-6" readonly="readonly" name="Agent[zila]" id="Agent_zila" type="text">
                                </div>
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">এজেন্টের মোবাইলঃ</label>
                                    <input class="col-md-6 col-xs-6" readonly="readonly" name="Agent[mobile]" id="Agent_mobile" type="text">
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">এজেন্টের নামঃ</label>
                                    <input class="col-md-6 col-xs-6" readonly="readonly" name="Agent[name]" id="Agent_name" type="text" value="<?php echo!empty($agent) ? $agent->name : ''; ?>">
                                </div>
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">এজেন্টের গ্রামঃ</label>
                                    <input class="col-md-6 col-xs-6" readonly="readonly" name="Agent[village]" id="Agent_village" type="text" value="<?php echo!empty($agent) ? $agent->village : ''; ?>">
                                </div>
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">এজেন্টের জেলাঃ</label>
                                    <input class="col-md-6 col-xs-6" readonly="readonly" name="Agent[zila]" id="Agent_zila" type="text" value="<?php echo!empty($agent) ? $agent->zila : ''; ?>">
                                </div>
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">এজেন্টের মোবাইলঃ</label>
                                    <input class="col-md-6 col-xs-6" readonly="readonly" name="Agent[mobile]" id="Agent_mobile" type="text" value="<?php echo!empty($agent) ? $agent->mobile : ''; ?>">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-4 col-sm-6">
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="">স্বত্বাধিকারীর নামঃ</label>
                                <?php echo $form->textField($customer, 'name', array('class' => 'col-md-6 col-xs-6')); ?>
                            </div>
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="">পিতার নামঃ</label>
                                <?php echo $form->textField($customer, 'father_name', array('class' => 'col-md-6 col-xs-6')); ?>
                            </div>
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="">গ্রামঃ</label>
                                <?php echo $form->textField($customer, 'village', array('class' => 'col-md-6 col-xs-6')); ?>
                            </div>
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="">উপজেলাঃ</label>
                                <?php echo $form->textField($customer, 'thana', array('class' => 'col-md-6 col-xs-6')); ?>
                            </div>
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="">জেলাঃ</label>
                                <?php echo $form->textField($customer, 'district', array('class' => 'col-md-6 col-xs-6')); ?>
                            </div>
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="">মোবাইলঃ</label>
                                <?php echo $form->textField($customer, 'mobile', array('class' => 'col-md-6 col-xs-6')); ?>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="">আলুর জাতঃ</label>
                                <?php
                                $typeList = ProductType::model()->getList();
                                $types = CHtml::listData($typeList, 'id', 'name');
                                echo $form->dropDownList($model, 'type', $types, array('empty' => 'Select', 'class' => 'col-md-6 col-xs-6', 'required' => 'required'));
                                ?>
                            </div>
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="">সংরক্ষনকৃত বস্তাঃ</label>
                                <?php echo $form->textField($model, 'quantity', array('class' => 'col-md-6 col-xs-6', 'required' => 'required')); ?>
                            </div>
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="">খালি বস্তাঃ</label>
                                <?php echo $form->textField($model, 'loan_pack', array('class' => 'col-md-6 col-xs-6')); ?>
                            </div>
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="">লট নংঃ</label>
                                <?php echo $form->textField($model, 'lot_no', array('class' => 'col-md-6 col-xs-6', 'readonly' => 'readonly', 'required' => 'required')); ?>
                            </div>
                            <div class="mb_5 clearfix">
                                <label class="col-md-6 col-xs-6 text-right" for="">পরিবহনঃ</label>
                                <?php echo $form->numberField($model, 'carrying_cost', array('class' => 'col-md-6 col-xs-6', 'min' => 0)); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix">
            <div class="form-group text-center">
                <?php echo CHtml::resetButton('Reset', array('class' => 'btn btn-info', 'style' => 'margin-right:20px;width:100px')); ?>
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Save' : 'Update', array('class' => 'btn btn-primary', 'style' => 'width:100px')); ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script type="text/javascript">
    function clear_agent_info() {
        $("#Agent_name").val('');
        $("#Agent_village").val('');
        $("#Agent_zila").val('');
        $("#Agent_mobile").val('');
    }

    function clear_customer_info() {
        $("#Customer_name").val('');
        $("#Customer_father_name").val('');
        $("#Customer_district").val('');
        $("#Customer_thana").val('');
        $("#Customer_village").val('');
        $("#Customer_mobile").val('');
    }

    $(document).ready(function() {
        $("#ProductIn_create_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on("change", ".toggle_answer", function() {
            if ($(this).val() == "Yes") {
                $("#customer_exist_panel").slideUp(200);
            } else {
                $("#customer_exist_panel").slideDown(200);
            }
        });

        $(document).on("input", "#ProductIn_agent_code", function() {
            var _url = ajaxUrl + '/misc/find_agent';

            $.post(_url, {aid: $(this).val()}, function(res) {
                if (res.success === true) {
                    $("#Agent_name").val(res.name);
                    $("#Agent_village").val(res.vill);
                    $("#Agent_zila").val(res.dist);
                    $("#Agent_mobile").val(res.mobile);
                } else {
                    clear_agent_info();
                }
            }, "json");
        });

        $(document).on("change", "#customer_id, #ProductIn_customer_id", function() {
            var _url = ajaxUrl + '/misc/find_customer';

            $.post(_url, {cid: $(this).val()}, function(res) {
                if (res.success === true) {
                    $("#Customer_name").val(res.name);
                    $("#Customer_father_name").val(res.father_name);
                    $("#Customer_district").val(res.distid);
                    $("#Customer_thana").val(res.thana);
                    $("#Customer_village").val(res.vill);
                    $("#Customer_mobile").val(res.mobile);
                } else {
                    clear_customer_info();
                }
            }, "json");
        });

        $(document).on("input", ".amount", function(e) {
            get_sum('amount', 'PaymentIn_net_amount');
            e.preventDefault();
        });

        $(document).on("input", "#ProductIn_sr_no, #ProductIn_quantity", function() {
            var _srno = document.getElementById('ProductIn_sr_no').value;
            var _qty = document.getElementById('ProductIn_quantity').value;
            document.getElementById('ProductIn_lot_no').value = _srno + '/' + _qty;
        });
    });
</script>