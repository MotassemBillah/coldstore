<?php
$this->breadcrumbs = array(
    'Delivery Item List'
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
                            <input type="text" name="customer" id="srno" class="form-control" placeholder="customer" size="25" style="width:12%;">
                            <input type="text" name="srno" id="srno" class="form-control" placeholder="sr" size="25" style="width:10%;">
                            <input type="text" name="agent" id="srno" class="form-control" placeholder="agent" size="25" style="width:8%;">
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_from" class="btn btn-primary">Clear</button>
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right wmd_30">
                <?php if ($this->hasUserAccess('delivery_create')): ?>
                    <a class="btn btn-success btn-xs" href="<?php echo Yii::app()->createUrl(AppUrl::URL_DELIVERY_CREATE); ?>"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "New"); ?></a>
                <?php endif; ?>
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
            <h4 class="inv_title"><u><?php echo Yii::t("strings", "Delivery List"); ?></u></h4>
        </div>
        <div id="ajaxContent">
            <?php if (!empty($dataset) && count($dataset) > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered tbl_invoice_view">
                        <tr class="bg_gray" id="r_checkAll">
                            <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                            <th><?php echo Yii::t("strings", "Date"); ?></th>
                            <th><?php echo Yii::t("strings", "Customer"); ?></th>
                            <th><?php echo Yii::t("strings", "Father Name"); ?></th>
                            <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "SR No"); ?></th>
                            <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "Lot No"); ?></th>
                            <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Qty"); ?></th>
                            <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Rent"); ?></th>
                            <th class="text-right" style="width:7%;"><?php echo Yii::t("strings", "Amount"); ?></th>
                            <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "E. Bag"); ?></th>
                            <th class="text-right" style="width:6%;"><?php echo Yii::t("strings", "E.B Tk"); ?></th>
                            <th class="text-right" style="width:6%;"><?php echo Yii::t("strings", "Carrying"); ?></th>
                            <th class="text-right" style="width:6%;"><?php echo Yii::t("strings", "Fanning"); ?></th>
                            <th class="text-right" style="width:6%;"><?php echo Yii::t("strings", "Discount"); ?></th>
                            <th class="text-right" style="width:6%;"><?php echo Yii::t("strings", "Total"); ?></th>
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
                                <td><?php echo date("d-m-Y", strtotime($data->delivery_date)); ?></td>
                                <td><?php echo $data->customer->name; ?></td>
                                <td><?php echo $data->customer->father_name; ?></td>
                                <td class="text-center"><?php echo $data->sr_no; ?></td>
                                <td class="text-center"><?php echo $data->lot_no; ?></td>
                                <td class="text-center"><?php echo $data->quantity; ?></td>
                                <td class="text-center"><?php echo $data->rent; ?></td>
                                <td class="text-right"><?php echo $data->rent_total; ?></td>
                                <td class="text-center"><?php echo $data->loan_bag; ?></td>
                                <td class="text-right"><?php echo $data->loan_bag_price_total; ?></td>
                                <td class="text-right"><?php echo $data->carrying; ?></td>
                                <td class="text-right"><?php echo $data->fan_charge_total; ?></td>
                                <td class="text-right"><?php echo $data->discount; ?></td>
                                <td class="text-right"><?php echo $data->net_total; ?></td>
                            </tr>
                            <?php
                            $sum_qty[] = $data->quantity;
                            $sum_rent_total[] = $data->rent_total;
                            $sum_loan_bag_qty[] = $data->loan_bag;
                            $sum_loan_bag_amount[] = $data->loan_bag_price_total;
                            $sum_carrying[] = $data->carrying;
                            $sum_fan_charge_total[] = $data->fan_charge_total;
                            $sum_discount[] = $data->discount;
                            $sum_net_total[] = $data->net_total;
                        endforeach;
                        ?>
                        <tr class="bg_gray">
                            <th class="text-right" colspan="6">Total</th>
                            <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                            <th></th>
                            <th class="text-right"><?php echo array_sum($sum_rent_total); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_loan_bag_qty); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_loan_bag_amount); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_carrying); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_fan_charge_total); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_discount); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_net_total); ?></th>
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
<script type="text/javascript">
    $(document).ready(function() {
        $("#from_date, #to_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on('click', '#search', function(e) {
            showLoader("Processing...", true);
            var _form = $("#frmSearch");

            $.ajax({
                type: "POST",
                url: baseUrl + "/delivery/search_item_list",
                data: _form.serialize(),
                success: function(res) {
                    showLoader("", false);
                    $("#ajaxContent").html('');
                    $("#ajaxContent").html(res);
                }
            });
            e.preventDefault();
        });
    });
</script>