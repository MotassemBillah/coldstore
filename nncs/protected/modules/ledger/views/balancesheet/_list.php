<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped tbl_invoice_view">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:5%;"><?= Yii::t("strings", "SL#"); ?></th>
                <th><?= Yii::t("strings", "Date"); ?></th>
                <th class="text-right" style="width:15%;"><?= Yii::t("strings", "Debit"); ?></th>
                <th class="text-right" style="width:15%;"><?= Yii::t("strings", "Credit"); ?></th>
                <th class="text-right" style="width:15%;"><?= Yii::t("strings", "Balance"); ?></th>
            </tr>
            <?php
            $counter = 0;
            if (isset($_GET['page']) && $_GET['page'] > 1) {
                $counter = ($_GET['page'] - 1) * $pages->pageSize;
            }
            foreach ($dataset as $data):
                $counter++;
                ?>
                <tr>
                    <td class="text-center"><?= $counter; ?></td>
                    <td ><?= date("j M Y", strtotime($data->created)); ?></td>
                    <td class="text-right"><?= AppHelper::getFloat($data->debit); ?></td>
                    <td class="text-right"><?= AppHelper::getFloat($data->credit); ?></td>
                    <td class="text-right"><?= AppHelper::getFloat($data->balance); ?></td>
                </tr>
                <?php
                $sum_dbt[] = $data->debit;
                $sum_cdt[] = $data->credit;
                $sum_blance[] = $data->balance;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th class="text-right" colspan="2"><?= Yii::t("strings", "Total"); ?></th>
                <th class="text-right"><?= AppHelper::getFloat(array_sum($sum_dbt)); ?></th>
                <th class="text-right"><?= AppHelper::getFloat(array_sum($sum_cdt)); ?></th>
                <th class="text-right"><?= AppHelper::getFloat(array_sum($sum_blance)); ?></th>
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
    <div class="alert alert-info"><?php echo Yii::t("strings", "No records found!"); ?></div>
<?php endif; ?>