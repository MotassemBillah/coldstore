<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th style="width:10%;"><?php echo Yii::t('strings', 'Date'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Empty Bag'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Bag Price'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Price Total'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Carrying'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Loan Amount'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Debit'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Credit'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Balance'); ?></th>
            </tr>
            <?php
            $counter = 0;
            if (isset($_GET['page']) && $_GET['page'] > 1) {
                $counter = ($_GET['page'] - 1) * $pages->pageSize;
            }
            foreach ($dataset as $data):
                $counter++;
                ?>
                <tr class="">
                    <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                    <td style="width:10%;"><?php echo date('j M Y', strtotime($data->created)); ?></td>
                    <td class="text-center"><?php echo $data->empty_bag; ?></td>
                    <td class="text-right"><?php echo $data->empty_bag_price; ?></td>
                    <td class="text-right"><?php echo $data->empty_bag_price_total; ?></td>
                    <td class="text-right"><?php echo $data->carrying_cost; ?></td>
                    <td class="text-right"><?php echo $data->loan_amount; ?></td>
                    <td class="text-right" style="width:10%;"><?php echo $data->debit; ?></td>
                    <td class="text-right" style="width:10%;"><?php echo $data->credit; ?></td>
                    <td class="text-right" style="width:10%;"><?php echo $data->balance; ?></td>
                </tr>
                <?php
                $sum_empty_bag[] = $data->empty_bag;
                $sum_empty_bag_price[] = $data->empty_bag_price;
                $sum_empty_bag_price_total[] = $data->empty_bag_price_total;
                $sum_ccost[] = $data->carrying_cost;
                $sum_ln_amount[] = $data->loan_amount;
                $sum_debit[] = $data->debit;
                $sum_credit[] = $data->credit;
                $sum_balance[] = $data->balance;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th colspan="2" class="text-right"><?php echo Yii::t("strings", "Total"); ?></th>
                <th class="text-center"><?php echo array_sum($sum_empty_bag); ?></th>
                <th class="text-right"><?php echo array_sum($sum_empty_bag_price); ?></th>
                <th class="text-right"><?php echo array_sum($sum_empty_bag_price_total); ?></th>
                <th class="text-right"><?php echo array_sum($sum_ccost); ?></th>
                <th class="text-right"><?php echo array_sum($sum_ln_amount); ?></th>
                <th class="text-right"><?php echo array_sum($sum_debit); ?></th>
                <th class="text-right"><?php echo array_sum($sum_credit); ?></th>
                <th class="text-right"><?php echo array_sum($sum_balance); ?></th>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on("click", ".pagination li a", function(e) {
                showLoader("Processing...", true);
                var _form = $("#frmSearch");
                var _srcUrl = $(this).attr('href');

                $.ajax({
                    type: "POST",
                    url: _srcUrl,
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
<?php else: ?>
    <div class="alert alert-info"><?php echo Yii::t('strings', 'No records found!'); ?></div>
<?php endif; ?>