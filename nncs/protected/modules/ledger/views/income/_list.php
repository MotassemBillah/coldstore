<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr id="r_checkAll" class="bg_gray">
                <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th><?php echo Yii::t('strings', 'Date'); ?></th>
                <th><?php echo Yii::t("strings", "Customer"); ?></th>
                <th><?php echo Yii::t('strings', 'Method'); ?></th>
                <th><?php echo Yii::t('strings', 'Purpose'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Amount'); ?></th>
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
                    <td><?php echo date('j M Y', strtotime($data->pay_date)); ?></td>
                    <td><?php echo $data->customer->name; ?></td>
                    <td>
                        <?php
                        if ($data->payment_mode == AppConstant::PAYMENT_CHECK) {
                            echo $data->payment_mode . "<br>";
                            echo "<u>Bank</u>: " . $data->bank_name . "<br>";
                            echo "<u>Check No</u>: " . $data->check_no;
                        } else if ($data->payment_mode == AppConstant::PAYMENT_CASH) {
                            echo "<span style='color:forestgreen'>" . $data->payment_mode . "</span>";
                        } else {
                            echo "<span style='color:grey'>" . AppConstant::PAYMENT_NO . "</span>";
                        }
                        ?>
                    </td>
                    <td><?php echo "<span style='color:grey'>Invoice Payment</span>"; ?></td>
                    <td class="text-right bg_gray"><?php echo $data->paid_amount; ?></td>
                </tr>
                <?php
                $sum_amount[] = $data->paid_amount;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th colspan="4"></th>
                <th class=""><?php echo Yii::t("strings", "Total Amount"); ?></th>
                <th colspan="1" class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_amount)); ?></th>
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
    <div class="alert alert-info"><?php echo Yii::t("strings", "No records found!"); ?></div>
<?php endif; ?>