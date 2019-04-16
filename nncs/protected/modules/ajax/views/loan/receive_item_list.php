<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped tbl_invoice_view">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                <th style="width:10%;"><?php echo Yii::t("strings", "Date"); ?></th>
                <th style=""><?php echo Yii::t("strings", "Customer"); ?></th>
                <th><?php echo Yii::t("strings", "Father Name"); ?></th>
                <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "Agent"); ?></th>
                <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "SR No"); ?></th>
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "Qty"); ?></th>
                <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "P.Q Loan"); ?></th>
                <th class="text-right" style="width:7%;"><?php echo Yii::t("strings", "Amount"); ?></th>
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "Days"); ?></th>
                <th class="text-right" style="width:6%;"><?php echo Yii::t("strings", "Interest"); ?></th>
                <th class="text-right" style="width:7%;"><?php echo Yii::t("strings", "Total"); ?></th>
                <th class="text-right" style="width:6%;"><?php echo Yii::t("strings", "Discount"); ?></th>
                <th class="text-right" style="width:9%;"><?php echo Yii::t("strings", "G.Total"); ?></th>
            </tr>
            <?php
            $counter = 0;
            if (isset($_GET['page']) && $_GET['page'] > 1) {
                $counter = ($_GET['page'] - 1) * $pages->pageSize;
            }
            foreach ($dataset as $data):
                $counter++;
                $_sts = $data->status;
                ?>
                <tr class="">
                    <td class="text-center"><?php echo $counter; ?></td>
                    <td><?php echo date("j M Y", strtotime($data->receive_date)); ?></td>
                    <td><?php echo $data->customer->name; ?></td>
                    <td><?php echo!empty($data->customer->father_name) ? $data->customer->father_name : ""; ?></td>
                    <td class="text-center"><?php echo $data->agent_code; ?></td>
                    <td class="text-center"><?php echo $data->sr_no; ?></td>
                    <td class="text-center"><?php echo $data->qty; ?></td>
                    <td class="text-center"><?php echo $data->cost_per_qty; ?></td>
                    <td class="text-right"><?php echo $data->loan_amount; ?></td>
                    <td class="text-center"><?php echo $data->loan_days; ?></td>
                    <td class="text-right"><?php echo $data->interest_amount; ?></td>
                    <td class="text-right"><?php echo $data->total_amount; ?></td>
                    <td class="text-right"><?php echo $data->discount; ?></td>
                    <td class="text-right"><?php echo $data->net_amount; ?></td>
                </tr>
                <?php
                $sum_qty[] = $data->qty;
                $sum_loan_amount[] = $data->loan_amount;
                $sum_interest_amount[] = $data->interest_amount;
                $sum_total_amount[] = $data->total_amount;
                $sum_discount[] = $data->discount;
                $sum_net_amount[] = $data->net_amount;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th class="text-right" colspan="6"><?php echo Yii::t("strings", "Total"); ?></th>
                <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                <th></th>
                <th class="text-right"><?php echo array_sum($sum_loan_amount); ?></th>
                <th></th>
                <th class="text-right"><?php echo array_sum($sum_interest_amount); ?></th>
                <th class="text-right"><?php echo array_sum($sum_total_amount); ?></th>
                <th class="text-right"><?php echo array_sum($sum_discount); ?></th>
                <th class="text-right"><?php echo array_sum($sum_net_amount); ?></th>
            </tr>
        </table>
    </div>

    <div class="paging dis_print">
        <?php
        $this->widget('CLinkPager', array(
            'pages' => $pages,
            'header' => ' ',
            'firstPageLabel' => '<<',
            'lastPageLabel' => '>>',
            'nextPageLabel' => '> ',
            'prevPageLabel' => '< ',
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
    <div class="alert alert-info">No records found!</div>
<?php endif; ?>