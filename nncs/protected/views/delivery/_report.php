<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered tbl_invoice_view">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                <th><?php echo Yii::t("strings", "Date"); ?></th>
                <th class="text-center"><?php echo Yii::t("strings", "Bag"); ?></th>
                <th class="text-right"><?php echo Yii::t("strings", "Rent"); ?></th>
                <th class="text-right" style=""><?php echo Yii::t("strings", "Loan C"); ?></th>
                <th class="text-right" style=""><?php echo Yii::t("strings", "Service C"); ?></th>
                <th class="text-center" style=""><?php echo Yii::t("strings", "E.Bag Qty"); ?></th>
                <th class="text-right" style=""><?php echo Yii::t("strings", "E.Bag TK"); ?></th>
                <th class="text-right" style=""><?php echo Yii::t("strings", "Carrying"); ?></th>
                <th class="text-right" style=""><?php echo Yii::t("strings", "Fanning"); ?></th>
                <th class="text-right" style=""><?php echo Yii::t("strings", "Others"); ?></th>
                <th class="text-right" style=""><?php echo Yii::t("strings", "Total"); ?></th>
            </tr>
            <?php
            $counter = 0;
            if (isset($_GET['page']) && $_GET['page'] > 1) {
                $counter = ($_GET['page'] - 1) * $pages->pageSize;
            }
            foreach ($dataset as $data):
                $counter++;
                $_date = date("d-m-Y", strtotime($data->delivery_date));
                $_qty = DeliveryItem::model()->sumQtyByDate($_date);
                $_rent = DeliveryItem::model()->sumRentByDate($_date);
                $_loan = LoanReceiveItem::model()->sumLoanByDate($_date);
                $_interest = LoanReceiveItem::model()->sumInterestByDate($_date);
                $_loan_bag = DeliveryItem::model()->sumLoanBagByDate($_date);
                $_loan_bag_amount = DeliveryItem::model()->sumLoanBagAmountByDate($_date);
                $_carrying = DeliveryItem::model()->sumCarryingByDate($_date);
                $_fanning = DeliveryItem::model()->sumFanChargeByDate($_date);
                $_total = ($_rent + $_loan + $_interest + $_loan_bag_amount + $_carrying + $_fanning);
                ?>
                <tr class="">
                    <td class="text-center"><?php echo $counter; ?></td>
                    <td><?php echo $_date; ?></td>
                    <td class="text-center"><?php echo $_qty; ?></td>
                    <td class="text-right"><?php echo $_rent; ?></td>
                    <td class="text-right"><?php echo $_loan; ?></td>
                    <td class="text-right"><?php echo $_interest; ?></td>
                    <td class="text-center"><?php echo $_loan_bag; ?></td>
                    <td class="text-right"><?php echo $_loan_bag_amount; ?></td>
                    <td class="text-right"><?php echo $_carrying; ?></td>
                    <td class="text-right"><?php echo $_fanning; ?></td>
                    <td class="text-right"><?php echo ''; ?></td>
                    <td class="text-right"><?php echo $_total; ?></td>
                </tr>
                <?php
                $sum_qty[] = $_qty;
                $sum_rent_total[] = $_rent;
                $sum_loan[] = $_loan;
                $sum_interest[] = $_interest;
                $sum_loan_bag[] = $_loan_bag;
                $sum_loan_bag_amount[] = $_loan_bag_amount;
                $sum_carrying[] = $_carrying;
                $sum_fan_charge_total[] = $_fanning;
                $sum_net_total[] = $_total;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th class="text-right" colspan="2">Total</th>
                <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                <th class="text-right"><?php echo array_sum($sum_rent_total); ?></th>
                <th class="text-right"><?php echo array_sum($sum_loan); ?></th>
                <th class="text-right"><?php echo array_sum($sum_interest); ?></th>
                <th class="text-center"><?php echo array_sum($sum_loan_bag); ?></th>
                <th class="text-right"><?php echo array_sum($sum_loan_bag_amount); ?></th>
                <th class="text-right"><?php echo array_sum($sum_carrying); ?></th>
                <th class="text-right"><?php echo array_sum($sum_fan_charge_total); ?></th>
                <th class="text-right"></th>
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