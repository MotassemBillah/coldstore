<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped tbl_invoice_view">
            <tr class="bg_gray">
                <th colspan="13">
                    <?php if (!empty($agentTotal)) : ?>
                        <span style="font-weight:400;margin-left:10px">[ agent stock = <?php echo $agentTotal; ?> ]</span>
                    <?php else: ?>
                        <?php echo Yii::t("strings", "Total Stock") . " = " . AppObject::sumStock(); ?>
                        <span style="margin-left:20px">Total Loan Pack : <?php echo ProductIn::model()->totalLoanPackGiven(); ?></span>
                        <span style="font-weight:400;margin-left:20px">[ Prepared By : <?php echo $display_name; ?>&nbsp;{ stock = <?php echo $userStock; ?> }&nbsp;<?php if (!empty($officeStock)) echo "{ Office Stock = {$officeStock} }"; ?> ]</span>
                    <?php endif; ?>
                </th>
            </tr>
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:4%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th><?php echo Yii::t("strings", "Date"); ?></th>
                <th><?php echo Yii::t("strings", "Customer"); ?></th>
                <th class="text-center" style="width:7%;"><?php echo Yii::t("strings", "SR No"); ?></th>
                <th><?php echo Yii::t("strings", "Type"); ?></th>
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "In"); ?></th>
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "Out"); ?></th>
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "Stock"); ?></th>
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Loan Bag"); ?></th>
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Bag Paid"); ?></th>
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Bag Remain"); ?></th>
                <th style="width:15%"><?php echo Yii::t("strings", "Pallot"); ?></th>
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Agent Code"); ?></th>
            </tr>
            <?php
            $counter = 0;
            if (isset($_GET['page']) && $_GET['page'] > 1) {
                $counter = ($_GET['page'] - 1) * $pages->pageSize;
            }
            foreach ($dataset as $data):
                $counter++;
                $agent = !empty($data->agent_code) ? Agent::model()->find('code=:code', [':code' => $data->agent_code]) : '';
                ?>
                <tr class="">
                    <td class="text-center"><?php echo $counter; ?></td>
                    <td><?php echo date("j M Y", strtotime($data->create_date)); ?></td>
                    <td><?php echo $data->customer->name; ?></td>
                    <td class="text-center"><?php echo!empty($data->sr_no) ? $data->sr_no : ""; ?></td>
                    <td><?php echo!empty($data->type) ? ProductType::model()->findByPk($data->type)->name : ""; ?></td>
                    <td class="text-center"><?php echo!empty($data->quantity) ? $data->quantity : ""; ?></td>
                    <td class="text-center"><?php echo AppObject::stockOut($data->sr_no); ?></td>
                    <td class="text-center"><?php echo AppObject::currentStock($data->sr_no); ?></td>
                    <td class="text-center"><?php echo AppObject::loanPackIn($data->sr_no); ?></td>
                    <td class="text-center"><?php echo AppObject::loanPackOut($data->sr_no); ?></td>
                    <td class="text-center"><?php echo AppObject::loanPackStock($data->sr_no); ?></td>
                    <td></td>
                    <td class="text-center"><?php echo!empty($agent) ? $agent->code : ''; ?></td>
                </tr>
                <?php
                $sum['in'][] = $data->quantity;
                $sum['out'][] = AppObject::stockOut($data->sr_no);
                $sum['total'][] = AppObject::currentStock($data->sr_no);
                $sum['lp_in'][] = AppObject::loanPackIn($data->sr_no);
                $sum['lp_out'][] = AppObject::loanPackOut($data->sr_no);
                $sum['lp_total'][] = AppObject::loanPackStock($data->sr_no);
            endforeach;
            ?>
            <tr class="bg_gray dis_print">
                <th class="text-right" colspan="5"><?php echo Yii::t("strings", "Total"); ?></th>
                <th class="text-center"><?php echo array_sum($sum['in']); ?></th>
                <th class="text-center"><?php echo array_sum($sum['out']); ?></th>
                <th class="text-center"><?php echo array_sum($sum['total']); ?></th>
                <th class="text-center"><?php echo array_sum($sum['lp_in']); ?></th>
                <th class="text-center"><?php echo array_sum($sum['lp_out']); ?></th>
                <th class="text-center"><?php echo array_sum($sum['lp_total']); ?></th>
                <th></th>
                <th></th>
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