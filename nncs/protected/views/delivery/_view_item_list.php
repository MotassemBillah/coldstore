<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered tbl_invoice_view">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                <th><?php echo Yii::t("strings", "Date"); ?></th>
                <th><?php echo Yii::t("strings", "Customer"); ?></th>
                <th><?php echo Yii::t("strings", "Father Name"); ?></th>
                <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "SR No"); ?></th>
                <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "Lot No"); ?></th>
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Qty"); ?></th>
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Rent"); ?></th>
                <th class="text-right" style="width:7%;"><?php echo Yii::t("strings", "Amount"); ?></th>
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "E. Bag"); ?></th>
                <th class="text-right" style="width:6%;"><?php echo Yii::t("strings", "E.B Tk"); ?></th>
                <th class="text-right" style="width:6%;"><?php echo Yii::t("strings", "Carrying"); ?></th>
                <th class="text-right" style="width:6%;"><?php echo Yii::t("strings", "Fanning"); ?></th>
                <th class="text-right" style="width:6%;"><?php echo Yii::t("strings", "Discount"); ?></th>
                <th class="text-right" style="width:6%;"><?php echo Yii::t("strings", "Total"); ?></th>
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
                    <td class="text-center"><?php echo $counter; ?></td>
                    <td><?php echo date("d-m-Y", strtotime($data->delivery_date)); ?></td>
                    <td><?php echo $data->customer->name; ?></td>
                    <td><?php echo $data->customer->father_name; ?></td>
                    <td class="text-center"><?php echo $data->sr_no; ?></td>
                    <td class="text-center"><?php echo $data->lot_no; ?></td>
                    <td class="text-center"><?php echo $data->quantity; ?></td>
                    <td class="text-center"><?php echo $data->rent; ?></td>
                    <td class="text-right"><?php echo $data->rent_total; ?></td>
                    <td class="text-center"><?php echo $data->loan_bag; ?></td>
                    <td class="text-right"><?php echo $data->loan_bag_price_total; ?></td>
                    <td class="text-right"><?php echo $data->carrying; ?></td>
                    <td class="text-right"><?php echo $data->fan_charge_total; ?></td>
                    <td class="text-right"><?php echo $data->discount; ?></td>
                    <td class="text-right"><?php echo $data->net_total; ?></td>
                </tr>
                <?php
                $sum_qty[] = $data->quantity;
                $sum_rent_total[] = $data->rent_total;
                $sum_loan_bag_qty[] = $data->loan_bag;
                $sum_loan_bag_amount[] = $data->loan_bag_price_total;
                $sum_carrying[] = $data->carrying;
                $sum_fan_charge_total[] = $data->fan_charge_total;
                $sum_discount[] = $data->discount;
                $sum_net_total[] = $data->net_total;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th class="text-right" colspan="6">Total</th>
                <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                <th></th>
                <th class="text-right"><?php echo array_sum($sum_rent_total); ?></th>
                <th class="text-center"><?php echo array_sum($sum_loan_bag_qty); ?></th>
                <th class="text-right"><?php echo array_sum($sum_loan_bag_amount); ?></th>
                <th class="text-right"><?php echo array_sum($sum_carrying); ?></th>
                <th class="text-right"><?php echo array_sum($sum_fan_charge_total); ?></th>
                <th class="text-right"><?php echo array_sum($sum_discount); ?></th>
                <th class="text-right"><?php echo array_sum($sum_net_total); ?></th>
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