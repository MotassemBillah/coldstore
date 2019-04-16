<?php
$this->breadcrumbs = array(
    'SR' => array(AppUrl::URL_SR),
    'Detail'
);
?>
<div class="row content-panel">
    <div id="printDiv">
        <div class="clearfix padb_20 mb_50">
            <div class="clearfix mp_center mb_10 mp_mt">
                <div class="col-md-3 mpw_25 text-center">
                    <span style="display:inline-block;font-size:16px;font-weight:600;margin-top:65px;padding:2px 0;width:230px;">এস আর নং</span><br>
                    <span style="display:inline-block;font-size:20px;font-weight:600;padding:2px 0;width:230px;"><?php echo AppHelper::en2bn($srinfo->lot_no); ?></span>
                </div>
                <div class="col-md-6 text-center mpw_50">
                    <?php if (!empty($this->settings->logo)) : ?>
                        <img alt="Logo" id="print_logo" src="<?php echo Yii::app()->request->baseUrl . '/uploads/' . $this->settings->logo; ?>" style="max-height:50px;position:absolute;left:0;top:0;">
                    <?php endif; ?>
                    <span class="">বিসমিল্লাহির রাহমানির রাহীম</span><br>
                    <?php if (!empty($this->settings->title)): ?>
                        <h1 style="font-weight:600;font-size:20px;margin:0;"><?php echo $this->settings->title; ?></h1>
                    <?php endif; ?>
                    <?php if (!empty($this->settings->author_address)): ?>
                        <span class=""><?php echo $this->settings->author_address; ?></span><br>
                    <?php endif; ?>
                    <?php if (!empty($this->settings->author_mobile)): ?>
                        <span style="">মোবাইল : <?php echo $this->settings->author_mobile; ?></span><br>
                    <?php endif; ?>
                    <h4 class="inv_title" style="font-size:16px;">সংরক্ষিত আলুর ভাড়া/লোন আদায় রশিদ - <?php echo date('Y'); ?> ইং
                        <span style="display:block;width:300px;height:1px;background-color:#000;margin:3px auto 0;"></span>
                    </h4>
                </div>
                <div class="col-md-3 mpw_25 pull-right">
                    <table class="table table-bordered in_info_tbl" style="margin: 0;">
                        <tr>
                            <th>বিবরণ</th>
                            <th class="text-center">স্টক</th>
                            <th class="text-center">উত্তোলন</th>
                            <th class="text-center">অবশিষ্ট</th>
                        </tr>
                        <tr>
                            <td>বস্তা</td>
                            <td class="text-center"><?php echo $srinfo->quantity; ?></td>
                            <td class="text-center"><?php echo DeliveryItem::model()->sumQty($srinfo->sr_no); ?></td>
                            <td class="text-center"><?php echo AppObject::currentStock($srinfo->sr_no); ?></td>
                        </tr>
                        <tr>
                            <td>লোন</td>
                            <td class="text-center"><?php echo LoanItem::model()->sumTotal($srinfo->sr_no); ?></td>
                            <td class="text-center"><?php echo LoanReceiveItem::model()->sumLoan($srinfo->sr_no); ?></td>
                            <td class="text-center"><?php echo AppObject::currentLoan($srinfo->sr_no); ?></td>
                        </tr>
                        <tr>
                            <td colspan="4">ডেলিভারি তারিখঃ <?php echo!empty($deliveryItems) ? date("j M Y", strtotime($deliveryItems[count($deliveryItems) - 1]->delivery_date)) : ''; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-12 clearfix mb_10">
                <table class="table table-bordered tbl_invoice_view mb_10">
                    <tr>
                        <td><strong>সংরক্ষণকারীর নামঃ</strong>&nbsp;&nbsp;<?php echo $srinfo->customer->name; ?></td>
                    </tr>
                    <tr>
                        <td><strong>পিতার নামঃ</strong>&nbsp;&nbsp;<?php echo $srinfo->customer->father_name; ?></td>
                    </tr>
                    <tr>
                        <td><strong>ঠিকানাঃ</strong>&nbsp;&nbsp;<?php echo $srinfo->customer->village . ', ' . $srinfo->customer->thana . ', ' . $srinfo->customer->district; ?></td>
                    </tr>
                </table>

                <table class="table table-bordered tbl_invoice_view mb_10" style="margin-bottom:10px;">
                    <tr class="">
                        <th class="text-center">বস্তার সংখ্যা</th>
                        <th class="text-center">আলুর জাত</th>
                        <th class="text-center">প্রতিবস্তার ভাড়া</th>
                        <th class="text-center">প্রদত্ত লোন</th>
                        <th class="text-center">তারিখ</th>
                        <th class="text-center">পরিবহন ভাড়া</th>
                        <th class="text-center">খালি বস্তার পরিমাণ</th>
                    </tr>
                    <tr>
                        <td class="text-center"><?php echo $srinfo->quantity . " ( " . ucwords(AppHelper::int_to_words($srinfo->quantity)) . " )"; ?></td>
                        <td class="text-center"><?php echo!empty($srinfo->type) ? ProductType::model()->findByPk($srinfo->type)->name : ''; ?></td>
                        <td class="text-center"><?php echo!empty($deliveryItems) ? $deliveryItems[count($deliveryItems) - 1]->rent : ''; ?></td>
                        <td class="text-center"><?php echo LoanItem::model()->sumTotal($srinfo->sr_no); ?></td>
                        <td class="text-center"><?php echo!empty($loanItem) ? date("j-F-Y", strtotime($loanItem->create_date)) : ''; ?></td>
                        <td class="text-center"><?php echo $srinfo->carrying_cost; ?></td>
                        <td class="text-center"><?php echo $srinfo->loan_pack; ?></td>
                    </tr>
                </table>

                <table class="table table-bordered tbl_invoice_view mb_10">
                    <tr class="bg_gray">
                        <th class="text-center" style="">Date</th>
                        <th class="text-center" style="">Receipt No</th>
                        <th class="text-center" style="">D. Bag</th>
                        <th class="text-center" style="">Rent</th>
                        <th class="text-center" style="">Loan Received</th>
                        <th class="text-center" style="">S. Charge</th>
                        <th class="text-center" style="">E. Bag Price</th>
                        <th class="text-center" style="">Carrying</th>
                        <th class="text-center" style="">Fanning Charge</th>
                        <th class="text-center" style="">Others</th>
                        <th class="text-center" style="">Total</th>
                        <th class="text-center" style="">Signature</th>
                    </tr>
                    <?php if (!empty($deliveryItems) && count($deliveryItems) > 0) : ?>
                        <?php
                        foreach ($deliveryItems as $ditem) :
                            $_loan = LoanReceiveItem::model()->loanByDelivery($ditem->delivery_number);
                            $_interest = LoanReceiveItem::model()->interestByDelivery($ditem->delivery_number);
                            ?>
                            <tr>
                                <td class="text-center"><?php echo date('j-M-Y', strtotime($ditem->delivery_date)); ?></td>
                                <td class="text-center"><?php echo $ditem->reff->delivery_number; ?></td>
                                <td class="text-center"><?php echo $ditem->quantity; ?></td>
                                <td class="text-center"><?php echo $ditem->rent_total; ?></td>
                                <td class="text-center"><?php echo $_loan; ?></td>
                                <td class="text-center"><?php echo $_interest; ?></td>
                                <td class="text-center"><?php echo $ditem->loan_bag_price_total; ?></td>
                                <td class="text-center"><?php echo $ditem->carrying; ?></td>
                                <td class="text-center"><?php echo $ditem->fan_charge_total; ?></td>
                                <td class="text-center"><?php echo ''; ?></td>
                                <td class="text-center"><?php echo $_total = ($ditem->rent_total + $_loan + $_interest + $ditem->loan_bag_price_total + $ditem->carrying + $ditem->fan_charge_total); ?></td>
                                <td class="text-center"><?php echo ''; ?></td>
                            </tr>
                            <?php
                            $sum_qty[] = $ditem->quantity;
                            $sum_total_rent[] = $ditem->rent_total;
                            $sum_loan[] = $_loan;
                            $sum_interest[] = $_interest;
                            $sum_ebp_total[] = $ditem->loan_bag_price_total;
                            $sum_carrying[] = $ditem->carrying;
                            $sum_fanc[] = $ditem->fan_charge_total;
                            $sum_net_total[] = $_total;
                        endforeach;
                        ?>
                        <tr class="bg_gray">
                            <th class="text-right" colspan="2">Total</th>
                            <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_total_rent); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_loan); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_interest); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_ebp_total); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_carrying); ?></th>
                            <th class="text-center"><?php echo array_sum($sum_fanc); ?></th>
                            <th colspan="1"></th>
                            <th class="text-center"><?php echo array_sum($sum_net_total); ?></th>
                            <th class="text-center"></th>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td class="text-center" colspan="12">No Delivery data found.</td>
                        </tr>
                    <?php endif; ?>
                </table>
                <span style="font-weight:bold;">বিঃ দ্রঃ সংরক্ষিত আলুর মূল দলিল হস্তান্তরযোগ্য নহে। আলুর মূল দলিল বহনকারীই আলুর বস্তার মালিক হিসাবে বিবেচিত হইবে।</span>
            </div>

            <div class="form-group clearfix">
                <div class="col-md-2 form-group mpw_20" style="padding-top:50px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "গ্রহীতা"); ?></div>
                </div>
                <div class="col-md-3 form-group mpw_20" style="padding-top:50px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "কম্পিউটার অপারেটর"); ?></div>
                </div>
                <div class="col-md-3 form-group mpw_20" style="padding-top:50px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "আদায়কারীর স্বাক্ষর"); ?></div>
                </div>
                <div class="col-md-2 form-group mpw_20" style="padding-top:50px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "হিসাবরক্ষক"); ?></div>
                </div>
                <div class="col-md-2 form-group mpw_20" style="padding-top:50px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "ব্যবস্থাপক"); ?></div>
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