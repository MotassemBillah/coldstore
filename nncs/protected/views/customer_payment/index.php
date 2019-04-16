<?php
$this->breadcrumbs = array(
    'Payments' => array(AppUrl::URL_PAYMENT),
    'Customer',
);
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
                            <input type="number" id="srno" name="srno" class="form-control" placeholder="sr number" style="width:12%;">
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_from" class="btn btn-primary" data-info="/customer/payment">Clear</button>
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right">
                <div class="dropdown navbar-right" style="margin-right: 0px;">
                    <button class="btn btn-success btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "New"); ?>&nbsp;<span class="caret"></span></button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="<?php echo $this->createUrl(AppUrl::URL_CUSTOMER_PAYMENT_CREATE_DELIVERY); ?>">Delivery Payment</a></li>
                        <li><a href="<?php echo $this->createUrl(AppUrl::URL_CUSTOMER_PAYMENT_CREATE_DUE); ?>">Due Payment</a></li>
                        <li><a href="<?php echo $this->createUrl(AppUrl::URL_CUSTOMER_PAYMENT_CREATE_LOAN); ?>">Loan Payment</a></li>
                    </ul>
                </div>
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
                        <th class="text-center" style="width:4%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                        <th style="width:9%;"><?php echo Yii::t('strings', 'Date'); ?></th>
                        <th><?php echo Yii::t('strings', 'Sr No'); ?></th>
                        <th class=""><?php echo Yii::t('strings', 'Invoice No'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Quantity'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Rent'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Total Rent'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Loan Pack'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Pack Cost'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Cost Total'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Dues Paid'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Advance'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'G.Total'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Action'); ?></th>
                    </tr>
                    <?php
                    $counter = 0;
                    if (isset($_GET['page']) && $_GET['page'] > 1) {
                        $counter = ($_GET['page'] - 1) * $pages->pageSize;
                    }
                    foreach ($dataset as $data):
                        $counter++;
                        $gtotal = ($data->loan_bag_amount + $data->delivered_cost_amount + $data->due_paid) - $data->advance_paid;
                        ?>
                        <tr class="">
                            <td class="text-center" style="width:4%;"><?php echo $counter; ?></td>
                            <td style="width:9%;"><?php echo date('j M Y', strtotime($data->created)); ?></td>
                            <td><?php echo $data->sr_no; ?></td>
                            <td><?php echo $data->delivery_sr_no; ?></td>
                            <td class="text-center"><?php echo $data->delivered_qty; ?></td>
                            <td class="text-center"><?php echo $data->delivered_cost; ?></td>
                            <td class="text-right"><?php echo $data->delivered_cost_amount; ?></td>
                            <td class="text-center"><?php echo $data->loan_bag; ?></td>
                            <td class="text-center"><?php echo $data->loan_bag_cost; ?></td>
                            <td class="text-right"><?php echo $data->loan_bag_amount; ?></td>
                            <td class="text-right"><?php echo $data->due_paid; ?></td>
                            <td class="text-right"><?php echo $data->advance_paid; ?></td>
                            <td class="text-right"><?php echo $data->net_amount; ?></td>
                            <td class="text-center">
                                <?php if (!empty($data->delivery_sr_no)) : ?>
                                    <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_PRODUCT_OUT_VIEW, ['id' => $data->delivery_sr_no]); ?>"><?php echo Yii::t("strings", "View"); ?></a>
                                <?php else: ?>
                                    <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_CUSTOMER_PAYMENT_EDIT, ['id' => $data->_key]); ?>"><?php echo Yii::t("strings", "Edit"); ?></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                        $sum_dc_qty[] = $data->delivered_qty;
                        $sum_dc_total[] = $data->delivered_cost_amount;
                        $sum_lpc[] = $data->loan_bag;
                        $sum_lpc_total[] = $data->loan_bag_amount;
                        $sum_paid[] = $data->due_paid;
                        $sum_adv[] = $data->advance_paid;
                        $sum_gt[] = $data->net_amount;
                    endforeach;
                    ?>
                    <tr class="bg_gray">
                        <th colspan="4" class="text-right"><?php echo Yii::t("strings", "Total"); ?></th>
                        <th class="text-center"><?php echo array_sum($sum_dc_qty); ?></th>
                        <th></th>
                        <th class="text-right"><?php echo array_sum($sum_dc_total); ?></th>
                        <th class="text-center"><?php echo array_sum($sum_lpc); ?></th>
                        <th></th>
                        <th class="text-right"><?php echo array_sum($sum_lpc_total); ?></th>
                        <th class="text-right"><?php echo array_sum($sum_paid); ?></th>
                        <th class="text-right"><?php echo array_sum($sum_adv); ?></th>
                        <th class="text-right"><?php echo array_sum($sum_gt); ?></th>
                        <th></th>
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
<script type="text/javascript">
    $(document).ready(function() {
        $("#from_date, #to_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on("click", "#search", function(e) {
            showLoader("Processing...", true);
            var _form = $("#frmSearch");

            $.ajax({
                type: "POST",
                url: ajaxUrl + "/customer/payment",
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