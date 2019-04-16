<?php
$this->breadcrumbs = array(
    'Product In' => array(AppUrl::URL_PRODUCT_IN),
    'Invoice'
);
if (!empty($location)) {
    $_room = !empty($location->room_id) ? LocationRoom::model()->findByPk($location->room_id)->name : '';
    $_floor = !empty($location->floor_id) ? LocationFloor::model()->findByPk($location->floor_id)->name : '';
    $_pocket = !empty($location->pocket_id) ? LocationPocket::model()->findByPk($location->pocket_id)->pocket_no : '';
} else {
    $_room = '';
    $_floor = '';
    $_pocket = '';
}
?>
<div class="content-panel">
    <div id="printDiv">
        <div class="clearfix txt_left_xs mp_center media_print">
            <div class="col-md-3 mpw_25 text-center">
                <?php if (!empty($this->settings->logo)) : ?>
                    <img alt="" src="<?php echo Yii::app()->request->baseUrl . '/uploads/' . $this->settings->logo; ?>" style="max-height: 50px;position: absolute;left: 15px;top: 0;">
                <?php endif; ?>
                <span style="display:inline-block;font-size:16px;font-weight:600;margin-top:65px;padding:2px 0;width:230px;">এস আর নং</span><br>
                <span style="display:inline-block;font-size:20px;font-weight:600;padding:2px 0;width:230px;"><?php echo AppHelper::en2bn($model->sr_no); ?>/<?php echo AppHelper::en2bn($model->quantity); ?></span>
            </div>
            <div class="col-md-6 mpw_50 text-center">
                <span style="background-color:#000;color:#fff;display:inline-block;padding:1px 0;width:230px;">বিসমিল্লাহির রাহমানির রাহিম</span><br>
                <span style="background-color:#f7f7f7;color:#000;display:inline-block;font-size:16px;padding:2px 0;width:230px;">সংরক্ষিত আলুর মূল দলিল</span><br>
                <?php if (!empty($this->settings->title)): ?>
                    <h1 style="font-weight: 600;font-size: 20px;margin: 0;">
                        <?php echo $this->settings->title; ?><br>
                        N. N. COLD STORAGE LTD
                    </h1>
                <?php endif; ?>
                <?php if (!empty($this->settings->author_address)): ?>
                    <span style="background-color:#f7f7f7;color:#000;display:inline-block;font-size:16px;padding:2px 0;width:230px;"><?php echo $this->settings->author_address; ?></span><br>
                <?php endif; ?>
                <?php if (!empty($this->settings->author_mobile)): ?>
                    <span style="background-color:#f7f7f7;color:#000;display:inline-block;font-size:16px;padding:2px 0;width:230px;">মোবাইল : <?php echo $this->settings->author_mobile; ?></span><br>
                <?php endif; ?>
            </div>
            <div class="col-md-3 mpw_25 pull-right">
                <table class="table customer_info agent_info" style="background-color:#f7f7f7;margin:0 0 1px;font-size: 8pt;">
                    <tr>
                        <td style="width:50%">Agent No</td>
                        <td><?php echo!empty($model->agent_code) ? $model->agent_code : ''; ?></td>
                    </tr>
                    <tr>
                        <td style="width:50%">এজেন্টের নাম</td>
                        <td><?php echo!empty($agent->name) ? $agent->name : ''; ?></td>
                    </tr>
                    <tr>
                        <td style="width:50%">এজেন্টের গ্রাম</td>
                        <td><?php echo!empty($agent->village) ? $agent->village : ''; ?></td>
                    </tr>
                    <tr>
                        <td style="width:50%">এজেন্টের উপজেলা</td>
                        <td><?php echo!empty($agent->upozila) ? $agent->upozila : ''; ?></td>
                    </tr>
                    <tr>
                        <td style="width:50%">এজেন্টের মোবাইল</td>
                        <td><?php echo!empty($agent->mobile) ? $agent->mobile : ''; ?></td>
                    </tr>
                    <tr>
                        <td style="width:50%">তারিখ</td>
                        <td><?php echo date("j-M-Y", strtotime($model->create_date)); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="clearfix media_print">
            <div class="col-md-5 mpw_50">
                <table class="table customer_info in_info_tbl" style="background-color:#f7f7f7;margin:0 0 1px;">
                    <tr>
                        <td style="width:50%;">সংরক্ষণকারীর নামঃ-</td>
                        <td><?php echo $customer->name; ?></td>
                    </tr>
                    <tr>
                        <td style="width:50%;">পিতা/স্বত্বাধিকারীরনামঃ-</td>
                        <td><?php echo $customer->father_name; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-7 mpw_50 pull-right">
                <table class="table customer_info in_info_tbl" style="background-color:#f7f7f7;margin:0 0 1px;">
                    <tr>
                        <td style="width:12%">গ্রামঃ-</td>
                        <td style="width:38%"><?php echo $customer->village; ?></td>
                        <td style="width:12%">উপজেলাঃ-</td>
                        <td style="width:38%"><?php echo!empty($customer->thana) ? $customer->thana : ''; ?></td>
                    </tr>
                    <tr>
                        <td style="width:12%">জেলাঃ-</td>
                        <td style="width:38%"><?php echo!empty($customer->district) ? $customer->district : ''; ?></td>
                        <td style="width:12%">মোবাইলঃ-</td>
                        <td style="width:38%"><?php echo $customer->mobile; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-md-12">
            <div class="text-center">
                <span style="background-color:#f7f7f7;color:#000;display:inline-block;font-size:16px;font-weight:bold;padding:3px 0;width:230px;">আলু সংরক্ষণের বিবরণ</span>
            </div>
            <table class="table table-bordered in_info_tbl mb_10" style="margin-bottom:10px;">
                <tr class="bg-danger" style="background-color: #d9534f;color: #ffffff;">
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
                    <td class="text-center"><?php echo!empty($model->type) ? ProductType::model()->findByPk($model->type)->name : ''; ?></td>
                    <td class="text-center"><?php echo ''; ?></td>
                    <td class="text-center"><?php echo $model->carrying_cost; ?></td>
                    <td class="text-center"><?php echo $model->loan_pack; ?></td>
                </tr>
            </table>

            <div>
                আপনার লোন, ভাড়া, সার্ভিসচার্জ ও কম্পিউটার সংক্রান্ত কোন বিষয়ে যোগযোগ করুনঃ ০১৭৪৫-৩৬৩৬৫১
            </div>
            <strong>সংরক্ষণের শর্তাবলী</strong><br>
            <div class="form-group clearfix ff_bn" style="font-size:8pt">
                ১। সংরক্ষণপত্র হস্তান্তরযোগ্য নহে। সংরক্ষণপত্র (মূল দলিল) বহনকারীই আলুর মালিক হিসাবে বিবেচিত হইবেন।
                <br>২। প্রতি বস্তায় ৮৪ কেজি আলু রাখা চলিবে। কম রাখিলে এই ভাড়া লাগিবে। কর্তৃপক্ষ প্রয়োজনবোধে যে কোন বস্তা বাতিল করিতে অথবা পার্টিও খরচে পুনরায় সুষ্ঠুভাবে বস্তাবন্দী করাইতে পারিবেন।
                <br>৩। পার্টি নিজ দায়িত্বে হিমাগারে সংরক্ষণের উপযুক্ত পাকা, পরিণত আলু জমা দিবেন। অপুষ্ঠ আলু হিমাগারে রাখিলে ওজনে অস্বাভাবিক ঘাটতি বা কমতি হয়। এইরুপ আলু হিমাগারে রাখার অনুপযোগী। এই ঘাটতির জন্য কোম্পানী কোনভাবে দায়ী নয়।
                <br>৪। বস্তা নতুন ও পাতলা অথচ মজবুত হইতে হইবে। চিনি, ময়দা, সোডা এবং যে কোন প্রকার কেমিক্যাল অথবা আলুর ক্ষতি করিতে পারে এরুপ মালের জন্য ব্যবহৃত বস্তায় আলু রাখা চলিবে না। এই প্রকার  বস্তায় রক্ষিত আলু পচিঁলে বা নষ্ট হইয়া গেলে কিংবা পুরাতন বস্তা ছিড়িয়া আলু পড়িয়া গেলে  কোম্পানী দায়ী হইবে না।
                <br>৫। কোম্পানী আলু নিরাপত্তার জন্য সমস্ত আলু বীমা করিয়া থাকেন। উক্ত বীমার ২০% ক্ষতির জন্য বীমা কোম্পানী কোন ক্ষতিপূরণ দেয় না। কাজেই ২০% আলু পচিঁয়া নষ্ট হইলে বা ওজন কমিলে কোন অবস্থাতেই ক্ষতিপূরণ দেওয়া হইবে না।
                <br>৬। আলু সংরক্ষণকারী বীমা কোম্পানীর আইন অনুযায়ী কোন ক্ষতিপূরণ পাওয়ার যোগ্য হইলে উক্ত ক্ষতিপূরণ বীমা কোম্পানী হইতে আদায়ের পর প্রদান করা হইবে।
                <br>৭। কোম্পানী সঠিক সংরক্ষণের জন্য সর্বপ্রকার সাবধানতা অবলম্বন করিবে কিন্তু কোম্পানীর এখতিয়ার বহির্ভূত দৈব দূর্ঘটনা বা ধর্মঘট, বিদ্যুৎ বিভ্রাট, মেশিনারী নষ্ট হইয়া, দাঙ্গা-হাঙ্গামা, ধ্বংসাত্বক কার্যকলাপ, যুদ্ধ, বিপদাত্মক পরিস্থিতি, আগুন লাগা, বন্যা ইত্যাদি কোন কারণে সংরক্ষিত আলু বিনষ্ট বা খারাপ হইলে কোম্পানী দায়ী থাকিবে না এবং পার্টিকে কোন ক্ষতিপূরণ দেওয়া হইবে না।
                <br>৮। কাই আলুর বস্তা গজালে বা পচেঁ গেলে কোম্পানী দায়ী থাকিবে  না।
                <br>৯। সংরক্ষিত আলু ডেলিভারী নেওয়ার ৫ দিন পূর্বে পাওনা টাকা পরিশোধ করিয়া ডেলিভারী অর্ডার নিতে হইবে।
                <br>১০। আলু ডেলিভারীর সময় অবশ্যই এই দলিল আনিতে হইবে। দলিল হারাইয়া গেলে অনতিবিলম্বে থানায় জিডি করিয়া জিডির কপি সহকারে কর্তৃপক্ষকে অবহিত করিতে হইবে।
                <br>১১। ভাড়া ও অন্যান্য যাবতীয় পাওনা ৩০ শে অক্টোবরের মধ্যে পরিশোধ করিয়া এক, দুই বা তিন দফায় ১৫ই নভেম্বরের পূর্বেই আলু ফেরৎ লইতে হইবে। অন্যথায় কোম্পানী নিজ দায়িত্বে আলু বিক্রয় করিয়া সমূদয় ভাড়া টাকা কাটিয়া লইতে বাধ্য থাকিবে।
                <br>১২। নির্ধারিত তারিখ অতিবাহিত হওয়ার পর আলু ডেলিভারী গ্রহণ না করিলে কর্তৃপক্ষ সংরক্ষিত আলু বিক্রয় করিয়া পাওনাদি পরিশোধ করিয়া নিবে। বিক্রয় মূল্য দিয়ে কোম্পানী পাওনা পরিশোধ না হইলে অবশিষ্ট পাওনা আলু সংরক্ষণকারীকে(পার্টি/কৃষক) পরিশোধ করিতে হইবে।
            </div>
        </div>
        <div class="clearfix">
            <div class="col-md-3 form-group mpw_25" style="padding-top:50px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "দলিল প্রস্তুতকারীর স্বাক্ষর"); ?></div>
            </div>
            <div class="col-md-3 form-group mpw_25" style="padding-top:50px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "হিসাবসহকারী/হিসাবরক্ষকের স্বাক্ষর"); ?></div>
            </div>
            <div class="col-md-3 form-group mpw_25" style="padding-top:50px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "ষ্টোরকিপারের স্বাক্ষর"); ?></div>
            </div>
            <div class="col-md-3 form-group mpw_25" style="padding-top:50px;">
                <div style="border-top:1px solid #000000;text-align:center;"><?php echo Yii::t("strings", "ব্যবস্থাপক/পরিচালকের স্বাক্ষর"); ?></div>
            </div>
        </div>
    </div>
    <div class="clearfix">
        <div class="form-group text-center">
            <button type="button" class="btn btn-primary btn-xs" onclick="printElem('#printDiv')" style="margin-right:20px;"><i class="fa fa-print"></i>&nbsp;<?php echo Yii::t("strings", "Print"); ?></button>
            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_PRODUCT_IN_CREATE); ?>"><button class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "New Entry"); ?></button></a>
        </div>
    </div>
</div>