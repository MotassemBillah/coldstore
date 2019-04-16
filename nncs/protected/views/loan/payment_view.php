<?php
$this->breadcrumbs = array(
    'Loan' => array(AppUrl::URL_LOAN),
    'Payment View'
);
$agent = !empty($model->items[0]->agent_code) ? Agent::model()->find('code=:cd', [':cd' => $model->items[0]->agent_code]) : '';
?>
<div class="content-panel">
    <div id="printDiv">
        <div class="clearfix text-center txt_left_xs mp_center mb_10 mp_mt">
            <?php if (!empty($this->settings->logo)) : ?>
                <img alt="" src="<?php echo Yii::app()->request->baseUrl . '/uploads/' . $this->settings->logo; ?>" style="max-height:50px;position:absolute;left:0;top:0;">
            <?php endif; ?>
            <?php if (!empty($this->settings->title)): ?>
                <h1 style="font-weight: 600;font-size: 20px;margin: 0;"><?php echo $this->settings->title; ?></h1>
            <?php endif; ?>
            <?php if (!empty($this->settings->author_address)): ?>
                <span class=""><?php echo $this->settings->author_address; ?></span><br>
            <?php endif; ?>
            <h4 class="inv_title">কোল্ড স্টোরেজে আলু / আলু বীজ রাখিয়া ঋণ গ্রহণ পত্র
                <span style="display:block;width:350px;height:1px;background-color:#000;margin:3px auto 0;"></span>
            </h4>
        </div>

        <div class="clearfix mb_10">
            <div class="col-md-4 mpw_33">
                লোন কেস নং :- <?php echo $model->case_no; ?>
            </div>
            <div class="col-md-4 mpw_33 pull-right text-right">
                তারিখ :- <?php echo date("j M Y", strtotime($model->created)); ?>
            </div>
        </div>

        <div class="clearfix mb_10">
            <div class="col-md-5 mpw_50">
                <table class="table customer_info in_info_tbl" style="background-color:#f7f7f7;margin:0 0 1px;">
                    <tr>
                        <td style="width:50%;">এজেন্টের নাম-</td>
                        <td><?php echo!empty($agent) ? $agent->name : ''; ?></td>
                    </tr>
                    <tr>
                        <td style="width:50%;">পিতার নামঃ-</td>
                        <td><?php echo!empty($agent) ? $agent->father_name : ''; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-7 mpw_50 pull-right">
                <table class="table customer_info in_info_tbl" style="background-color:#f7f7f7;margin:0 0 1px;">
                    <tr>
                        <td style="width:12%">গ্রামঃ-</td>
                        <td style="width:38%"><?php echo!empty($agent) ? $agent->village : ''; ?></td>
                        <td style="width:12%">উপজেলাঃ-</td>
                        <td style="width:38%"><?php echo!empty($agent) ? $agent->upozila : ''; ?></td>
                    </tr>
                    <tr>
                        <td style="width:12%">জেলাঃ-</td>
                        <td style="width:38%"><?php echo!empty($agent) ? $agent->zila : ''; ?></td>
                        <td style="width:12%">মোবাইলঃ-</td>
                        <td style="width:38%"><?php echo!empty($agent) ? $agent->mobile : ''; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-md-12">
            <?php $totalAmount = $model->total_loan_amount; ?>

            <table class="table table-striped table-bordered tbl_invoice_view">
                <tr class="bg_gray">
                    <th class="text-center" style="width:3%">SL#</th>
                    <th style="width:12%">Date</th>
                    <th style="width:17%">Customer</th>
                    <th style="width:5%">SR Number</th>
                    <th style="width:5%" class="text-center">Agent Code</th>
                    <th class="text-center" style="width:5%">Loan Bag</th>
                    <th class="text-center" style="width:5%">L.B Price</th>
                    <th class="text-right" style="width:10%">L.B.P Total</th>
                    <th class="text-center" style="width:5%">Qty</th>
                    <th class="text-center" style="width:6%">Qty Price</th>
                    <th class="text-right" style="width:10%">Total</th>
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
                            <td><?php echo date("j M Y", strtotime($item->create_date)); ?></td>
                            <td><?php echo $item->customer->name; ?></td>
                            <td><?php echo $item->sr_no; ?></td>
                            <td class="text-center"><?php echo!empty($item->agent_code) ? $item->agent_code : ''; ?></td>
                            <td class="text-center"><?php echo $item->loanbag; ?></td>
                            <td class="text-center"><?php echo $item->loanbag_cost; ?></td>
                            <td class="text-right"><?php echo AppHelper::getFloat($item->loanbag_cost_total); ?></td>
                            <td class="text-center"><?php echo $item->qty; ?></td>
                            <td class="text-center"><?php echo $item->qty_cost; ?></td>
                            <td class="text-right"><?php echo AppHelper::getFloat($item->qty_cost_total); ?></td>
                            <td class="text-center dis_print">
                                <a class="btn btn-primary btn-xs" href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOAN_PAYMENT_VIEW_SINGLE, ['id' => $item->id]); ?>">view</a>
                            </td>
                        </tr>
                        <?php
                        $sum_loanbag[] = $item->loanbag;
                        $sum_loanbag_price[] = $item->loanbag_cost;
                        $sum_loanbag_price_total[] = $item->loanbag_cost_total;
                        $sum_qty[] = $item->qty;
                        $sum_amount[] = $item->qty_cost_total;
                    endforeach;
                    ?>
                    <tr class="bg_gray">
                        <th colspan="5">Sum Total</th>
                        <th class="text-center"><?php echo array_sum($sum_loanbag); ?></th>
                        <th></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_loanbag_price_total)); ?></th>
                        <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                        <th></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_amount)); ?></th>
                        <th class="dis_print"></th>
                    </tr>
                <?php else : ?>
                    <tr>
                        <td colspan="7">No Loan.</td>
                    </tr>
                <?php endif; ?>
            </table>
			
			<?php $avgCostPerQty = AppHelper::getFloat(array_sum($sum_amount) / array_sum($sum_qty)); ?>

            <div class="clearfix mb_10" style="font-size:12pt;">
                উল্লিখিত কোল্ড স্টোরেজে সংরক্ষিত আলুর উপরোক্ত রশিদ নং সমূহের উপর প্রতি বস্তা  <input type="text" value="<?php echo $avgCostPerQty; ?>" readonly style="width:100px;text-align:center;"> টাকা হারে মোট (অংকে) <input type="text" value="<?php echo!empty($model->items) ? array_sum($sum_amount) : ''; ?>" readonly style="width:100px;text-align:center;"> কথায় <u> <?php echo AppHelper::int_to_words(array_sum($sum_amount)); ?></u> টাকা ঋণ গ্রহণ করিলাম.
            </div>

            <div class="clearfix mb_10" style="font-size:12pt;">
                ঋণের টাকা ব্যাংককে দেয় <?php echo AppHelper::en2bn($loanSetting->interest_rate); ?>% সুদ ও বীমাকৃত অন্যান্য দেয় টাকার পড়তা অনুযায়ী সমস্ত টাকা ১৫ই  অগাস্ট এর মধ্যে পরিশোধ করিয়া আমার আলু সংরক্ষণ রশিদ ফেরত লইব . সময় মত ঋণের
                টাকা, সুদ ও অন্যান্য দেয় টাকা পরিশোধ করিতে না পারিলে সংরক্ষিত আলুর কোন দাবী দাওয়া
                করিতে পারিব না . যদি কোন সময় দাবী করি তাহা আইন আদালতে অগ্রাহ্য বিবেচিত হইবে
                <br><br>
                আলুর রশিদ যথাক্রমে কোল্ড স্টোরেজ সংরক্ষণ প্রাপ্য ভাড়া জমা দিয়া বস্তাসহ আলু যথাসময়ে ফেরত লইব
            </div>
        </div>

        <div class="clearfix">
            <div class="col-md-4 form-group mpw_33" style="padding-top:30px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "ঋণ গ্রহণকারী"); ?></div>
            </div>
            <div class="col-md-4 form-group mpw_33" style="padding-top:30px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "হিসাবরক্ষক"); ?></div>
            </div>
            <div class="col-md-4 form-group mpw_33" style="padding-top:30px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "ব্যবস্থাপক/কর্তৃপক্ষ"); ?></div>
            </div>
        </div>
    </div>
    <div class="clearfix">
        <div class="form-group text-center">
            <button type="button" class="btn btn-primary btn-xs" onclick="printDiv('printDiv')"><i class="fa fa-print"></i>&nbsp;<?php echo Yii::t("strings", "Print"); ?></button>
        </div>
    </div>
</div>