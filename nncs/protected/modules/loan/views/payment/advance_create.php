<?php
$this->breadcrumbs = array(
    'Loan' => array(AppUrl::URL_LOAN),
    'Payment Create'
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
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <label for="type_customer"><input type="radio" class="toggle_customer" id="type_customer" name="customer_type" value="customer" data-target="#customer_panel" checked>&nbsp;Customer</label>
                                <label for="type_agent">&nbsp;<input type="radio" class="toggle_customer" id="type_agent" name="customer_type" value="agent" data-target="#agent_panel">&nbsp;Agent</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <label class="col-md-6 col-xs-6 text-right" for="">Loan Case No</label>
                                <input type="text" id="loan_case_no" name="loan_case_no" class="col-md-6 col-xs-6" required>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group panel_toggle customer_panel" id="customer_panel">
                                <?php
                                $customerList = Customer::model()->getList();
                                $custList = CHtml::listData($customerList, 'id', 'name');
                                echo CHtml::dropDownList('customer', 'customer', $custList, array('empty' => 'Customer', 'class' => 'form-control'));
                                ?>
                            </div>
                            <div class="form-group panel_toggle agent_panel" id="agent_panel" style="display: none;">
                                <input type="text" class="form-control" name="agent" id="agent" placeholder="Agent Code">
                            </div>
                            <div class="form-group customer_panel">
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">Name</label>
                                    <input type="text" id="customer_name" class="col-md-6 col-xs-6" readonly>
                                </div>
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">Father Name</label>
                                    <input type="text" id="customer_father_name" class="col-md-6 col-xs-6" readonly>
                                </div>
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">Village</label>
                                    <input type="text" id="customer_village" class="col-md-6 col-xs-6" readonly>
                                </div>
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">District</label>
                                    <input type="text" id="customer_district" class="col-md-6 col-xs-6" readonly>
                                </div>
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">Mobile</label>
                                    <input type="text" id="customer_mobile" class="col-md-6 col-xs-6" readonly>
                                </div>
                            </div>
                            <div class="form-group agent_panel" style="display: none;">
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">Name</label>
                                    <input type="text" id="agent_name" class="col-md-6 col-xs-6" readonly>
                                </div>
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">Village</label>
                                    <input type="text" id="agent_village" class="col-md-6 col-xs-6" readonly>
                                </div>
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">District</label>
                                    <input type="text" id="agent_zila" class="col-md-6 col-xs-6" readonly>
                                </div>
                                <div class="mb_5 clearfix">
                                    <label class="col-md-6 col-xs-6 text-right" for="">Mobile</label>
                                    <input type="text" id="agent_mobile" class="col-md-6 col-xs-6" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="pay_date" name="pay_date" readonly value="<?php echo date('d-m-Y'); ?>">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <div class="col-md-6 no_pad_lft">
                                    <label>Empty Bag</label>
                                    <input type="text" class="" id="loan_bag" name="loan_bag" style="width:100%;">
                                </div>
                                <div class="col-md-6 no_pad_rgt">
                                    <label>Bag Price</label>
                                    <input type="number" class="" id="loan_bag_price" name="loan_bag_price" min="0" step="any" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Carrying</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="carrying_amount" name="carrying_amount">
                                    <span class="input-group-addon">Tk</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Loan Amount</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="loan_amount" name="loan_amount">
                                    <span class="input-group-addon">Tk</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" id="submit_adv_loan" name="submit_adv_loan" value="Submit">
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

        $(document).on("change", ".toggle_customer", function() {
            if ($(this).val() == "customer") {
                $(".agent_panel").hide();
                $(".customer_panel").show();
                clear_agent_info();
            } else {
                $(".customer_panel").hide();
                $(".agent_panel").show();
                clear_customer_info();
            }
        });

        $(document).on("input", "#agent", function() {
            var _url = ajaxUrl + '/misc/find_agent';

            $.post(_url, {aid: $(this).val()}, function(res) {
                if (res.success === true) {
                    $("#agent_name").val(res.name);
                    $("#agent_village").val(res.vill);
                    $("#agent_zila").val(res.dist);
                    $("#agent_mobile").val(res.mobile);
                } else {
                    clear_agent_info();
                }
            }, "json");
        });

        $(document).on("change", "#customer", function() {
            var _url = ajaxUrl + '/misc/find_customer';

            $.post(_url, {cid: $(this).val()}, function(res) {
                if (res.success === true) {
                    $("#customer_name").val(res.name);
                    $("#customer_father_name").val(res.father_name);
                    $("#customer_district").val(res.dist);
                    $("#customer_village").val(res.vill);
                    $("#customer_mobile").val(res.mobile);
                } else {
                    clear_customer_info();
                }
            }, "json");
        });

//        $(document).on("submit", "#frmCustomerLoan", function() {
//            if ($("#loan_bag").val() != "") {
//                $("#ajaxMessage").showAjaxMessage({html: "Empty bag price required", type: "warning"});
//                return false;
//            } else {
//                return true;
//            }
//        });
    });

    function clear_agent_info() {
        $("#agent_name").val('');
        $("#agent_village").val('');
        $("#agent_zila").val('');
        $("#agent_mobile").val('');
    }

    function clear_customer_info() {
        $("#customer_name").val('');
        $("#customer_father_name").val('');
        $("#customer_district").val('');
        $("#customer_village").val('');
        $("#customer_mobile").val('');
    }
</script>