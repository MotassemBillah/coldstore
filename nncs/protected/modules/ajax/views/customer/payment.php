<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:4%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th style="width:9%;"><?php echo Yii::t('strings', 'Date'); ?></th>
                <th><?php echo Yii::t('strings', 'Sr No'); ?></th>
                <th class=""><?php echo Yii::t('strings', 'Invoice No'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Quantity'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Rent'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Total Rent'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Loan Pack'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Pack Cost'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Cost Total'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Dues Paid'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Advance'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'G.Total'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Action'); ?></th>
            </tr>
            <?php
            $counter = 0;
            if (isset($_GET['page']) && $_GET['page'] > 1) {
                $counter = ($_GET['page'] - 1) * $pages->pageSize;
            }
            foreach ($dataset as $data):
                $counter++;
                $gtotal = ($data->loan_bag_amount + $data->delivered_cost_amount + $data->due_paid) - $data->advance_paid;
                ?>
                <tr class="">
                    <td class="text-center" style="width:4%;"><?php echo $counter; ?></td>
                    <td style="width:9%;"><?php echo date('j M Y', strtotime($data->created)); ?></td>
                    <td><?php echo $data->sr_no; ?></td>
                    <td><?php echo $data->delivery_sr_no; ?></td>
                    <td class="text-center"><?php echo $data->delivered_qty; ?></td>
                    <td class="text-center"><?php echo $data->delivered_cost; ?></td>
                    <td class="text-right"><?php echo $data->delivered_cost_amount; ?></td>
                    <td class="text-center"><?php echo $data->loan_bag; ?></td>
                    <td class="text-center"><?php echo $data->loan_bag_cost; ?></td>
                    <td class="text-right"><?php echo $data->loan_bag_amount; ?></td>
                    <td class="text-right"><?php echo $data->due_paid; ?></td>
                    <td class="text-right"><?php echo $data->advance_paid; ?></td>
                    <td class="text-right"><?php echo $data->net_amount; ?></td>
                    <td class="text-center">
                        <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_CUSTOMER_PAYMENT_EDIT, ['id' => $data->_key]); ?>"><?php echo Yii::t("strings", "Edit"); ?></a>
                    </td>
                </tr>
                <?php
                $sum_dc_qty[] = $data->delivered_qty;
                $sum_dc_total[] = $data->delivered_cost_amount;
                $sum_lpc[] = $data->loan_bag;
                $sum_lpc_total[] = $data->loan_bag_amount;
                $sum_paid[] = $data->due_paid;
                $sum_adv[] = $data->advance_paid;
                $sum_gt[] = $data->net_amount;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th colspan="4" class="text-right"><?php echo Yii::t("strings", "Total"); ?></th>
                <th class="text-center"><?php echo array_sum($sum_dc_qty); ?></th>
                <th></th>
                <th class="text-right"><?php echo array_sum($sum_dc_total); ?></th>
                <th class="text-center"><?php echo array_sum($sum_lpc); ?></th>
                <th></th>
                <th class="text-right"><?php echo array_sum($sum_lpc_total); ?></th>
                <th class="text-right"><?php echo array_sum($sum_paid); ?></th>
                <th class="text-right"><?php echo array_sum($sum_adv); ?></th>
                <th class="text-right"><?php echo array_sum($sum_gt); ?></th>
                <th></th>
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
                'id' => 'pagination',
            )
        ));
        ?>
    </div>
<?php else: ?>
    <div class="alert alert-info"><?php echo Yii::t('strings', 'No records found!'); ?></div>
<?php endif; ?>