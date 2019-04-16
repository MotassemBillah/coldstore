<?php
$this->breadcrumbs = array(
    'Loan' => array(AppUrl::URL_LOAN),
    'Payment Invoice'
);
?>
<div class="content-panel">
    <div id="printDiv">
        <div class="clearfix text-center txt_left_xs mp_center mb_10 mp_mt">
            <?php if (!empty($this->settings->logo)) : ?>
                <img alt="Logo" id="print_logo" src="<?php echo Yii::app()->request->baseUrl . '/uploads/' . $this->settings->logo; ?>" style="max-height:50px;position:absolute;left:0;top:0;">
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
                ক্রমিক নং :- <?php echo AppHelper::en2bn($model->sr_no); ?>/<?php echo AppHelper::en2bn($model->qty); ?>
            </div>
            <div class="col-md-4 mpw_33 pull-right text-right">
                তারিখ :- <?php echo date("j M Y", strtotime($model->create_date)); ?>
            </div>
        </div>

        <div class="clearfix mb_10">
            <div class="col-md-5 mpw_50">
                <table class="table customer_info in_info_tbl" style="background-color:#f7f7f7;margin:0 0 1px;">
                    <tr>
                        <td style="width:50%;">সংরক্ষণকারীর নামঃ-</td>
                        <td><?php echo $model->customer->name; ?></td>
                    </tr>
                    <tr>
                        <td style="width:50%;">পিতার নামঃ-</td>
                        <td><?php echo $model->customer->father_name; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-7 mpw_50 pull-right">
                <table class="table customer_info in_info_tbl" style="background-color:#f7f7f7;margin:0 0 1px;">
                    <tr>
                        <td style="width:12%">গ্রামঃ-</td>
                        <td style="width:38%"><?php echo $model->customer->village; ?></td>
                        <td style="width:12%">উপজেলাঃ-</td>
                        <td style="width:38%"><?php echo!empty($model->customer->thana) ? $model->customer->thana : ''; ?></td>
                    </tr>
                    <tr>
                        <td style="width:12%">জেলাঃ-</td>
                        <td style="width:38%"><?php echo!empty($model->customer->district) ? $model->customer->district : ''; ?></td>
                        <td style="width:12%">মোবাইলঃ-</td>
                        <td style="width:38%"><?php echo $model->customer->mobile; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-md-12">
            <table class="table table-bordered in_info_tbl mb_10">
                <tr class="bg_gray">
                    <th class="text-center">বস্তার সংখ্যা</th>
                    <th class="text-center">আলুর জাত</th>
                    <th class="text-center">প্রতিবস্তার ভাড়া</th>
                    <th class="text-center">পরিবহন ভাড়া</th>
                    <th class="text-center">খালি বস্তার পরিমাণ</th>
                </tr>
                <tr>
                    <td class="text-center">
                        <?php
                        $stock_qty = AppObject::stockIn($model->sr_no);
                        echo $stock_qty . "<br>";
                        echo ucwords(AppHelper::int_to_words($stock_qty));
                        ?>
                    </td>
                    <td class="text-center"><?php echo!empty($product->type) ? ProductType::model()->findByPk($product->type)->name : ''; ?></td>
                    <td class="text-center"><?php echo ''; ?></td>
                    <td class="text-center"><?php echo $product->payment->net_amount; ?></td>
                    <td class="text-center"><?php echo $product->loan_pack; ?></td>
                </tr>
            </table>

            <table class="table table-bordered table-striped reguler_tbl">
                <tr class="bg_gray">
                    <th>Date</th>
                    <th>Customer</th>
                    <th>SR Number</th>
                    <th class="text-center">Agent Code</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Loan Per Qty</th>
                    <th class="text-right">Total</th>
                </tr>
                <?php if (!empty($model)): ?>
                    <tr>
                        <td><?php echo date("j M Y", strtotime($model->create_date)); ?></td>
                        <td><?php echo $model->customer->name; ?></td>
                        <td><?php echo $model->sr_no; ?></td>
                        <td class="text-center"><?php echo!empty($model->agent_code) ? $model->agent_code : ''; ?></td>
                        <td class="text-center"><?php echo $model->qty; ?></td>
                        <td class="text-center"><?php echo $model->qty_cost; ?></td>
                        <td class="text-right"><?php echo AppHelper::getFloat($model->net_amount); ?></td>
                    </tr>
                    <tr class="bg_gray">
                        <th colspan="4">Sum Total</th>
                        <th class="text-center"><?php echo $model->qty; ?></th>
                        <th></th>
                        <th class="text-right"><?php echo AppHelper::getFloat($model->net_amount); ?></th>
                    </tr>
                <?php else : ?>
                    <tr>
                        <td colspan="6">No Loan.</td>
                    </tr>
                <?php endif; ?>
            </table>

            <div class="clearfix mb_10" style="font-size:12pt;">
                উল্লিখিত কোল্ড স্টোরেজে সংরক্ষিত আলুর উপরোক্ত রশিদ নং সমূহের উপর প্রতি বস্তা  <input type="text" value="<?php echo!empty($model) ? $model->qty_cost : ''; ?>" readonly style="width:100px;text-align:center;"> টাকা হারে মোট (অংকে) <input type="text" value="<?php echo!empty($model) ? AppHelper::getFloat($model->net_amount) : ''; ?>" readonly style="width:100px;text-align:center;"> কথায় <u> <?php echo AppHelper::int_to_words($model->net_amount); ?></u> টাকা ঋণ গ্রহণ করিলাম.
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