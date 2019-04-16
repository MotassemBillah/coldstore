<?php
$this->breadcrumbs = array(
    'Loan' => array(AppUrl::URL_LOAN),
    'Receive View'
);
?>
<div class="row content-panel">
    <div id="printDiv">
        <div class="clearfix text-center txt_left_xs mp_center mb_10 mp_mt">
            <div class="col-md-3 mpw_25 text-left">
                রশিদ নং :- <?php echo $model->receive_number; ?>
            </div>
            <div class="col-md-6 mpw_50">
                <?php if (!empty($this->settings->logo)) : ?>
                    <img alt="Logo" id="print_logo" src="<?php echo Yii::app()->request->baseUrl . '/uploads/' . $this->settings->logo; ?>" style="max-height:50px;position:absolute;left:0;top:0;">
                <?php endif; ?>
                <span class="">বিসমিল্লাহির রাহমানির রাহীম</span><br>
                <?php if (!empty($this->settings->title)): ?>
                    <h1 style="font-weight: 600;font-size: 20px;margin: 0;"><?php echo $this->settings->title; ?></h1>
                <?php endif; ?>
                <?php if (!empty($this->settings->author_address)): ?>
                    <span class=""><?php echo $this->settings->author_address; ?></span><br>
                <?php endif; ?>
                <?php if (!empty($this->settings->author_mobile)): ?>
                    <span style="">মোবাইল : <?php echo $this->settings->author_mobile; ?></span><br>
                <?php endif; ?>
                <h4 class="inv_title">সংরক্ষিত আলুর লোন আদায় রশিদ - <?php echo date('Y'); ?> ইং
                    <span style="display:block;width:310px;height:1px;background-color:#000;margin:3px auto 0;"></span>
                </h4>
            </div>
            <div class="col-md-3 mpw_25 pull-right text-right">
                তারিখ :- <?php echo date("j M Y", strtotime($model->receive_date)); ?>
            </div>
        </div>

        <div class="col-md-12">
            <table class="table table-striped table-bordered tbl_invoice_view">
                <tr class="bg_gray">
                    <th class="text-center">Total Qty</th>
                    <th class="text-center">Quantity Price</th>
                    <th class="text-center">Received Loan</th>
                    <th class="text-center">Received Interest</th>
                    <th class="text-center">Received Amount</th>
                    <th class="text-center">Received Person</th>
                </tr>
                <tr>
                    <td class="text-center"><?php echo $model->sumQty; ?></td>
                    <td class="text-center"><?php echo $model->items[0]->cost_per_qty; ?></td>
                    <td class="text-center"><?php echo $model->sumLoan; ?></td>
                    <td class="text-center"><?php echo $model->sumInterest; ?></td>
                    <td class="text-center"><?php echo $receivedAmount = ($model->sumLoan + $model->sumInterest); ?></td>
                    <td class="text-center"></td>
                </tr>
            </table>

            <table class="table table-striped table-bordered tbl_invoice_view">
                <tr class="bg_gray">
                    <th class="text-center" style="width:3%">SL#</th>
                    <th style="width:12%">Date</th>
                    <th style="width:17%">Customer</th>
                    <th style="width:5%">SR Number</th>
                    <th style="width:5%" class="text-center">Agent Code</th>
                    <th class="text-center" style="width:5%">Qty</th>
                    <th class="text-center" style="width:6%">Qty Price</th>
                    <th class="text-right" style="width:10%">Loan Amount</th>
                    <th class="text-center" style="width:5%">Day</th>
                    <th class="text-right" style="width:8%">Interest</th>
                    <th class="text-right" style="width:8%">Total</th>
                    <th class="text-center dis_print" style="width:8%">Single</th>
                </tr>
                <?php
                $sum_qty = [];
                $sum_amount = [];
                if (!empty($model->items) && count($model->items) > 0):
                    $counter = 0;
                    foreach ($model->items as $item) :
                        $counter++;
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $counter; ?></td>
                            <td><?php echo date("j M Y", strtotime($item->receive_date)); ?></td>
                            <td><?php echo $item->customer->name; ?></td>
                            <td><?php echo $item->sr_no; ?></td>
                            <td class="text-center"><?php echo!empty($item->agent_code) ? $item->agent_code : ''; ?></td>
                            <td class="text-center"><?php echo $item->qty; ?></td>
                            <td class="text-center"><?php echo $item->cost_per_qty; ?></td>
                            <td class="text-right"><?php echo AppHelper::getFloat($item->loan_amount); ?></td>
                            <td class="text-center"><?php echo $item->loan_days; ?></td>
                            <td class="text-right"><?php echo $item->interest_amount; ?></td>
                            <td class="text-right"><?php echo $item->net_amount; ?></td>
                            <td class="text-center dis_print">
                                <a class="btn btn-primary btn-xs" href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOAN_RECEIVE_VIEW_SINGLE, ['id' => $item->id]); ?>">view</a>
                            </td>
                        </tr>
                        <?php
                        $sum_qty[] = $item->qty;
                        $sum_amount[] = $item->loan_amount;
                        $sum_interest_amount[] = $item->interest_amount;
                        $sum_net_amount[] = $item->net_amount;
                    endforeach;
                    ?>
                    <tr class="bg_gray">
                        <th colspan="5">Sum Total</th>
                        <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                        <th></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_amount)); ?></th>
                        <th></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_interest_amount)); ?></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_net_amount)); ?></th>
                        <th class="dis_print"></th>
                    </tr>
                <?php else : ?>
                    <tr>
                        <td colspan="12">No Data Found.</td>
                    </tr>
                <?php endif; ?>
            </table>

            <span>কথায়ঃ&nbsp;&nbsp;<?php echo AppHelper::int_to_words(array_sum($sum_amount)); ?> tk only</span>
        </div>

        <div class="clearfix">
            <div class="col-md-3 form-group mpw_25" style="padding-top:50px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "গ্রহীতা"); ?></div>
            </div>
            <div class="col-md-3 form-group mpw_25" style="padding-top:50px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "ক্যাশিয়ার"); ?></div>
            </div>
            <div class="col-md-3 form-group mpw_25" style="padding-top:50px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "আদায়কারীর স্বাক্ষর"); ?></div>
            </div>
            <div class="col-md-3 form-group mpw_25" style="padding-top:50px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "হিসাবরক্ষক"); ?></div>
            </div>
        </div>
    </div>
    <div class="clearfix">
        <div class="form-group text-center">
            <button type="button" class="btn btn-primary btn-xs" onclick="printDiv('printDiv')"><i class="fa fa-print"></i>&nbsp;<?php echo Yii::t("strings", "Print"); ?></button>
        </div>
    </div>
</div>