<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered tbl_invoice_view">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                <th><?php echo Yii::t("strings", "Date"); ?></th>
                <th><?php echo Yii::t("strings", "Purpose"); ?></th>
                <th><?php echo Yii::t("strings", "Person"); ?></th>
                <th class="text-right"><?php echo Yii::t("strings", "Debit"); ?></th>
                <th class="text-right"><?php echo Yii::t("strings", "Credit"); ?></th>
                <th class="text-right"><?php echo Yii::t("strings", "Balance"); ?></th>
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
                    <td class="text-center"><?php echo $counter; ?></td>
                    <td><?php echo date('j M Y', strtotime($data->last_update)); ?></td>
                    <td><?php echo AppHelper::getCleanValue($data->purpose); ?></td>
                    <td><?php echo AppHelper::getCleanValue($data->by_whom); ?></td>
                    <td class="text-right"><?php echo AppHelper::getFloat($data->debit); ?></td>
                    <td class="text-right"><?php echo AppHelper::getFloat($data->credit); ?></td>
                    <td class="text-right"><?php echo AppHelper::getFloat($data->balance); ?></td>
                </tr>
                <?php
                $sum_dbt[] = $data->debit;
                $sum_crdt[] = $data->credit;
                $sum_blance[] = $data->balance;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th colspan="4" class="text-right"><?php echo Yii::t("strings", "Total"); ?></th>
                <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_dbt)); ?></th>
                <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_crdt)); ?></th>
                <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_blance)); ?></th>
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
    <div class="alert alert-info">No records found!</div>
<?php endif; ?>