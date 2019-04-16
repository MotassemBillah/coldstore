<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped tbl_invoice_view">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                <th style="width:10%;"><?php echo Yii::t("strings", "Date"); ?></th>
                <th style="width:15%;"><?php echo Yii::t("strings", "Customer"); ?></th>
                <th><?php echo Yii::t("strings", "Father Name"); ?></th>
                <th style="width:15%;"><?php echo Yii::t("strings", "Village"); ?></th>
                <th style="width:10%;"><?php echo Yii::t("strings", "Type"); ?></th>
                <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "SR No"); ?></th>
                <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "Qty"); ?></th>
                <th class="text-right" style="width:10%;"><?php echo Yii::t("strings", "Loan"); ?>(tk)</th>
                <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "Status"); ?></th>
                <th class="text-center dis_print" style="width:5%;"><?php echo Yii::t("strings", "Actions"); ?></th>
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
                    <td><?php echo date("j M Y", strtotime($data->create_date)); ?></td>
                    <td><?php echo $data->customer->name; ?></td>
                    <td><?php echo!empty($data->customer->father_name) ? $data->customer->father_name : ""; ?></td>
                    <td><?php echo!empty($data->customer->village) ? $data->customer->village : ""; ?></td>
                    <td><?php echo!empty($data->type) ? ProductType::model()->findByPk($data->type)->name : ""; ?></td>
                    <td class="text-center"><?php echo $data->sr_no; ?></td>
                    <td class="text-center"><?php echo $data->qty; ?></td>
                    <td class="text-right"><?php echo AppHelper::getFloat($data->net_amount); ?></td>
                    <td class="text-center" style="color:<?php echo ($_sts == AppConstant::ORDER_PENDING) ? 'red' : 'green'; ?>"><?php echo $_sts; ?></td>
                    <td class="text-center dis_print">
                        <a class="btn btn-primary btn-xs" href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOAN_PAYMENT_VIEW_SINGLE, ['id' => $data->id]); ?>"><?php echo Yii::t('strings', 'View'); ?></a>
                    </td>
                </tr>
                <?php
                $sum_qty[] = $data->qty;
                $sum_amount[] = $data->net_amount;
            endforeach;
            ?>
            <tr class="bg_gray dis_print">
                <th class="text-right" colspan="7"><?php echo Yii::t("strings", "Total"); ?></th>
                <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_amount)); ?></th>
                <th></th>
                <th class="dis_print"></th>
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
            )
        ));
        ?>
    </div>
<?php else: ?>
    <div class="alert alert-info">No records found!</div>
<?php endif; ?>