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
                            <button type="button" id="clear_from" class="btn btn-primary" data-info="/loan/payment_advance_list">Clear</button>
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
                        <th><?php echo Yii::t('strings', 'Type'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Amount'); ?></th>
                        <?php if ($this->hasUserAccess('loan_payment_delete')): ?>
                            <th class="text-center" style="width:3%;"><input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)"></th>
                        <?php endif; ?>
                    </tr>
                    <?php
                    $counter = 0;
                    if (isset($_GET['page']) && $_GET['page'] > 1) {
                        echo $counter = ($_GET['page'] - 1) * $pages->pageSize;
                    }
                    foreach ($dataset as $data):
                        $counter++;
                        //$customer = !empty($data->customer_id) ? Customer::model()->findByPk($data->customer_id) : '';
                        //$agent = !empty($data->agent_code) ? Agent::model()->find("code=:code", [":code" => (string) $data->agent_code]) : '';
                        ?>
                        <tr>
                            <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                            <td style="width:10%;"><?php echo date("j M Y", strtotime($data->created)); ?></td>
                            <td><?php echo!empty($data->type) ? ucwords($data->type) : ''; ?></td>
                            <td class="text-right"><?php echo!empty($data->sumAmount) ? AppHelper::getFloat($data->sumAmount) : ''; ?></td>
                            <?php if ($this->hasUserAccess('loan_payment_delete')): ?>
                                <td class="text-center" style="width:3%;"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"></td>
                            <?php endif; ?>
                        </tr>
                        <?php
                        $sum_amount[] = !empty($data->sumAmount) ? $data->sumAmount : '';
                    endforeach;
                    ?>
                    <tr class="bg_gray">
                        <th colspan="3" class="text-right"><?php echo Yii::t('strings', 'Total'); ?></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_amount)); ?></th>
                        <?php if ($this->hasUserAccess('loan_payment_delete')): ?>
                            <th></th>
                        <?php endif; ?>
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