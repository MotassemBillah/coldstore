<?php
$this->breadcrumbs = array(
    'Agent' => array(AppUrl::URL_AGENT),
    'Ledger'
);
?>
<div class="well">
    <table width="100%">
        <tr>
            <td class="wmd_70">
                <form class="search-form" method="post" name="frmSearch" id="frmSearch">
                    <input type="hidden" id="dataId" name="dtatKey" value="<?php echo $model->_key; ?>">
                    <input type="hidden" name="code_no" value="<?php echo $model->code; ?>">
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
                            <input type="text" id="srno" name="srno" class="form-control" size="25" placeholder="sr number" style="width:13%;">
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_from" class="btn btn-primary"><?php echo Yii::t("strings", "Clear"); ?></button>
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right wmd_30">
                <button type="button" class="btn btn-primary btn-xs" onclick="printDiv('printDiv')"><i class="fa fa-print"></i>&nbsp;<?php echo Yii::t("strings", "Print"); ?></button>
            </td>
        </tr>
    </table>
</div>
<form id="deleteForm" action="" method="post">
    <div id="printDiv">
        <div class="clearfix text-center txt_left_xs mp_center mb_10 show_in_print mp_mt">
            <?php if (!empty($this->settings->logo)) : ?>
                <img alt="" src="<?php echo Yii::app()->request->baseUrl . '/uploads/' . $this->settings->logo; ?>" style="max-height:50px;position:absolute;left:0;top:0;">
            <?php endif; ?>
            <?php if (!empty($this->settings->title)): ?>
                <h1 style="font-size:20px;margin:0;"><?php echo $this->settings->title; ?></h1>
            <?php endif; ?>
            <?php if (!empty($this->settings->author_address)): ?>
                <?php echo $this->settings->author_address; ?><br>
            <?php endif; ?>
            <h3 class="inv_title" style="font-size:18px;"><u><?php echo Yii::t("strings", "Agent Ledger"); ?></u></h3>
        </div>
        <table class="table table-bordered tbl_invoice_view no_mrgn">
            <tr>
                <td><strong>Name : </strong><?php echo $model->name; ?></td>
                <td colspan="2"><strong>Father : </strong><?php echo!empty($model->father_name) ? $model->father_name : ''; ?></td>
                <td><strong>Mobile : </strong><?php echo!empty($model->mobile) ? $model->mobile : ''; ?></td>
                <td colspan="2">
                    <strong>Address : </strong>
                    <?php
                    echo!empty($model->post) ? $model->post : '';
                    echo!empty($model->village) ? ', ' . $model->village : '';
                    echo!empty($model->upozila) ? ', ' . $model->upozila : '';
                    echo!empty($model->zila) ? ', ' . $model->zila : '';
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Code : </strong><?php echo $model->code; ?></td>
                <td><strong>Stock : </strong><?php echo ProductIn::model()->agentStock($model->code); ?></td>
                <td><strong>Empty Bag : </strong><?php echo ProductIn::model()->sumEmptyBag($model->code); ?></td>
                <td><strong>Carrying : </strong><?php echo ProductIn::model()->sumCarryingAgent($model->code); ?></td>
                <td><strong>Loan Given : </strong><?php echo LoanItem::model()->sumTotalAgent($model->code); ?></td>
                <td><strong>AVG Loan : </strong><?php echo LoanItem::model()->avgLoan($model->code); ?></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Delivery Qty : </strong><?php echo DeliveryItem::model()->sumQtyOfAgent($model->code); ?></td>
                <td colspan="3"><strong>Rent Total : </strong><?php echo DeliveryItem::model()->sumTotalAgent($model->code); ?></td>
            </tr>
        </table>
        <div id="ajaxContent">
            <?php if (!empty($dataset) && count($dataset) > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped tbl_invoice_view">
                        <tr class="bg_gray" id="r_checkAll">
                            <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                            <th class="text-center"><?php echo Yii::t('strings', 'SR Number'); ?></th>
                            <th class="text-center"><?php echo Yii::t('strings', 'Qty In'); ?></th>
                            <th class="text-center"><?php echo Yii::t('strings', 'Qty Out'); ?></th>
                            <th class="text-center"><?php echo Yii::t('strings', 'Stock'); ?></th>
                            <th class="text-center"><?php echo Yii::t('strings', 'Empty Bag'); ?></th>
                            <th class="text-center"><?php echo Yii::t('strings', 'Carrying'); ?></th>
                            <th class="text-right"><?php echo Yii::t('strings', 'Loan Amount'); ?></th>
                            <th class="text-right" style=""><?php echo Yii::t('strings', 'Loan Receive'); ?></th>
                            <th class="text-right" style=""><?php echo Yii::t('strings', 'Loan Remain'); ?></th>
                            <th class="text-right" style=""><?php echo Yii::t('strings', 'Interest Receive'); ?></th>
                            <th class="text-right" style=""><?php echo Yii::t('strings', 'Delivery Receive'); ?></th>
                            <th class="text-right" style=""><?php echo Yii::t('strings', 'Total Receive'); ?></th>
                        </tr>
                        <?php
                        $counter = 0;
                        if (isset($_GET['page']) && $_GET['page'] > 1) {
                            $counter = ($_GET['page'] - 1) * $pages->pageSize;
                        }
                        foreach ($dataset as $data):
                            $counter++;
                            $_in = ProductIn::model()->sumQty($data->sr_no);
                            $_out = DeliveryItem::model()->sumQty($data->sr_no);
                            $_stock = AppObject::currentStock($data->sr_no);
                            $_loanRemain = AppHelper::getFloat(AppObject::currentLoan($data->sr_no));
                            $_loanReceive = LoanReceiveItem::model()->sumLoan($data->sr_no);
                            $_intReceive = LoanReceiveItem::model()->sumInterest($data->sr_no);
                            $_delvReceive = DeliveryItem::model()->sumRent($data->sr_no);
                            ?>
                            <tr class="">
                                <td class="text-center"><?php echo $counter; ?></td>
                                <td class="text-center"><?php echo $data->sr_no; ?></td>
                                <td class="text-center"><?php echo $_in; ?></td>
                                <td class="text-center"><?php echo $_out; ?></td>
                                <td class="text-center"><?php echo $_stock; ?></td>
                                <td class="text-center"><?php echo $data->loanbag; ?></td>
                                <td class="text-center"><?php echo $data->carrying_cost; ?></td>
                                <td class="text-right"><?php echo $data->net_amount; ?></td>
                                <td class="text-right"><?php echo $_loanReceive; ?></td>
                                <td class="text-right"><?php echo $_loanRemain; ?></td>
                                <td class="text-right"><?php echo $_intReceive; ?></td>
                                <td class="text-right"><?php echo $_delvReceive; ?></td>
                                <td class="text-right"><?php echo $_totalReceive = ($_delvReceive + $_loanReceive + $_intReceive); ?></td>
                            </tr>
                            <?php
                            $sum_in[] = $_in;
                            $sum_out[] = $_out;
                            $sum_stock[] = $_stock;
                            $sum_loanbag[] = $data->loanbag;
                            $sum_carrying[] = $data->carrying_cost;
                            $sum_net_amount[] = $data->net_amount;
                            $sum_lrec[] = $_loanReceive;
                            $sum_loan_remain[] = $_loanRemain;
                            $sum_intrec[] = $_intReceive;
                            $sum_drec[] = $_delvReceive;
                            $sum_totalReceive[] = $_totalReceive;
                        endforeach;
                        ?>
                        <tr class="bg_gray">
                            <th colspan="2" class="text-right"><?php echo Yii::t("strings", "Total"); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_in); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_out); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_stock); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_loanbag); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_carrying); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_net_amount); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_lrec); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_loan_remain); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_intrec); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_drec); ?></th>
                            <th class="text-right"><?php echo array_sum($sum_totalReceive); ?></th>
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
<script type="text/javascript">
    $(document).ready(function() {
        $("#from_date, #to_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on('click', '#search', function(e) {
            showLoader("Processing...", true);
            var _form = $("#frmSearch");
            var _id = $("#dataId").val();

            $.ajax({
                type: "POST",
                url: baseUrl + "/agent/search_ledger/id/" + _id,
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