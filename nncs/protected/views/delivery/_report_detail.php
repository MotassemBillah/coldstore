<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered tbl_invoice_view">
            <tr>
                <th colspan="16">
                    <span style="display:inline-block;">রিপোর্টিং তারিখ</span>
                    <span style="display:inline-block;margin-left:25px;"><?php echo date('d-m-y', strtotime($dateForm)); ?></span>
                    <span style="display:inline-block;margin:0 25px;">To</span>
                    <span style="display:inline-block;"><?php echo date('d-m-y', strtotime($dateTo)); ?></span>
                </th>
            </tr>
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                <th class="text-center"><?php echo Yii::t("strings", "Receipt No"); ?></th>
                <th class="text-center"><?php echo Yii::t("strings", "SR No"); ?></th>
                <th><?php echo Yii::t("strings", "Customer"); ?></th>
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
                <th class="text-center dis_print" style="width:8%;"><?php echo Yii::t("strings", "Actions"); ?></th>
                <th class="text-center dis_print" style="width:3%;">
                    <?php if ($this->hasUserAccess('delivery_delete')): ?>
                        <input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)">
                    <?php endif; ?>
                </th>
            </tr>
            <?php
            $counter = 0;
            if (isset($_GET['page']) && $_GET['page'] > 1) {
                $counter = ($_GET['page'] - 1) * $pages->pageSize;
            }
            foreach ($dataset as $data):
                $counter++;
                $_loan = LoanReceiveItem::model()->loanByDelivery($data->delivery_number);
                $_interest = LoanReceiveItem::model()->interestByDelivery($data->delivery_number);
                $_others = 0;
                ?>
                <tr class="">
                    <td class="text-center"><?php echo $counter; ?></td>
                    <td class="text-center"><?php echo $data->delivery_number; ?></td>
                    <td class="text-center"><?php echo $data->sr_no; ?></td>
                    <td><?php echo $data->customer->name; ?></td>
                    <td class="text-center"><?php echo $data->quantity; ?></td>
                    <td class="text-right"><?php echo $data->rent_total; ?></td>
                    <td class="text-right"><?php echo $_loan; ?></td>
                    <td class="text-right"><?php echo $_interest; ?></td>
                    <td class="text-center"><?php echo $data->loan_bag; ?></td>
                    <td class="text-right"><?php echo $data->loan_bag_price_total; ?></td>
                    <td class="text-right"><?php echo $data->carrying; ?></td>
                    <td class="text-right"><?php echo $data->fan_charge_total; ?></td>
                    <td class="text-right"><?php echo $_others; ?></td>
                    <td class="text-right"><?php echo $_total = ($data->rent_total + $_loan + $_interest + $data->loan_bag_price_total + $data->carrying + $data->fan_charge_total + $_others); ?></td>
                    <td class="text-center dis_print">
                        <?php if ($this->hasUserAccess('delivery_view')): ?>
                            <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_DELIVERY_VIEW, ['id' => $data->reff->_key]); ?>"><?php echo Yii::t('strings', 'View'); ?></a>
                        <?php endif; ?>
                        <?php if ($this->hasUserAccess('delivery_edit')): ?>
                            <a class="btn btn-info btn-xs" href="#"><?php echo Yii::t("strings", "Edit"); ?></a>
                        <?php endif; ?>
                    </td>
                    <td class="text-center dis_print">
                        <?php if ($this->hasUserAccess('delivery_delete')): ?>
                            <input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check">
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
                $sum_qty[] = $data->quantity;
                $sum_rent_total[] = $data->rent_total;
                $sum_loan[] = $_loan;
                $sum_interest[] = $_interest;
                $sum_loan_bag[] = $data->loan_bag;
                $sum_loan_bag_amount[] = $data->loan_bag_price_total;
                $sum_carrying[] = $data->carrying;
                $sum_fan_charge_total[] = $data->fan_charge_total;
                $sum_net_total[] = $_total;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th class="text-right" colspan="4">Total</th>
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
                <th class="dis_print" colspan="2"></th>
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