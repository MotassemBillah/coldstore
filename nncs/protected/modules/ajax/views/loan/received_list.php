<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <tr id="r_checkAll">
                <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th style="width:10%;"><?php echo Yii::t('strings', 'Date'); ?></th>
                <th><?php echo Yii::t('strings', 'Customer'); ?></th>
                <th><?php echo Yii::t('strings', 'Sr No'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Quantity'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Loan Per Qty'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Amount'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Days'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Interest'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Total Amount'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Status'); ?></th>
                <?php if ($this->hasUserAccess('loan_payment_receive')): ?>
                    <th class="text-center" style="width:3%;"><input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)"></th>
                <?php endif; ?>
            </tr>
            <?php
            $counter = 0;
            if (isset($_GET['page']) && $_GET['page'] > 1) {
                echo $counter = ($_GET['page'] - 1) * $pages->pageSize;
            }
            foreach ($dataset as $data):
                $counter++;
                $days = AppHelper::get_day_diff($data->create_date, date('Y-m-d'));
                ?>
                <tr>
                    <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                    <td style="width:10%;"><?php echo date("j M Y", strtotime($data->create_date)); ?></td>
                    <td><?php echo $data->customer->name; ?></td>
                    <td><?php echo $data->sr_no; ?></td>
                    <td class="text-center"><?php echo $data->quantity; ?></td>
                    <td class="text-center"><?php echo $data->cost_per_qty; ?></td>
                    <td class="text-right"><?php echo $data->loan_amount; ?></td>
                    <td class="text-right"><?php echo $days; ?></td>
                    <td class="text-right"><?php echo $data->interest_amount; ?></td>
                    <td class="text-right"><?php echo $data->total_loan_amount; ?></td>
                    <td class="text-center">
                        <?php
                        if ($data->status == AppConstant::ORDER_COMPLETE) {
                            echo "<span class='label label-success'>{$data->status}</span>";
                        } else {
                            echo "<span class='label label-warning'>{$data->status}</span>";
                        }
                        ?>
                    </td>
                    <?php if ($this->hasUserAccess('loan_payment_receive')): ?>
                        <td class="text-center" style="width:3%;"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"></td>
                    <?php endif; ?>
                </tr>
                <?php
                $sum_qty[] = $data->quantity;
                $sum_rent[] = $data->cost_per_qty;
                $sum_amount[] = $data->loan_amount;
                $sum_interest[] = $data->interest_amount;
                $sum_loan_amount[] = $data->total_loan_amount;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th colspan="4" class="text-right"><?php echo Yii::t('strings', 'Total'); ?></th>
                <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                <th></th>
                <th class="text-right"><?php echo array_sum($sum_amount); ?></th>
                <th class="text-right"></th>
                <th class="text-right"><?php echo array_sum($sum_interest); ?></th>
                <th class="text-right"><?php echo array_sum($sum_loan_amount); ?></th>
                <th colspan="1"></th>
                <?php if ($this->hasUserAccess('loan_payment_receive')): ?>
                    <th><button id="get_payment" class="btn btn-info btn-xs" type="button"><?php echo Yii::t('strings', 'Get Payment'); ?></button></th>
                <?php endif; ?>
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
            'nextPageLabel' => '>',
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