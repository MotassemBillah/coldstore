<?php
$this->breadcrumbs = array(
    'Delivery' => array(AppUrl::URL_DELIVERY),
    'View'
);
?>
<div class="row content-panel">
    <div id="printDiv">
        <div class="clearfix padb_50 mpbb mb_50">
            <div class="clearfix mp_center mb_10 mp_mt">
                <div class="col-md-3 mpw_25">
                    <span>রশিদ নং : <?php echo $deliveryItems[0]->reff->delivery_number; ?></span><br>
                    <span style="font-weight:bold;">দলিল নং : <?php echo $deliveryItems[0]->sr_no; ?></span><br>
                    <span style="font-weight:bold;">লট নং : <?php echo $deliveryItems[0]->lot_no; ?></span><br>
                    <span>সংরক্ষিত বস্তা : <?php echo ProductIn::model()->sumQty($deliveryItems[0]->sr_no); ?></span><br>
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
                    <h4 class="inv_title">সংরক্ষিত আলুর ভাড়া/লোন আদায় রশিদ - <?php echo date('Y'); ?> ইং
                        <span style="display:block;width:350px;height:1px;background-color:#000;margin:3px auto 0;"></span>
                    </h4>
                </div>
                <div class="col-md-3 mpw_25 pull-right">
                    <table class="table table-bordered in_info_tbl" style="margin: 0;">
                        <tr>
                            <td>ইস্যু তারিখঃ <?php echo date("j M Y", strtotime($deliveryItems[count($deliveryItems) - 1]->created)); ?></td>
                        </tr>
                        <tr>
                            <td>ডেলিভারি তারিখঃ <?php echo date("j M Y", strtotime($deliveryItems[count($deliveryItems) - 1]->delivery_date)); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-12 clearfix mb_10">
                <table class="table table-bordered in_info_tbl mb_10" style="">
                    <tr>
                        <td><strong>সংরক্ষণকারীর নামঃ</strong>&nbsp;&nbsp;<?php echo $deliveryItems[0]->customer->name; ?></td>
                    </tr>
                    <tr>
                        <td><strong>পিতার নামঃ</strong>&nbsp;&nbsp;<?php echo $deliveryItems[0]->customer->father_name; ?></td>
                    </tr>
                    <tr>
                        <td><strong>ঠিকানাঃ</strong>&nbsp;&nbsp;<?php echo $deliveryItems[0]->customer->village . ', ' . $deliveryItems[0]->customer->thana . ', ' . $deliveryItems[0]->customer->district; ?></td>
                    </tr>
                </table>

                <div class=" clearfix">
                    <table class="table table-bordered in_info_tbl mb_10">
                        <tr class="bg_gray">
                            <th class="text-center" style="width: 16%">তারিখ</th>
                            <th class="text-center" style="width: 16%">ডেলিভারি বস্তা</th>
                            <th class="text-center" style="width: 10%">অবশিষ্ট বস্তা</th>
                            <th class="text-center" style="width: 10%">বস্তার ভাড়া</th>
                            <th class="text-center" style="width: 16%">ফ্যানিং চার্জ আদায়</th>
                            <th class="text-center" style="width: 16%">ভাড়ার টাকা</th>
                            <th class="text-center" style="width: 16%">মোট টাকা</th>
                        </tr>
                        <?php foreach ($deliveryItems as $ditem) : ?>
                            <tr>
                                <td class="text-center"><?php echo date('d-m-Y', strtotime($ditem->delivery_date)); ?></td>
                                <td class="text-center"><?php echo $ditem->quantity; ?></td>
                                <td class="text-center"><?php echo $ditem->cur_qty; ?></td>
                                <td class="text-center"><?php echo $ditem->rent; ?></td>
                                <td class="text-center"><?php echo $ditem->fan_charge; ?></td>
                                <td class="text-center"><?php echo $ditem->rent_total; ?></td>
                                <td class="text-center"><?php echo $totalDamount = ($ditem->fan_charge + $ditem->rent_total); ?></td>
                            </tr>
                            <?php
                            $sum_qty[] = $ditem->quantity;
                            $sum_fanc[] = $ditem->fan_charge;
                            $sum_total_rent[] = $ditem->rent_total;
                            $sum_total[] = $totalDamount;
                        endforeach;
                        ?>
                        <tr class="bg_gray">
                            <th></th>
                            <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                            <th colspan="2"></th>
                            <th class="text-center"><?php echo array_sum($sum_fanc); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_total_rent); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_total); ?></th>
                        </tr>
                    </table>

                    <table class="table table-bordered in_info_tbl mb_10">
                        <tr class="bg_gray">
                            <th class="text-center" style="">তারিখ</th>
                            <th class="text-center" style="">আদায়কৃত বস্তা</th>
                            <th class="text-center" style="">অবশিষ্ট বস্তা</th>
                            <th class="text-center" style="">বস্তার লোন</th>
                            <th class="text-center" style="">নগদ লোন আদায়</th>
                            <th class="text-center" style="">মোট দিন</th>
                            <th class="text-center" style="">সার্ভিস চার্জ আদায়</th>
                            <th class="text-center" style="">মোট টাকা</th>
                        </tr>
                        <?php if (!empty($loanReceiveItems) && count($loanReceiveItems) > 0) : ?>
                            <?php foreach ($loanReceiveItems as $lrItem) : ?>
                                <tr>
                                    <td class="text-center"><?php echo date('d-m-Y', strtotime($lrItem->receive_date)); ?></td>
                                    <td class="text-center"><?php echo $lrItem->qty; ?></td>
                                    <td class="text-center"><?php echo $lrItem->cur_qty; ?></td>
                                    <td class="text-center"><?php echo $lrItem->cost_per_qty; ?></td>
                                    <td class="text-center"><?php echo $lrItem->loan_amount; ?></td>
                                    <td class="text-center"><?php echo $lrItem->loan_days; ?></td>
                                    <td class="text-center"><?php echo $lrItem->interest_amount; ?></td>
                                    <td class="text-center"><?php echo $totalAmountReceived = ($lrItem->loan_amount + $lrItem->interest_amount); ?></td>
                                </tr>
                                <?php
                                $sum_lrqty[] = $lrItem->qty;
                                $sum_lrtotal[] = $lrItem->loan_amount;
                                $sum_inta[] = $lrItem->interest_amount;
                                $sum_totalRec[] = $totalAmountReceived;
                            endforeach;
                            ?>
                            <tr class="bg_gray">
                                <th></th>
                                <th class="text-center"><?php echo array_sum($sum_lrqty); ?></th>
                                <th colspan="2"></th>
                                <th class="text-center"><?php echo array_sum($sum_lrtotal); ?></th>
                                <th></th>
                                <th class="text-center"><?php echo array_sum($sum_inta); ?></th>
                                <th class="text-center"><?php echo array_sum($sum_totalRec); ?></th>
                            </tr>
                        <?php else : ?>
                            <tr><td colspan='8'>No Loan On this SR Number.</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
                <?php $totalAmountVal = (array_sum($sum_total) + (!empty($loanReceiveItems) ? array_sum($sum_totalRec) : 0)); ?>
                <span>কথায়ঃ&nbsp;&nbsp;<?php echo AppHelper::int_to_words($totalAmountVal); ?> [ <?php echo $totalAmountVal; ?> ] tk only</span>
            </div>

            <div class="clearfix">
                <div class="col-md-3 form-group mpw_25" style="padding-top:100px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "গ্রহীতা"); ?></div>
                </div>
                <div class="col-md-3 form-group mpw_25" style="padding-top:100px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "ক্যাশিয়ার"); ?></div>
                </div>
                <div class="col-md-3 form-group mpw_25" style="padding-top:100px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "আদায়কারীর স্বাক্ষর"); ?></div>
                </div>
                <div class="col-md-3 form-group mpw_25" style="padding-top:100px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "হিসাবরক্ষক"); ?></div>
                </div>
            </div>
        </div>

        <div class="clearfix ">
            <div class="clearfix mp_center mb_10">
                <div class="col-md-3 mpw_25">
                    <span>রশিদ নং : <?php echo $deliveryItems[0]->reff->delivery_number; ?></span><br>
                    <span style="font-weight:bold;">দলিল নং : <?php echo $deliveryItems[0]->sr_no; ?></span><br>
                    <span style="font-weight:bold;">লট নং : <?php echo $deliveryItems[0]->lot_no; ?></span><br>
                    <span>সংরক্ষিত বস্তা : <?php echo ProductIn::model()->sumQty($deliveryItems[0]->sr_no); ?></span><br>
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
                    <h4 class="inv_title">সংরক্ষিত আলুর ভাড়া/লোন আদায় রশিদ - <?php echo date('Y'); ?> ইং
                        <span style="display:block;width:350px;height:1px;background-color:#000;margin:3px auto 0;"></span>
                    </h4>
                    <h4 class="inv_title" style="font-weight:bold;">গেট পাশ
                        <span style="display:block;width:60px;height:1px;background-color:#000;margin:3px auto 0;"></span>
                    </h4>
                </div>
                <div class="col-md-3 mpw_25 pull-right">
                    <table class="table table-bordered in_info_tbl" style="margin: 0;">
                        <tr>
                            <td>ইস্যু তারিখঃ <?php echo date("j M Y", strtotime($deliveryItems[count($deliveryItems) - 1]->created)); ?></td>
                        </tr>
                        <tr>
                            <td>ডেলিভারি তারিখঃ <?php echo date("j M Y", strtotime($deliveryItems[count($deliveryItems) - 1]->delivery_date)); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-12 clearfix mb_10">
                <table class="table table-bordered" style="font-size:16px;margin-bottom:20px;">
                    <tr>
                        <td><strong>সংরক্ষণকারীর নামঃ</strong>&nbsp;&nbsp;<?php echo $deliveryItems[0]->customer->name; ?></td>
                    </tr>
                    <tr>
                        <td><strong>পিতার নামঃ</strong>&nbsp;&nbsp;<?php echo $deliveryItems[0]->customer->father_name; ?></td>
                    </tr>
                    <tr>
                        <td><strong>ঠিকানাঃ</strong>&nbsp;&nbsp;<?php echo $deliveryItems[0]->customer->village . ', ' . $deliveryItems[0]->customer->thana . ', ' . $deliveryItems[0]->customer->district; ?></td>
                    </tr>
                </table>

                <div class="row clearfix">
                    <div class="col-md-6 mpw_50">
                        <table class="table table-bordered mb_10" style="font-size:16px;">
                            <tr class="bg_gray">
                                <th class="text-center" style="width:50%">লট নং</th>
                                <th class="text-center" style="width:50%">ডেলিভারি বস্তা</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="height:100px"><?php echo $deliveryItems[count($deliveryItems) - 1]->lot_no; ?></th>
                                <th class="text-center" style="height:100px"><?php echo $deliveryItems[count($deliveryItems) - 1]->quantity; ?></th>
                            </tr>
                        </table>
                        <span style="text-transform: capitalize;">ডেলিভারি বস্তার সংখ্যাঃ&nbsp;&nbsp;<?php echo AppHelper::int_to_words($deliveryItems[count($deliveryItems) - 1]->quantity); ?></span>
                    </div>
                    <div class="col-md-6 mpw_50 mp_no_pad_rgt">
                        <table class="table table-bordered mb_10" style="font-size:16px;">
                            <tr class="bg_gray">
                                <th class="text-center" style="width:25%">চেম্বার</th>
                                <th class="text-center" style="width:25%">তলা</th>
                                <th class="text-center" style="width:25%">পকেট</th>
                                <th class="text-center" style="width:25%">সংখ্যা</th>
                            </tr>
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <tr>
                                    <td style="height:33px;"></td>
                                    <td style="height:33px;"></td>
                                    <td style="height:33px;"></td>
                                    <td style="height:33px;"></td>
                                </tr>
                            <?php endfor; ?>
                        </table>
                    </div>
                </div>
            </div>

            <div class="clearfix">
                <div class="col-md-4 form-group mpw_33" style="padding-top:100px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "আদায়কারীর স্বাক্ষর"); ?></div>
                </div>
                <div class="col-md-4 form-group mpw_33" style="padding-top:100px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "হিসাবরক্ষক"); ?></div>
                </div>
                <div class="col-md-4 form-group mpw_33" style="padding-top:100px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "স্টোর কিপার"); ?></div>
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