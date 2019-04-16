<?php
$this->breadcrumbs = array(
    'Loan' => $this->createUrl(AppUrl::URL_CUSTOMER_LOAN, ['id' => $model->_key]),
    'Regular'
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
        <input type="hidden" id="customerID" name="customerID" value="<?php echo $model->id; ?>">
        <div class="clearfix">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Loan Payment Information</h3>
                </div>
                <div class="panel-body">
                    <div class="row clearfix">
                        <div class="col-md-2 col-sm-3 no_pad_rgt">
                            <div class="form-group">
                                <label><input type="radio" name="type" value="adv">&nbsp;Advance</label><br>
                                <label><input type="radio" name="type" value="reg">&nbsp;Regular</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3 no_pad">
                            <div class="form-group">
                                <label>SR Number</label>
                                <input type="text" id="sr_number" name="sr_number" class="form-control" placeholder="sr number">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 no_pad">
                            <div class="form-group">
                                <label>Agent Code</label>
                                <input type="text" id="agent_code" name="agent_code" class="form-control" placeholder="agent code">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3 no_pad">
                            <div class="form-group">
                                <label class="hidden-xs" style="display: block">&nbsp;</label>
                                <input type="button" id="search" class="btn btn-info" value="Search">
                                <input type="button" class="btn btn-warning" value="Clear" onclick="clear_search_fields()">
                            </div>
                        </div>
                    </div>
                    <hr style="margin:0px 0 10px;">

                    <div id="ajaxContent"></div>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>