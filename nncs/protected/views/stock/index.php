<?php
$this->breadcrumbs = array('Stocks');
?>
<div class="well">
    <table width="100%">
        <tr>
            <td class="">
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
                            <div class="col-md-2 col-sm-2 no_pad" style="width: 10%">
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
                            <div class="col-md-1 col-sm-1 md5 xsw_100 text-center">
                                <b style="color: rgb(0, 0, 0); vertical-align: middle; display: block; padding: 6px 0px;">TO</b>
                            </div>
                            <div class="col-md-2 col-sm-3 no_pad">
                                <div class="input-group xsw_100">
                                    <input type="text" id="to_date" class="form-control" name="to_date" placeholder="(dd-mm-yyyy)" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="customer" size="25" style="width:10%;">
                            <input type="text" name="sr_no" id="sr_no" class="form-control" placeholder="sr number" size="25" style="width:9%">
                            <input type="text" name="agent" id="agent" class="form-control" placeholder="agent" size="25" style="width:6%">
                            <button type="button" id="search" class="btn btn-info btn_xs_block"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_from" class="btn btn-warning btn_xs_block" data-info="/stock">Clear</button>
                            <button type="button" class="btn btn-primary" onclick="printDiv('printDiv')"><i class="fa fa-print"></i>&nbsp;<?php echo Yii::t("strings", "Print"); ?></button>
                        </div>
                    </div>
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
                        <select id="sortBy" class="form-control" name="sort_by">
                            <option value="create_date" style="text-transform:capitalize">Date</option>
                            <option value="customer_id" style="text-transform:capitalize">Customer</option>
                            <option value="sr_no" style="text-transform:capitalize" selected>SR No</option>
                            <option value="quantity" style="text-transform:capitalize">Quantity</option>
                            <option value="type" style="text-transform:capitalize">Type</option>
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
        </tr>
    </table>
</div>
<form id="deleteForm" action="" method="post">
    <div id="printDiv">
        <div class="form-group clearfix text-center txt_left_xs mp_center media_print show_in_print mp_mt">
            <?php if (!empty($this->settings->title)): ?>
                <h1 style="font-size:20px;margin:0;"><?php echo $this->settings->title; ?></h1>
            <?php endif; ?>
            <?php if (!empty($this->settings->author_address)): ?>
                <?php echo $this->settings->author_address; ?><br>
            <?php endif; ?>
            <h4 class="inv_title" style="font-size:17px;"><u><?php echo Yii::t("strings", "Stock List"); ?></u></h4>
        </div>
        <div id="ajaxContent">
            <?php if (!empty($dataset) && count($dataset) > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped tbl_invoice_view">
                        <tr class="bg_gray">
                            <th colspan="13">
                                <?php echo Yii::t("strings", "Total Stock") . " = " . AppObject::sumStock(); ?>
                                <span style="margin-left:20px">Total Loan Pack : <?php echo ProductIn::model()->totalLoanPackGiven(); ?></span>
                                <span style="font-weight:400;margin-left:20px">[ Prepared By : <?php echo $display_name; ?>&nbsp;{ stock = <?php echo $userStock; ?> }]</span>
                            </th>
                        </tr>
                        <tr class="bg_gray" id="r_checkAll">
                            <th class="text-center" style="width:4%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                            <th><?php echo Yii::t("strings", "Date"); ?></th>
                            <th><?php echo Yii::t("strings", "Customer"); ?></th>
                            <th class="text-center" style="width:7%;"><?php echo Yii::t("strings", "SR No"); ?></th>
                            <th><?php echo Yii::t("strings", "Type"); ?></th>
                            <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "In"); ?></th>
                            <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "Out"); ?></th>
                            <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "Stock"); ?></th>
                            <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Loan Bag"); ?></th>
                            <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Bag Paid"); ?></th>
                            <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Bag Remain"); ?></th>
                            <th style="width:15%"><?php echo Yii::t("strings", "Pallot"); ?></th>
                            <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Agent Code"); ?></th>
                        </tr>
                        <?php
                        $counter = 0;
                        if (isset($_GET['page']) && $_GET['page'] > 1) {
                            $counter = ($_GET['page'] - 1) * $pages->pageSize;
                        }
                        foreach ($dataset as $data):
                            $counter++;
                            $agent = !empty($data->agent_code) ? Agent::model()->find('code=:code', [':code' => $data->agent_code]) : '';
                            ?>
                            <tr class="">
                                <td class="text-center"><?php echo $counter; ?></td>
                                <td><?php echo date("j M Y", strtotime($data->create_date)); ?></td>
                                <td><?php echo $data->customer->name; ?></td>
                                <td class="text-center"><?php echo!empty($data->sr_no) ? $data->sr_no : ""; ?></td>
                                <td><?php echo!empty($data->type) ? ProductType::model()->findByPk($data->type)->name : ""; ?></td>
                                <td class="text-center"><?php echo!empty($data->quantity) ? $data->quantity : ""; ?></td>
                                <td class="text-center"><?php echo AppObject::stockOut($data->sr_no); ?></td>
                                <td class="text-center"><?php echo AppObject::currentStock($data->sr_no); ?></td>
                                <td class="text-center"><?php echo AppObject::loanPackIn($data->sr_no); ?></td>
                                <td class="text-center"><?php echo AppObject::loanPackOut($data->sr_no); ?></td>
                                <td class="text-center"><?php echo AppObject::loanPackStock($data->sr_no); ?></td>
                                <td></td>
                                <td class="text-center"><?php echo!empty($agent) ? $agent->code : ''; ?></td>
                            </tr>
                            <?php
                            $sum['in'][] = $data->quantity;
                            $sum['out'][] = AppObject::stockOut($data->sr_no);
                            $sum['total'][] = AppObject::currentStock($data->sr_no);
                            $sum['lp_in'][] = AppObject::loanPackIn($data->sr_no);
                            $sum['lp_out'][] = AppObject::loanPackOut($data->sr_no);
                            $sum['lp_total'][] = AppObject::loanPackStock($data->sr_no);
                        endforeach;
                        ?>
                        <tr class="bg_gray dis_print">
                            <th class="text-right" colspan="5"><?php echo Yii::t("strings", "Total"); ?></th>
                            <th class="text-center"><?php echo array_sum($sum['in']); ?></th>
                            <th class="text-center"><?php echo array_sum($sum['out']); ?></th>
                            <th class="text-center"><?php echo array_sum($sum['total']); ?></th>
                            <th class="text-center"><?php echo array_sum($sum['lp_in']); ?></th>
                            <th class="text-center"><?php echo array_sum($sum['lp_out']); ?></th>
                            <th class="text-center"><?php echo array_sum($sum['lp_total']); ?></th>
                            <th></th>
                            <th></th>
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
<div class="modal fade" id="containerForLocation" tabindex="-1" role="dialog" aria-labelledby="containerForLocationLabel"></div>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", ".frm_open", function(e) {
            showLoader("Processing...", true);
            var _id = $(this).attr('data-info');
            var _info = $(this).attr('data-url');
            var _url = ajaxUrl + '/stock/location_form?did=' + _id + "&info=" + _info;

            $("#containerForLocation").load(_url, function() {
                $("#containerForLocation").modal({
                    backdrop: 'static',
                    keyboard: false
                });
                showLoader("", false);
            });
            e.preventDefault();
        });
    });
</script>