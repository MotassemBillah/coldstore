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
                            <div class="col-md-2 col-sm-3 no_pad">
                                <select id="order" class="form-control" name="order">
                                    <option value="delivery_date ASC">Date Ascending</option>
                                    <option value="delivery_date DESC">Date Descending</option>
                                </select>
                            </div>
                            <input type="text" name="srno" id="srno" class="form-control" placeholder="sr" size="25" style="width:10%;">
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
        <div class="form-group clearfix text-center txt_left_xs mp_center media_print show_in_print mp_mt">
            <?php if (!empty($this->settings->title)): ?>
                <h1 style="font-size: 30px;margin: 0;"><?php echo $this->settings->title; ?></h1>
            <?php endif; ?>
            <?php if (!empty($this->settings->author_address)): ?>
                <?php echo $this->settings->author_address; ?><br>
            <?php endif; ?>
            <h4 class="inv_title"><u><?php echo Yii::t("strings", "Delivery Report"); ?></u></h4>
        </div>
        <div id="ajaxContent">
            <?php if (!empty($dataset) && count($dataset) > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered tbl_invoice_view">
                        <tr>
                            <th colspan="16">
                                <span style="display:inline-block;">রিপোর্টিং তারিখ</span>
                                <span style="display:inline-block;margin-left:25px;"><?php echo date('d-m-y', strtotime($dataset[0]->delivery_date)); ?></span>
                                <span style="display:inline-block;margin:0 25px;">To</span>
                                <span style="display:inline-block;"><?php echo date('d-m-y', strtotime($dataset[count($dataset) - 1]->delivery_date)); ?></span>
                            </th>
                        </tr>
                        <tr class="bg_gray" id="r_checkAll">
                            <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                            <th class="text-center"><?php echo Yii::t("strings", "Receipt No"); ?></th>
                            <th class="text-center"><?php echo Yii::t("strings", "SR No"); ?></th>
                            <th><?php echo Yii::t("strings", "Customer"); ?></th>
                            <th class="text-center"><?php echo Yii::t("strings", "Bag"); ?></th>
                            <th class="text-right"><?php echo Yii::t("strings", "Rent"); ?></th>
                            <th class="text-right" style=""><?php echo Yii::t("strings", "Loan C"); ?></th>
                            <th class="text-right" style=""><?php echo Yii::t("strings", "Service C"); ?></th>
                            <th class="text-center" style=""><?php echo Yii::t("strings", "E.Bag Qty"); ?></th>
                            <th class="text-right" style=""><?php echo Yii::t("strings", "E.Bag TK"); ?></th>
                            <th class="text-right" style=""><?php echo Yii::t("strings", "Carrying"); ?></th>
                            <th class="text-right" style=""><?php echo Yii::t("strings", "Fanning"); ?></th>
                            <th class="text-right" style=""><?php echo Yii::t("strings", "Others"); ?></th>
                            <th class="text-right" style=""><?php echo Yii::t("strings", "Total"); ?></th>
                            <th class="text-center dis_print" style="width:8%;"><?php echo Yii::t("strings", "Actions"); ?></th>
                            <th class="text-center dis_print" style="width:3%;">
                                <?php if ($this->hasUserAccess('delivery_delete')): ?>
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
                            $_loan = LoanReceiveItem::model()->loanByDelivery($data->delivery_number);
                            $_interest = LoanReceiveItem::model()->interestByDelivery($data->delivery_number);
                            $_others = 0;
                            ?>
                            <tr class="">
                                <td class="text-center"><?php echo $counter; ?></td>
                                <td class="text-center"><?php echo $data->reff->delivery_number; ?></td>
                                <td class="text-center"><?php echo $data->sr_no; ?></td>
                                <td><?php echo $data->customer->name; ?></td>
                                <td class="text-center"><?php echo $data->quantity; ?></td>
                                <td class="text-right"><?php echo $data->rent_total; ?></td>
                                <td class="text-right"><?php echo $_loan; ?></td>
                                <td class="text-right"><?php echo $_interest; ?></td>
                                <td class="text-center"><?php echo $data->loan_bag; ?></td>
                                <td class="text-right"><?php echo $data->loan_bag_price_total; ?></td>
                                <td class="text-right"><?php echo $data->carrying; ?></td>
                                <td class="text-right"><?php echo $data->fan_charge_total; ?></td>
                                <td class="text-right"><?php echo $_others; ?></td>
                                <td class="text-right"><?php echo $_total = ($data->net_total + $_loan + $_interest + $_others); ?></td>
                                <td class="text-center dis_print">
                                    <?php if ($this->hasUserAccess('delivery_view')): ?>
                                        <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_DELIVERY_VIEW, ['id' => $data->reff->_key]); ?>"><?php echo Yii::t('strings', 'View'); ?></a>
                                    <?php endif; ?>
                                    <?php if ($this->hasUserAccess('delivery_edit')): ?>
                                        <a class="btn btn-info btn-xs" href="#"><?php echo Yii::t("strings", "Edit"); ?></a>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center dis_print">
                                    <?php if ($this->hasUserAccess('delivery_delete')): ?>
                                        <input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check">
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                            $sum_qty[] = $data->quantity;
                            $sum_rent_total[] = $data->rent_total;
                            $sum_loan[] = $_loan;
                            $sum_interest[] = $_interest;
                            $sum_loan_bag[] = $data->loan_bag;
                            $sum_loan_bag_amount[] = $data->loan_bag_price_total;
                            $sum_carrying[] = $data->carrying;
                            $sum_fan_charge_total[] = $data->fan_charge_total;
                            $sum_net_total[] = $_total;
                        endforeach;
                        ?>
                        <tr class="bg_gray">
                            <th class="text-right" colspan="4">Total</th>
                            <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_rent_total); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_loan); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_interest); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_loan_bag); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_loan_bag_amount); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_carrying); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_fan_charge_total); ?></th>
                            <th class="text-right"></th>
                            <th class="text-right"><?php echo array_sum($sum_net_total); ?></th>
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
                url: baseUrl + "/delivery/search_report_detail",
                data: _form.serialize(),
                success: function(res) {
                    showLoader("", false);
                    $("#ajaxContent").html('');
                    $("#ajaxContent").html(res);
                }
            });
            e.preventDefault();
        });

        $(document).on('click', '#admin_del_btn', function(e) {
            var _rc = confirm('Are you sure about this action? This cannot be undone!');

            if (_rc === true) {
                showLoader("Processing...", true);
                var _form = $("#deleteForm");
                var _url = baseUrl + '/delivery/deleteall';

                $.post(_url, _form.serialize(), function(res) {
                    if (res.success === true) {
                        $("#ajaxMessage").showAjaxMessage({html: res.message, type: 'success'});
                        $("tr.bg-danger").remove();
                        $("#search").trigger('click');
                    } else {
                        $("#ajaxMessage").showAjaxMessage({html: res.message, type: 'error'});
                    }
                    reset_index();
                    showLoader("", false);
                }, "json");
            } else {
                return false;
            }
            e.preventDefault();
        });
    });
</script>