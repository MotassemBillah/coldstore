<?php
$this->breadcrumbs = array(
    'Payments'
);
?>
<div class="well">
    <table width="100%">
        <tr>
            <td class="wmd_70">
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
                            <?php if (Yii::app()->user->role == AppConstant::ROLE_SUPERADMIN): ?>
                                <div class="col-md-2 col-sm-2 no_pad">
                                    <?php
                                    $userList = User::model()->getList();
                                    $ulist = CHtml::listData($userList, 'id', 'display_name');
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
                            <div class="col-md-1 col-sm-1 text-center" style="font-size:14px;width:5%;">
                                <b style="color: rgb(0, 0, 0); vertical-align: middle; display: block; padding: 6px 0px;">TO</b>
                            </div>
                            <div class="col-md-2 col-sm-3 no_pad">
                                <div class="input-group xsw_100">
                                    <input type="text" id="to_date" class="form-control" name="to_date" placeholder="(dd-mm-yyyy)" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
							<input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="customer" size="25" style="width:12%;">
                            <input type="text" id="srno" name="srno" class="form-control" placeholder="sr number" style="width:15%;">
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_from" class="btn btn-primary" data-info="/payments">Clear</button>
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right wmd_30" style="">
                <?php if ($this->hasUserAccess('payment_delete')): ?>
                    <button type="button" class="btn btn-danger btn-xs" id="admin_del_btn" disabled="disabled" ><i class="fa fa-trash-o"></i>&nbsp;<?php echo Yii::t("strings", "Delete"); ?></button>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>

<form id="deleteForm" action="#" method="post">
    <div id="ajaxContent">
        <?php if (!empty($dataset) && count($dataset) > 0) : ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tr class="bg_gray" id="r_checkAll">
                        <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                        <th><?php echo Yii::t('strings', 'Date'); ?></th>
                        <th><?php echo Yii::t('strings', 'Customer'); ?></th>
                        <th><?php echo Yii::t('strings', 'Sr No'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Advance'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Carrying'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Labor'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Others'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Total'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Actions'); ?></th>
                        <?php if ($this->hasUserAccess('payment_delete')): ?>
                            <th class="text-center" style="width:3%;"><input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)"></th>
                        <?php endif; ?>
                    </tr>
                    <?php
                    $counter = 0;
                    if (isset($_GET['page']) && $_GET['page'] > 1) {
                        $counter = ($_GET['page'] - 1) * $pages->pageSize;
                    }
                    foreach ($dataset as $data):
                        $counter++;
                        ?>
                        <tr class="">
                            <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                            <td><?php echo date('j M Y', strtotime($data->create_date)); ?></td>
                            <td><?php echo $data->customer->name; ?></td>
                            <td><?php echo $data->sr_no; ?></td>
                            <td class="text-right"><?php echo AppHelper::getFloat($data->payment->advance_amount); ?></td>
                            <td class="text-right"><?php echo AppHelper::getFloat($data->payment->carrying_cost); ?></td>
                            <td class="text-right"><?php echo AppHelper::getFloat($data->payment->labor_cost); ?></td>
                            <td class="text-right"><?php echo AppHelper::getFloat($data->payment->other_cost); ?></td>
                            <td class="text-right"><?php echo AppHelper::getFloat($data->payment->net_amount); ?></td>
                            <td class="text-center">
                                <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_PRODUCT_IN_VIEW, ['id' => $data->_key]); ?>" target="_blank"><?php echo Yii::t('strings', 'View'); ?></a>
                            </td>
                            <?php if ($this->hasUserAccess('payment_delete')): ?>
                                <td class="text-center" style="width:3%;"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"/></td>
                            <?php endif; ?>
                        </tr>
                        <?php
                        $sum_advcost[] = $data->payment->advance_amount;
                        $sum_ccost[] = $data->payment->carrying_cost;
                        $sum_lcost[] = $data->payment->labor_cost;
                        $sum_ocost[] = $data->payment->other_cost;
                        $sum_tcost[] = $data->payment->net_amount;
                    endforeach;
                    ?>
                    <tr class="bg_gray">
                        <th colspan="4" class="text-right"><?php echo Yii::t("strings", "Total"); ?></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_advcost)); ?></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_ccost)); ?></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_lcost)); ?></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_ocost)); ?></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_tcost)); ?></th>
                        <th colspan="1"></th>
                        <?php if ($this->hasUserAccess('payment_delete')): ?>
                            <th colspan="1"></th>
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
                    'nextPageLabel' => '> ',
                    'prevPageLabel' => '< ',
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
            <div class="alert alert-info"><?php echo Yii::t('strings', 'No records found!'); ?></div>
        <?php endif; ?>
    </div>
</form>