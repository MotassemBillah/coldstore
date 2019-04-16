<?php
$this->breadcrumbs = array(
    $this->module->id => array(AppUrl::URL_LEDGER),
    'Income',
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
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_from" class="btn btn-primary" data-info="/income">Clear</button>
                        </div>
                    </div>
                </form>
            </td>
        </tr>
    </table>
</div>
<form id="deleteForm" action="" method="post">
    <div id="ajaxContent">
        <?php if (!empty($dataset) && count($dataset) > 0) : ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr id="r_checkAll" class="bg_gray">
                        <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                        <th><?php echo Yii::t("strings", "Customer"); ?></th>
                        <th><?php echo Yii::t('strings', 'Date'); ?></th>
                        <th><?php echo Yii::t('strings', 'Method'); ?></th>
                        <th><?php echo Yii::t('strings', 'Purpose'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Amount'); ?></th>
                    </tr>
                    <?php
                    $counter = 0;
                    foreach ($dataset as $data):
                        $counter++;
                        ?>
                        <tr class="">
                            <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                            <td><?php echo AppObject::customerName($data->customer_id); ?></td>
                            <td><?php echo date('j M Y', strtotime($data->pay_date)); ?></td>
                            <td>
                                <?php
                                if ($data->payment_mode == AppConstant::PAYMENT_CHECK) {
                                    echo $data->payment_mode . "<br>";
                                    echo "<u>Bank</u>: " . $data->bank_name . "<br>";
                                    echo "<u>Check No</u>: " . $data->check_no;
                                } else if ($data->payment_mode == AppConstant::PAYMENT_CASH) {
                                    echo "<span style='color:forestgreen'>" . $data->payment_mode . "</span>";
                                } else {
                                    echo "<span style='color:grey'>" . AppConstant::PAYMENT_NO . "</span>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($data->type == AppConstant::TYPE_INVOICE) {
                                    echo "<span style='color:grey'>Invoice Payment</span>";
                                } else if ($data->type == AppConstant::TYPE_ADVANCE) {
                                    echo "<span style='color:grey'>Due Collection</span>";
                                } else {
                                    echo "<span style='color:grey'></span>";
                                }
                                ?>
                            </td>
                            <td class="text-right bg_gray">
                                <?php
                                $balance_amount = ($data->invoice_paid + $data->advance_amount);
                                echo AppHelper::getFloat($balance_amount);
                                ?>
                            </td>
                        </tr>
                        <?php
                        $sum_balance_amount[] = $balance_amount;
                    endforeach;
                    ?>
                    <tr class="bg_gray">
                        <th colspan="4"></th>
                        <th class=""><?php echo Yii::t("strings", "Total Amount"); ?></th>
                        <th colspan="1" class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_balance_amount)); ?></th>
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
            <div class="alert alert-info"><?php echo Yii::t("strings", "No records found!"); ?></div>
        <?php endif; ?>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
    });
</script>