<?php
$this->breadcrumbs = array(
    'Loan' => array(AppUrl::URL_LOAN),
    'Payment'
);
?>
<div class="well">
    <table width="100%">
        <tr>
            <td class="wmd_70 wxs_100">
                <form class="search-form" method="post" name="frmSearch" id="frmSearch">
                    <div class="input-group">
                        <div class="input-group-btn clearfix">
                            <select id="itemCount" class="form-control" name="itemCount" style="width:55px;">
                                <?php
                                for ($i = 10; $i <= 100; $i+=10) {
                                    if ($i == $this->settings->page_size) {
                                        echo "<option value='{$i}' selected='selected'>{$i}</option>";
                                    } else {
                                        echo "<option value='{$i}'>{$i}</option>";
                                    }
                                }
                                ?>
                            </select>
                            <div class="col-md-2 col-sm-3 no_pad">
                                <?php
                                $customerList = Customer::model()->getList();
                                $custList = CHtml::listData($customerList, 'id', 'name');
                                echo CHtml::dropDownList('customer', 'customer', $custList, array('empty' => 'Customer', 'class' => 'form-control'));
                                ?>
                            </div>
                            <input type="text" name="q" id="q" class="form-control" placeholder="search sr or mobile number" size="30">
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <!--<button type="button" id="clear_from" class="btn btn-primary" data-info="/loan/payment">Clear</button>-->
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right wmd_30" style="position: relative;">
                <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOAN_PAYMENT_ADVANCE_CREATE); ?>"><button type="button" class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "New"); ?></button></a>
                <?php if ($this->hasUserAccess('loan_payment_delete')): ?>
                    <button type="button" class="btn btn-danger btn-xs" id="admin_del_btn" disabled="disabled"><?php echo Yii::t("strings", "Delete"); ?></button>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>
<form id="deleteForm" action="" method="post">
    <div id="ajaxContent">
        <?php if (!empty($dataset) && count($dataset) > 0) : ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tr id="r_checkAll" class="bg_gray">
                        <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                        <th style="width:10%;"><?php echo Yii::t('strings', 'Date'); ?></th>
                        <th><?php echo Yii::t('strings', 'Customer'); ?></th>
                        <th><?php echo Yii::t('strings', 'Agent'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Loan Bag Qty'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Price Per Bag'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Loan Amount'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Total Amount'); ?></th>
                    </tr>
                    <?php
                    $counter = 0;
                    if (isset($_GET['page']) && $_GET['page'] > 1) {
                        echo $counter = ($_GET['page'] - 1) * $pages->pageSize;
                    }
                    foreach ($dataset as $data):
                        $counter++;
                        ?>
                        <tr>
                            <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                            <td style="width:10%;"><?php echo date("j M Y", strtotime($data->created)); ?></td>
                            <td><?php echo!empty($data->customer_id) ? $data->customer->name : ''; ?></td>
                            <td><?php echo!empty($data->agent_code) ? Agent::model()->find('code=:code', [':code' => $data->agent_code])->name : ''; ?></td>
                            <td class="text-center"><?php echo $data->loanbag; ?></td>
                            <td class="text-center"><?php echo $data->cost_per_loanbag; ?></td>
                            <td class="text-right"><?php echo $data->loan_amount; ?></td>
                            <td class="text-right"><?php echo $data->total_loan_amount; ?></td>
                        </tr>
                        <?php
                        $sum_qty[] = $data->loanbag;
                        $sum_amount[] = $data->loan_amount;
                        $sum_total_amount[] = $data->total_loan_amount;
                    endforeach;
                    ?>
                    <tr class="bg_gray">
                        <th colspan="4" class="text-right"><?php echo Yii::t('strings', 'Total'); ?></th>
                        <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                        <th></th>
                        <th class="text-right"><?php echo array_sum($sum_amount); ?></th>
                        <th class="text-right"><?php echo array_sum($sum_total_amount); ?></th>
                    </tr>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No records found!</div>
        <?php endif; ?>
    </div>
</form>