<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped tbl_invoice_view">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'SR Number'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Agent'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Qty In'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Qty Out'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Stock'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Loan Amount'); ?></th>
                <th class="text-right" style=""><?php echo Yii::t('strings', 'Loan Receive'); ?></th>
                <th class="text-right" style=""><?php echo Yii::t('strings', 'Loan Remain'); ?></th>
                <th class="text-right" style=""><?php echo Yii::t('strings', 'Interest Receive'); ?></th>
                <th class="text-right" style=""><?php echo Yii::t('strings', 'Delivery Receive'); ?></th>
                <th class="text-right" style=""><?php echo Yii::t('strings', 'Total Receive'); ?></th>
            </tr>
            <?php
            $counter = 0;
            if (isset($_GET['page']) && $_GET['page'] > 1) {
                $counter = ($_GET['page'] - 1) * $pages->pageSize;
            }
            foreach ($dataset as $data):
                $counter++;
                $_in = ProductIn::model()->sumQty($data->sr_no);
                $_out = DeliveryItem::model()->sumQty($data->sr_no);
                $_stock = AppObject::currentStock($data->sr_no);
                $_loanRemain = AppObject::currentLoan($data->sr_no);
                $_loanReceive = LoanReceiveItem::model()->sumLoan($data->sr_no);
                $_intReceive = LoanReceiveItem::model()->sumInterest($data->sr_no);
                $_delvReceive = DeliveryItem::model()->sumRent($data->sr_no);
                ?>
                <tr class="">
                    <td class="text-center"><?php echo $counter; ?></td>
                    <td class="text-center"><?php echo $data->sr_no; ?></td>
                    <td class="text-center"><?php echo $data->agent_code; ?></td>
                    <td class="text-center"><?php echo $_in; ?></td>
                    <td class="text-center"><?php echo $_out; ?></td>
                    <td class="text-center"><?php echo $_stock; ?></td>
                    <td class="text-right"><?php echo $data->net_amount; ?></td>
                    <td class="text-right"><?php echo $_loanReceive; ?></td>
                    <td class="text-right"><?php echo $_loanRemain; ?></td>
                    <td class="text-right"><?php echo $_intReceive; ?></td>
                    <td class="text-right"><?php echo $_delvReceive; ?></td>
                    <td class="text-right"><?php echo $_totalReceive = ($_delvReceive + $_loanReceive + $_intReceive); ?></td>
                </tr>
                <?php
                $sum_in[] = $_in;
                $sum_out[] = $_out;
                $sum_stock[] = $_stock;
                $sum_net_amount[] = $data->net_amount;
                $sum_lrec[] = $_loanReceive;
                $sum_loan_remain[] = $_loanRemain;
                $sum_intrec[] = $_intReceive;
                $sum_drec[] = $_delvReceive;
                $sum_totalReceive[] = $_totalReceive;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th colspan="3" class="text-right"><?php echo Yii::t("strings", "Total"); ?></th>
                <th class="text-center"><?php echo array_sum($sum_in); ?></th>
                <th class="text-center"><?php echo array_sum($sum_out); ?></th>
                <th class="text-center"><?php echo array_sum($sum_stock); ?></th>
                <th class="text-right"><?php echo array_sum($sum_net_amount); ?></th>
                <th class="text-right"><?php echo array_sum($sum_lrec); ?></th>
                <th class="text-right"><?php echo array_sum($sum_loan_remain); ?></th>
                <th class="text-right"><?php echo array_sum($sum_intrec); ?></th>
                <th class="text-right"><?php echo array_sum($sum_drec); ?></th>
                <th class="text-right"><?php echo array_sum($sum_totalReceive); ?></th>
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
    <div class="alert alert-info">No records found!</div>
<?php endif; ?>