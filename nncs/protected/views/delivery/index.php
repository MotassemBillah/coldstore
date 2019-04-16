<?php
$this->breadcrumbs = array(
    'Delivery'
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
                            <div class="col-md-1 col-sm-1 text-center" style="font-size:14px;width: 5%;">
                                <b style="color: rgb(0, 0, 0); vertical-align: middle; display: block; padding: 6px 0px;">TO</b>
                            </div>
                            <div class="col-md-2 col-sm-3 no_pad">
                                <div class="input-group xsw_100">
                                    <input type="text" id="to_date" class="form-control" name="to_date" placeholder="(dd-mm-yyyy)" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <input type="number" name="srno" id="srno" class="form-control" placeholder="sr number" size="25" style="width:12%;">
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_btn" class="btn btn-primary">Clear</button>
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right wmd_30">
                <?php if ($this->hasUserAccess('delivery_create')): ?>
                    <a class="btn btn-success btn-xs" href="<?php echo Yii::app()->createUrl(AppUrl::URL_DELIVERY_CREATE); ?>"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "New"); ?></a>
                <?php endif; ?>
                <?php if ($this->hasUserAccess('delivery_delete')): ?>
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
            <?php if (!empty($this->settings->title)): ?>
                <h1 style="font-size: 30px;margin: 0;"><?php echo $this->settings->title; ?></h1>
            <?php endif; ?>
            <?php if (!empty($this->settings->author_address)): ?>
                <?php echo $this->settings->author_address; ?><br>
            <?php endif; ?>
            <h4 class="inv_title"><u><?php echo Yii::t("strings", "Delivery List"); ?></u></h4>
        </div>
        <table class="table table-bordered tbl_invoice_view no_mrgn">
            <tr>
                <td>Total amount of delivery received up to date( <?php echo date("j M Y", strtotime(date("Y-m-d"))); ?> ) = <u><?php echo AppHelper::getFloat(DeliveryItem::model()->sumTotal()); ?></u> TK</td>
            </tr>
        </table>
        <div id="ajaxContent">
            <?php if (!empty($dataset) && count($dataset) > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered tbl_invoice_view">
                        <tr class="bg_gray" id="r_checkAll">
                            <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                            <th style="width:12%;"><?php echo Yii::t("strings", "Date"); ?></th>
                            <th class="text-center" style="width:12%;"><?php echo Yii::t("strings", "SR Number"); ?></th>
                            <th class="text-center" style="width:12%;"><?php echo Yii::t("strings", "Quantity"); ?></th>
                            <th class="text-center" style="width:15%;"><?php echo Yii::t('strings', 'Author'); ?></th>
                            <th class="text-right"><?php echo Yii::t("strings", "Amount"); ?></th>
                            <th class="text-center dis_print" style="width:10%;"><?php echo Yii::t("strings", "Actions"); ?></th>
                            <?php if ($this->hasUserAccess('delivery_delete')): ?>
                                <th class="text-center dis_print" style="width:3%;"><input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)"></th>
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
                                <td class="text-center"><?php echo $counter; ?></td>
                                <td><?php echo date("j M Y", strtotime($data->delivery_date)); ?></td>
                                <td class="text-center"><?php echo $data->sr_no; ?></td>
                                <td class="text-center"><?php echo $data->sumQty; ?></td>
                                <td class="text-center"><?php echo User::model()->displayname($data->created_by); ?></td>
                                <td class="text-right"><?php echo AppHelper::getFloat($data->sumAmount); ?></td>
                                <td class="text-center dis_print">
                                    <?php if ($this->hasUserAccess('delivery_view')): ?>
                                        <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_DELIVERY_VIEW, ['id' => $data->_key]); ?>"><?php echo Yii::t('strings', 'View'); ?></a>
                                    <?php endif; ?>
                                    <?php if ($this->hasUserAccess('delivery_edit')): ?>
                                        <a class="btn btn-info btn-xs" href="#"><?php echo Yii::t("strings", "Edit"); ?></a>
                                    <?php endif; ?>
                                </td>
                                <?php if ($this->hasUserAccess('delivery_delete')): ?>
                                    <td class="text-center dis_print"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"></td>
                                <?php endif; ?>
                            </tr>
                            <?php
                            $sum_qty_total[] = $data->sumQty;
                            $sum_net_total[] = $data->sumAmount;
                        endforeach;
                        ?>
                        <tr class="bg_gray">
                            <th colspan="3" class="text-right">Total</th>
                            <th class="text-center"><?php echo array_sum($sum_qty_total); ?></th>
                            <th></th>
                            <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_net_total)); ?></th>
                            <th class="text-center dis_print"></th>
                            <?php if ($this->hasUserAccess('delivery_delete')): ?>
                                <th class="text-center dis_print"></th>
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
                <div class="alert alert-info">No records found!</div>
            <?php endif; ?>
        </div>
    </div>
</form>