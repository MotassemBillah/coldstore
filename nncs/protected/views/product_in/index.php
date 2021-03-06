<?php
$this->breadcrumbs = array(
    'Product In'
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
                            <div class="col-md-2 col-sm-2 no_pad" style="width: 15%">
                                <?php
                                $proTypeList = ProductType::model()->getList();
                                $type_list = CHtml::listData($proTypeList, 'id', 'name');
                                echo CHtml::dropDownList('type', 'type', $type_list, array('empty' => 'Type', 'class' => 'form-control'));
                                ?>
                            </div>
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
                            <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="customer" size="25" style="width:12%;">
                            <input type="text" name="srno" id="sr" class="form-control" placeholder="sr number" size="25" style="width:10%;">
                            <input type="text" name="agent" id="agent" class="form-control" placeholder="agent code" size="25" style="width:10%;">
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_from" class="btn btn-primary" data-info="/product_in">Clear</button>
                        </div>
                    </div>
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
                        <select id="sortBy" class="form-control" name="sort_by">
                            <option value="create_date" style="text-transform:capitalize">Date</option>
                            <option value="customer_id" style="text-transform:capitalize">Customer</option>
                            <option value="sr_no" style="text-transform:capitalize" selected>SR No</option>
                            <option value="quantity" style="text-transform:capitalize">Quantity</option>
                            <option value="agent_code" style="text-transform:capitalize">Agent</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-3 no_pad">
                        <select id="sortType" class="form-control" name="sort_type">
                            <option value="ASC">Ascending</option>
                            <option value="DESC">Descending</option>
                        </select>
                    </div>
                    <label for="office_code" style="margin: 7px 10px 0;"><input type="checkbox" id="office_code" name="office_code" value="0">&nbsp;Office Code</label>
                </form>
            </td>
            <td class="text-right wmd_30">
                <?php if ($this->hasUserAccess('entry_create')): ?>
                    <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_PRODUCT_IN_CREATE); ?>"><button class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "New"); ?></button></a>
                <?php endif; ?>
                <?php if ($this->hasUserAccess('entry_delete')): ?>
                    <button type="button" class="btn btn-danger btn-xs" id="admin_del_btn" disabled="disabled"><i class="fa fa-trash-o"></i>&nbsp;<?php echo Yii::t("strings", "Delete"); ?></button>
                <?php endif; ?><br>
                <button type="button" class="btn btn-primary btn-xs" onclick="printDiv('printDiv')"><i class="fa fa-print"></i>&nbsp;<?php echo Yii::t("strings", "Print"); ?></button>
            </td>
        </tr>
    </table>
</div>
<form id="deleteForm" action="" method="post">
    <div id="printDiv">
        <div class="form-group clearfix text-center txt_left_xs mp_center media_print show_in_print mp_mt">
            <?php if (!empty($this->settings->title)): ?>
                <h1 style="font-size: 30px;margin: 0;"><?php echo $this->settings->title; ?></h1>
            <?php endif; ?>
            <?php if (!empty($this->settings->author_address)): ?>
                <?php echo $this->settings->author_address; ?><br>
            <?php endif; ?>
            <h4 class="inv_title"><u><?php echo Yii::t("strings", "Product Entry List"); ?></u></h4>
        </div>
        <div id="ajaxContent">
            <?php if (!empty($dataset) && count($dataset) > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped tbl_invoice_view">
                        <tr class="bg_gray">
                            <th colspan="12">
                                <?php echo Yii::t("strings", "Total Quantity") . " = " . ProductIn::model()->sumTotal(); ?>
                                <span style="font-weight:400;margin-left:20px">[Prepared By : <?php echo $display_name; ?>&nbsp;{ stock = <?php echo $userStock; ?> }]</span>
                            </th>
                        </tr>
                        <tr class="bg_gray" id="r_checkAll">
                            <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                            <th style="width:10%;"><?php echo Yii::t("strings", "Date"); ?></th>
                            <th style="width:15%;"><?php echo Yii::t("strings", "Customer"); ?></th>
                            <th style="width:15%;"><?php echo Yii::t("strings", "Father Name"); ?></th>
                            <th><?php echo Yii::t("strings", "Address"); ?></th>
                            <th style="width:10%;"><?php echo Yii::t("strings", "Type"); ?></th>
                            <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "SR No"); ?></th>
                            <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "Qty"); ?></th>
                            <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Loan Bag"); ?></th>
                            <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Agent Code"); ?></th>
                            <th class="text-center dis_print" style="width:9%;"><?php echo Yii::t("strings", "Actions"); ?></th>
                            <th class="text-center dis_print" style="width:3%;">
                                <?php if ($this->hasUserAccess('entry_delete')): ?>
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
                            <tr class="">
                                <td class="text-center"><?php echo $counter; ?></td>
                                <td><?php echo date("j M Y", strtotime($data->create_date)); ?></td>
                                <td><?php echo $data->customer->name; ?></td>
                                <td><?php echo!empty($data->customer->father_name) ? $data->customer->father_name : ""; ?></td>
                                <td>
                                    <?php echo!empty($data->customer->village) ? $data->customer->village : ""; ?>
                                    <?php echo!empty($data->customer->thana) ? ", " . $data->customer->thana : ""; ?>
                                    <?php echo!empty($data->customer->district) ? ", " . $data->customer->district : ""; ?>
                                </td>
                                <td><?php echo!empty($data->type) ? ProductType::model()->findByPk($data->type)->name : ""; ?></td>
                                <td class="text-center"><?php echo $data->sr_no; ?></td>
                                <td class="text-center"><?php echo $data->quantity; ?></td>
                                <td class="text-center"><?php echo $data->loan_pack; ?></td>
                                <td class="text-center"><?php echo $data->agent_code; ?></td>
                                <td class="text-center dis_print">
                                    <?php if ($this->hasUserAccess('entry_view')): ?>
                                        <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_PRODUCT_IN_VIEW, ['id' => $data->_key]); ?>"><?php echo Yii::t("strings", "View"); ?></a>
                                    <?php endif; ?>
                                    <?php if ($this->hasUserAccess('entry_edit')): ?>
                                        <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_PRODUCT_IN_EDIT, ['id' => $data->_key]); ?>"><?php echo Yii::t("strings", "Edit"); ?></a>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center dis_print">
                                    <?php if ($this->hasUserAccess('entry_delete')): ?>
                                        <input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check">
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                            $sum['qty'][] = $data->quantity;
                            $sum['lp'][] = $data->loan_pack;
                        endforeach;
                        ?>
                        <tr class="bg_gray dis_print">
                            <th class="text-right" colspan="7"><?php echo Yii::t("strings", "Total"); ?></th>
                            <th class="text-center"><?php echo array_sum($sum['qty']); ?></th>
                            <th class="text-center"><?php echo array_sum($sum['lp']); ?></th>
                            <th></th>
                            <th class="dis_print" colspan="2"></th>
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