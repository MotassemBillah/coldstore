<?php
$this->breadcrumbs = array(
    'Delivery' => array(AppUrl::URL_DELIVERY),
    'View'
);
?>
<div class="row content-panel">
    <div id="printDiv">
        <div class="clearfix padb_20 mpbb mb_50">
            <div class="clearfix text-center mp_center mb_10 mp_mt">
                <div class="col-md-3 mpw_33">
                    <table class="table table-bordered tbl_invoice_view" style="margin: 0;">
                        <tr>
                            <th>ক্রমিক নং</th>
                            <th><?php echo $model->delivery_number; ?></th>
                        </tr>
                        <tr>
                            <th>ডেলিভারি তারিখঃ</th>
                            <td><?php echo date("j M Y", strtotime($model->delivery_date)); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 mpw_33">
                    <?php if (!empty($this->settings->logo)) : ?>
                        <img alt="Logo" id="print_logo" src="<?php echo Yii::app()->request->baseUrl . '/uploads/' . $this->settings->logo; ?>" style="max-height:50px;position:absolute;left:0;top:0;">
                    <?php endif; ?>
                    <?php if (!empty($this->settings->title)): ?>
                        <h1 style="font-size:16px;font-weight:600;margin:0;"><?php echo $this->settings->title; ?></h1>
                    <?php endif; ?>
                    <?php if (!empty($this->settings->author_address)): ?>
                        <span style="font-size:11px;"><?php echo $this->settings->author_address; ?></span><br>
                    <?php endif; ?>
                    <?php if (!empty($this->settings->author_mobile)): ?>
                        <span style="font-size:12px;">মোবাইল : <?php echo $this->settings->author_mobile; ?></span><br>
                    <?php endif; ?>
                    <h4 class="inv_title" style="font-size:12px;font-weight:600;">ডেলিভারি অর্ডার ( <span style="font-size:10px;">হস্তান্তর যোগ্য নয়</span> )</h4>
                </div>
                <div class="col-md-3 mpw_33 pull-right text-right">
                    <table class="table table-bordered tbl_invoice_view" style="margin: 0;">
                        <tr>
                            <th class="text-center">বিবরণ</th>
                            <th class="text-center">স্টক</th>
                        </tr>
                        <tr>
                            <td class="text-center">বস্তা</td>
                            <td class="text-center"><?php echo $srinfo->quantity; ?></td>
                        </tr>
                        <tr>
                            <td class="text-center">লোন</td>
                            <td class="text-center"><?php echo LoanItem::model()->sumTotal($srinfo->sr_no); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-12">
                <table class="table table-bordered tbl_invoice_view" style="margin: 0;">
                    <tr>
                        <td><strong>নামঃ</strong> <?php echo $srinfo->customer->name; ?></td>
                        <td colspan="2"><strong>পিতার নামঃ</strong> <?php echo $srinfo->customer->father_name; ?></td>
                    </tr>
                    <tr>
                        <td>
                            <strong>ঠিকানাঃ</strong>
                            <?php
                            echo!empty($srinfo->customer->village) ? $srinfo->customer->village : '';
                            echo!empty($srinfo->customer->thana) ? ', ' . $srinfo->customer->thana : '';
                            echo!empty($srinfo->customer->district) ? ', ' . $srinfo->customer->district : '';
                            ?>
                        </td>
                        <td><strong>এজেন্ট কোডঃ</strong> <?php echo!empty($srinfo->agent_code) ? $srinfo->agent_code : ''; ?></td>
                        <td><strong>এজেন্টঃ</strong> <?php echo!empty($srinfo->agent_code) ? Agent::model()->find("code=:cd", [":cd" => $srinfo->agent_code])->name : ''; ?></td>
                    </tr>
                </table>
            </div>

            <?php
            $_loanAmount = !empty($loanItem->loan_amount) ? AppHelper::getFloat($loanItem->loan_amount) : '';
            $_loanDays = !empty($loanItem->loan_days) ? $loanItem->loan_days : '';
            $_loanInterest = !empty($loanItem->interest_amount) ? AppHelper::getFloat($loanItem->interest_amount) : '';
            ?>

            <div class="col-md-12">
                <table class="table table-striped table-bordered tbl_invoice_view">
                    <tr class="bg_gray">
                        <th>বিবরণ</th>
                        <th class="text-center">বস্তার পরিমান</th>
                        <th class="text-center">দর</th>
                        <th class="text-right">টাকা</th>
                    </tr>
                    <tr>
                        <td>এস আর নং   ( <?php echo $srinfo->lot_no; ?> )</td>
                        <td class="text-center"><?php echo $item->quantity; ?></td>
                        <td class="text-center"></td>
                        <td class="text-right"><?php echo AppHelper::getFloat($item->rent_total); ?></td>
                    </tr>
                    <tr>
                        <td>ঋণের পরিমান</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-right"><?php echo $_loanAmount; ?></td>
                    </tr>
                    <tr>
                        <td>সার্ভিস চার্জ ( <?php echo $_loanDays; ?> ) দিনের জন্য</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-right"><?php echo $_loanInterest; ?></td>
                    </tr>
                    <tr>
                        <td>খালি বস্তার মূল্য</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-right"><?php echo AppHelper::getFloat($item->loan_bag_price_total); ?></td>
                    </tr>
                    <tr>
                        <td>পরিবহন / ক্যারিং</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-right"><?php echo AppHelper::getFloat($item->carrying); ?></td>
                    </tr>
                    <tr>
                        <td>ফ্যানিং চার্জ</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-right"><?php echo AppHelper::getFloat($item->fan_charge_total); ?></td>
                    </tr>
                    <tr>
                        <td>অন্যান্য আদায়</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-right"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">সর্ব মোট আদায়</td>
                        <td class="text-right">
                            <?php
                            $_totalAday = ($item->rent_total + $_loanAmount + $_loanInterest + $item->loan_bag_price_total + $item->carrying + $item->fan_charge_total);
                            echo AppHelper::getFloat($_totalAday);
                            ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="clearfix">
                <div class="col-md-3 form-group mpw_25" style="padding-top:25px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "দলিল প্রস্তুকারীর স্বাক্ষর"); ?></div>
                </div>
                <div class="col-md-3 form-group mpw_25" style="padding-top:25px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "অফিস সহকারী"); ?></div>
                </div>
                <div class="col-md-3 form-group mpw_25" style="padding-top:25px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "হিসাবরক্ষক"); ?></div>
                </div>
                <div class="col-md-3 form-group mpw_25" style="padding-top:25px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "ব্যবস্থাপক"); ?></div>
                </div>
            </div>
        </div>

        <div class="clearfix">
            <div class="clearfix text-center mp_center mb_10">
                <div class="col-md-3 mpw_33">
                    <table class="table table-bordered tbl_invoice_view" style="margin: 0;">
                        <tr>
                            <th>ক্রমিক নং</th>
                            <th><?php echo $model->delivery_number; ?></th>
                        </tr>
                        <tr>
                            <th>ডেলিভারি তারিখঃ</th>
                            <td><?php echo date("j M Y", strtotime($model->delivery_date)); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 mpw_33">
                    <?php if (!empty($this->settings->logo)) : ?>
                        <img alt="Logo" id="print_logo" src="<?php echo Yii::app()->request->baseUrl . '/uploads/' . $this->settings->logo; ?>" style="max-height:50px;position:absolute;left:0;top:0;">
                    <?php endif; ?>
                    <?php if (!empty($this->settings->title)): ?>
                        <h1 style="font-size:16px;font-weight:600;margin:0;"><?php echo $this->settings->title; ?></h1>
                    <?php endif; ?>
                    <?php if (!empty($this->settings->author_address)): ?>
                        <span style="font-size:11px;"><?php echo $this->settings->author_address; ?></span><br>
                    <?php endif; ?>
                    <?php if (!empty($this->settings->author_mobile)): ?>
                        <span style="font-size:12px;">মোবাইল : <?php echo $this->settings->author_mobile; ?></span><br>
                    <?php endif; ?>
                    <h4 class="inv_title" style="font-size:12px;font-weight:600;">ডেলিভারি অর্ডার ( <span style="font-size:10px;">হস্তান্তর যোগ্য নয়</span> )</h4>
                </div>
                <div class="col-md-3 mpw_33 pull-right text-right">
                    <table class="table table-bordered tbl_invoice_view" style="margin: 0;">
                        <tr>
                            <th class="text-center">বিবরণ</th>
                            <th class="text-center">স্টক</th>
                        </tr>
                        <tr>
                            <td class="text-center">বস্তা</td>
                            <td class="text-center"><?php echo $srinfo->quantity; ?></td>
                        </tr>
                        <tr>
                            <td class="text-center">লোন</td>
                            <td class="text-center"><?php echo LoanItem::model()->sumTotal($srinfo->sr_no); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-12">
                <table class="table table-bordered tbl_invoice_view" style="margin: 0;">
                    <tr>
                        <td><strong>নামঃ</strong> <?php echo $srinfo->customer->name; ?></td>
                        <td colspan="2"><strong>পিতার নামঃ</strong> <?php echo $srinfo->customer->father_name; ?></td>
                    </tr>
                    <tr>
                        <td>
                            <strong>ঠিকানাঃ</strong>
                            <?php
                            echo!empty($srinfo->customer->village) ? $srinfo->customer->village : '';
                            echo!empty($srinfo->customer->thana) ? ', ' . $srinfo->customer->thana : '';
                            echo!empty($srinfo->customer->district) ? ', ' . $srinfo->customer->district : '';
                            ?>
                        </td>
                        <td><strong>এজেন্ট কোডঃ</strong> <?php echo!empty($srinfo->agent_code) ? $srinfo->agent_code : ''; ?></td>
                        <td><strong>এজেন্টঃ</strong> <?php echo!empty($srinfo->agent_code) ? Agent::model()->find("code=:cd", [":cd" => $srinfo->agent_code])->name : ''; ?></td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table table-striped table-bordered tbl_invoice_view">
                    <tr class="bg_gray">
                        <th>বিবরণ</th>
                        <th class="text-center">বস্তার পরিমান</th>
                        <th class="text-center">দর</th>
                        <th class="text-right">টাকা</th>
                    </tr>
                    <tr>
                        <td>এস আর নং   ( <?php echo $srinfo->lot_no; ?> )</td>
                        <td class="text-center"><?php echo $item->quantity; ?></td>
                        <td class="text-center"></td>
                        <td class="text-right"><?php echo AppHelper::getFloat($item->rent_total); ?></td>
                    </tr>
                    <tr>
                        <td>ঋণের পরিমান</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-right"><?php echo $_loanAmount; ?></td>
                    </tr>
                    <tr>
                        <td>সার্ভিস চার্জ ( <?php echo $_loanDays; ?> ) দিনের জন্য</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-right"><?php echo $_loanInterest; ?></td>
                    </tr>
                    <tr>
                        <td>খালি বস্তার মূল্য</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-right"><?php echo AppHelper::getFloat($item->loan_bag_price_total); ?></td>
                    </tr>
                    <tr>
                        <td>পরিবহন / ক্যারিং</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-right"><?php echo AppHelper::getFloat($item->carrying); ?></td>
                    </tr>
                    <tr>
                        <td>ফ্যানিং চার্জ</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-right"><?php echo AppHelper::getFloat($item->fan_charge_total); ?></td>
                    </tr>
                    <tr>
                        <td>অন্যান্য আদায়</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-right"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">সর্ব মোট আদায়</td>
                        <td class="text-right">
                            <?php
                            echo AppHelper::getFloat($_totalAday);
                            ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="clearfix">
                <div class="col-md-3 form-group mpw_25" style="padding-top:25px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "দলিল প্রস্তুকারীর স্বাক্ষর"); ?></div>
                </div>
                <div class="col-md-3 form-group mpw_25" style="padding-top:25px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "অফিস সহকারী"); ?></div>
                </div>
                <div class="col-md-3 form-group mpw_25" style="padding-top:25px;">
                    <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "হিসাবরক্ষক"); ?></div>
                </div>
                <div class="col-md-3 form-group mpw_25" style="padding-top:25px;">
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