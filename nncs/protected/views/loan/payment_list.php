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
                                for ($i = 10; $i <= 500; $i+=10) {
                                    if ($i > 100)
                                        $i+=40;
                                    if ($i > 200)
                                        $i+=50;
                                    if ($i == $this->settings->page_size) {
                                        echo "<option value='{$i}' selected='selected'>{$i}</option>";
                                    } else {
                                        echo "<option value='{$i}'>{$i}</option>";
                                    }
                                }
                                ?>
                            </select>
                            <?php if (Yii::app()->user->role == AppConstant::ROLE_SUPERADMIN): ?>
                                <div class="col-md-2 col-sm-2 no_pad">
                                    <?php
                                    $userList = User::model()->getList();
                                    $ulist = CHtml::listData($userList, 'id', function($obj) {
                                                return !empty($obj->display_name) ? $obj->display_name : $obj->email;
                                            });
                                    echo CHtml::dropDownList('user', 'user', $ulist, array('empty' => 'User', 'class' => 'form-control', 'style' => 'text-transform:capitalize;'));
                                    ?>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-2 col-sm-3 no_pad">
                                <div class="input-group xsw_100">
                                    <input type="text" id="from_date" class="form-control" name="from_date" placeholder="(dd-mm-yyyy)" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-1 text-center" style="font-size:14px;width: 5%;">
                                <b style="color: rgb(0, 0, 0); vertical-align: middle; display: block; padding: 6px 0px;">TO</b>
                            </div>
                            <div class="col-md-2 col-sm-3 no_pad">
                                <div class="input-group xsw_100">
                                    <input type="text" id="to_date" class="form-control" name="to_date" placeholder="(dd-mm-yyyy)" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <input type="text" name="srno" id="srno" class="form-control" placeholder="sr number" size="30" style="width:12%;">
                            <input type="text" name="agent" id="agent" class="form-control" placeholder="agent" size="30" style="width:10%;">
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_from" class="btn btn-primary" data-info="/loan/payment"><?php echo Yii::t("strings", "Clear"); ?></button>
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right wmd_30" style="position: relative;">
                <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOAN_PAYMENT_CREATE); ?>"><button type="button" class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "New"); ?></button></a>
                <?php if ($this->hasUserAccess('loan_payment_delete')): ?>
                    <button type="button" class="btn btn-danger btn-xs" id="admin_del_btn" disabled="disabled"><i class="fa fa-trash-o"></i>&nbsp;<?php echo Yii::t("strings", "Delete"); ?></button>
                <?php endif; ?>
                <button type="button" class="btn btn-primary btn-xs" onclick="printDiv('printDiv')"><i class="fa fa-print"></i>&nbsp;<?php echo Yii::t("strings", "Print"); ?></button>
            </td>
        </tr>
    </table>
</div>
<form id="deleteForm" action="" method="post">
    <div id="printDiv">
        <div class="row form-group clearfix text-center txt_left_xs mp_center media_print show_in_print mp_mt">
            <?php if (!empty($this->settings->logo)) : ?>
                <img alt="Logo" id="print_logo" src="<?php echo Yii::app()->request->baseUrl . '/uploads/' . $this->settings->logo; ?>" style="max-height:50px;position:absolute;left:0;top:0;">
            <?php endif; ?>
            <?php if (!empty($this->settings->title)): ?>
                <h1 style="font-size: 30px;margin: 0;"><?php echo $this->settings->title; ?></h1>
            <?php endif; ?>
            <?php if (!empty($this->settings->author_address)): ?>
                <?php echo $this->settings->author_address; ?><br>
            <?php endif; ?>
            <h4 class="inv_title"><u><?php echo Yii::t("strings", "Loan Payment List"); ?></u></h4>
        </div>
        <div id="ajaxContent">
            <?php if (!empty($dataset) && count($dataset) > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered tbl_invoice_view">
                        <tr class="bg_gray">
                            <th colspan="7">
                                <span style="font-weight:400;">[Prepared By : <?php echo $display_name; ?>]</span><span style="font-weight:400;margin-left:20px;">[Total Loan Given : <?php echo AppHelper::getFloat(LoanItem::model()->sumTotal()); ?>&nbsp;TK]</span>
                            </th>
                        </tr>
                        <tr id="r_checkAll">
                            <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                            <th style="width:12%;"><?php echo Yii::t('strings', 'Date'); ?></th>
                            <th class="text-center" style="width:12%;"><?php echo Yii::t('strings', 'Loan Number'); ?></th>
                            <th class="text-center" style="width:12%;"><?php echo Yii::t('strings', 'Items'); ?></th>
                            <th class="text-right"><?php echo Yii::t('strings', 'Amount'); ?></th>
                            <th class="text-center dis_print" style="width:10%;"><?php echo Yii::t('strings', 'Actions'); ?></th>
                            <th class="text-center dis_print" style="width:3%;">
                                <?php if ($this->hasUserAccess('loan_payment_delete')): ?>
                                    <input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)">
                                <?php endif; ?>
                            </th>
                        </tr>
                        <?php
                        $counter = 0;
                        if (isset($_GET['page']) && $_GET['page'] > 1) {
                            $counter = ($_GET['page'] - 1) * $pages->pageSize;
                        }
                        foreach ($dataset as $data):
                            $counter++;
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $counter; ?></td>
                                <td><?php echo date("j M Y", strtotime($data->created)); ?></td>
                                <td class="text-center"><?php echo $data->case_no; ?></td>
                                <td class="text-center"><?php echo count($data->items); ?></td>
                                <td class="text-right"><?php echo AppHelper::getFloat($data->sumAmount); ?></td>
                                <td class="text-center dis_print">
                                    <?php if ($this->hasUserAccess('loan_payment_edit')): ?>
                                        <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_LOAN_PAYMENT_EDIT, ['id' => $data->_key]); ?>"><?php echo Yii::t('strings', 'Edit'); ?></a>
                                    <?php endif; ?>
                                    <?php if ($this->hasUserAccess('loan_payment_view')): ?>
                                        <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_LOAN_PAYMENT_VIEW, ['id' => $data->_key]); ?>"><?php echo Yii::t('strings', 'View'); ?></a>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center dis_print">
                                    <?php if ($this->hasUserAccess('loan_payment_delete')): ?>
                                        <input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check">
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                            $sum_net_total[] = $data->sumAmount;
                        endforeach;
                        ?>
                        <tr class="bg_gray">
                            <th colspan="4" class="text-right">Total</th>
                            <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_net_total)); ?></th>
                            <th class="text-center dis_print" colspan="2"></th>
                        </tr>
                    </table>
                </div>

                <div class="paging dis_print">
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
    </div>
</form>