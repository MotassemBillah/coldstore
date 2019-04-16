<?php
$this->breadcrumbs = array(
    'Loan' => array(AppUrl::URL_LOAN),
    'Receive View'
);
?>
<div class="row content-panel">
    <div id="printDiv">
        <div class="clearfix padb_50 mpbb mb_50">
            <div class="clearfix mp_center mb_10 mp_mt">
                <div class="col-md-3 mpw_25">
                    রশিদ নং :- <?php echo $model->reff->receive_number; ?>
                </div>
                <div class="col-md-6 text-center mpw_50">
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
                <div class="col-md-3 mpw_25 pull-right">
                    <table class="table table-bordered in_info_tbl" style="margin: 0;">
                        <tr>
                            <td>ডেলিভারি তারিখঃ <?php echo date("j M Y", strtotime($model->receive_date)); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-12 clearfix mb_10">
                <table class="table table-bordered in_info_tbl mb_10" style="">
                    <tr>
                        <td><strong>সংরক্ষণকারীর নামঃ</strong>&nbsp;&nbsp;<?php echo $model->customer->name; ?></td>
                    </tr>
                    <tr>
                        <td><strong>পিতার নামঃ</strong>&nbsp;&nbsp;<?php echo $model->customer->father_name; ?></td>
                    </tr>
                    <tr>
                        <td><strong>ঠিকানাঃ</strong>&nbsp;&nbsp;<?php echo $model->customer->village . ', ' . $model->customer->thana . ', ' . $model->customer->district; ?></td>
                    </tr>
                </table>

                <div class=" clearfix">
                    <table class="table table-bordered in_info_tbl mb_10">
                        <tr class="bg_gray">
                            <th class="text-center">দলিল নং</th>
                            <th class="text-center">লট নং</th>
                            <th class="text-center">বস্তার পরিমান</th>
                            <th class="text-center">বস্তার লোন</th>
                            <th class="text-center">ডেলিভারি বস্তার ভাড়া</th>
                            <th class="text-center">সার্ভিস চার্জ আদায়&nbsp;(দিন)&nbsp;<?php echo $model->loan_days; ?></th>
                            <th class="text-center">মোট টাকা</th>
                        </tr>
                        <tr>
                            <td class="text-center"><?php echo $model->sr_no ?></td>
                            <td class="text-center"><?php echo $model->lot_no ?></td>
                            <td class="text-center"><?php echo $model->qty; ?></td>
                            <td class="text-center"><?php echo $model->cost_per_qty; ?></td>
                            <td class="text-center"><?php echo $model->loan_amount; ?></td>
                            <td class="text-center"><?php echo $model->interest_amount; ?></td>
                            <td class="text-center"><?php echo $totalAmountReceived = ($model->loan_amount + $model->interest_amount); ?></td>
                        </tr>
                    </table>
                </div>

                <span>কথায়ঃ&nbsp;&nbsp;<?php echo AppHelper::int_to_words($totalAmountReceived); ?> tk only</span>
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
    </div>
    <div class="clearfix">
        <div class="form-group text-center">
            <button type="button" class="btn btn-primary btn-xs" onclick="printDiv('printDiv')"><i class="fa fa-print"></i>&nbsp;<?php echo Yii::t("strings", "Print"); ?></button>
        </div>
    </div>
</div>