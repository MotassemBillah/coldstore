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
                            <button type="button" id="clear_from" class="btn btn-primary" data-info="/loan/pending_list">Clear</button>
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right wmd_30" style="position: relative;">
                <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOAN_PAYMENT_CREATE); ?>"><button type="button" class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "New"); ?></button></a>
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
                <table class="table table-bordered table-hover">
                    <tr id="r_checkAll">
                        <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                        <th style="width:10%;"><?php echo Yii::t('strings', 'Date'); ?></th>
                        <th><?php echo Yii::t('strings', 'Customer'); ?></th>
                        <th><?php echo Yii::t('strings', 'Sr No'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Quantity'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Loan Per Qty'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Period'); ?> (days)</th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Total Amount'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Status'); ?></th>
                        <!--<th class="text-center"><?php // echo Yii::t('strings', 'Actions');    ?></th>-->
                        <?php // if ($this->hasUserAccess('loan_payment_delete')): ?>
                            <!--<th class="text-center" style="width:3%;"><input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)"/></th>-->
                        <?php // endif; ?>
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
                            <td style="width:10%;"><?php echo date("j M Y", strtotime($data->create_date)); ?></td>
                            <td><?php echo $data->customer->name; ?></td>
                            <td><?php echo $data->sr_no; ?></td>
                            <td class="text-center"><?php echo $data->quantity; ?></td>
                            <td class="text-center"><?php echo $data->cost_per_qty; ?></td>
                            <td class="text-center"><?php echo $data->loan_period; ?></td>
                            <td class="text-right"><?php echo $data->loan_amount; ?></td>
                            <td class="text-center">
                                <?php
                                if ($data->status == AppConstant::ORDER_COMPLETE) {
                                    echo "<span class='label label-success'>{$data->status}</span>";
                                } else {
                                    echo "<span class='label label-warning'>{$data->status}</span>";
                                }
                                ?>
                            </td>
        <!--                            <td class="text-center">
                            <?php // if ($this->hasUserAccess('loan_payment_edit')): ?>
                                    <a class="btn btn-info btn-xs" href="<?php // echo $this->createUrl(AppUrl::URL_LOAN_PAYMENT_EDIT, ['id' => $data->_key]);    ?>"><?php // echo Yii::t('strings', 'Edit');    ?></a>
                            <?php // endif; ?>
                            </td>-->
                            <?php // if ($this->hasUserAccess('loan_payment_delete')): ?>
                                <!--<td class="text-center" style="width:3%;"><input type="checkbox" name="data[]" value="<?php // echo $data->id;    ?>" class="check"/></td>-->
                            <?php // endif; ?>
                        </tr>
                        <?php
                        $sum_qty[] = $data->quantity;
                        $sum_rent[] = $data->cost_per_qty;
                        $sum_loan_amount[] = $data->loan_amount;
                    endforeach;
                    ?>
                    <tr class="bg_gray">
                        <th colspan="4" class="text-right"><?php echo Yii::t('strings', 'Total'); ?></th>
                        <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                        <th colspan="2"></th>
                        <th class="text-right"><?php echo array_sum($sum_loan_amount); ?></th>
                        <th colspan="2"></th>
                        <?php // if ($this->hasUserAccess('loan_payment_delete')): ?>
                            <!--<th></th>-->
                        <?php // endif; ?>
                    </tr>
                </table>
            </div>

            <div class="paging">
                <?php
                $this->widget('CLinkPager', array(
                    'pages' => $pages,
                    'header' => ' ',
                    'firstPageLabel' => '<<',
                    'lastPageLabel' => '>>',
                    'nextPageLabel' => '>',
                    'prevPageLabel' => '<',
                    'selectedPageCssClass' => 'active ',
                    'hiddenPageCssClass' => 'disabled ',
                    'maxButtonCount' => 10,
                    'htmlOptions' => array(
                        'class' => 'pagination',
                    )
                ));
                ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No records found!</div>
        <?php endif; ?>
    </div>
</form>