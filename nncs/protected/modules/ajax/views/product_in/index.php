<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped tbl_invoice_view">
            <tr class="bg_gray">
                <th colspan="11">
                    <?php echo Yii::t("strings", "Total Quantity") . " = " . Stock::model()->sumTotal(); ?>
                    <span style="font-weight:400;margin-left:20px">[Prepared By : <?php echo $display_name; ?>&nbsp;{ stock = <?php echo $userStock; ?> }&nbsp;{ agent stock = <?php echo $agentTotal; ?> }&nbsp;<?php if (!empty($officeStock)) echo "{ Office Stock = {$officeStock} }"; ?>]</span>
                </th>
                <?php if ($this->hasUserAccess('entry_delete')): ?>
                    <th></th>
                <?php endif; ?>
            </tr>
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                <th style="width:10%;"><?php echo Yii::t("strings", "Date"); ?></th>
                <th style="width:15%;"><?php echo Yii::t("strings", "Customer"); ?></th>
                <th><?php echo Yii::t("strings", "Father Name"); ?></th>
                <th style="width:13%;"><?php echo Yii::t("strings", "Village"); ?></th>
                <th style="width:10%;"><?php echo Yii::t("strings", "Type"); ?></th>
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "SR No"); ?></th>
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "Qty"); ?></th>
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Loan Bag"); ?></th>
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Agent Code"); ?></th>
                <th class="text-center dis_print" style="width:9%;"><?php echo Yii::t("strings", "Actions"); ?></th>
                <?php if ($this->hasUserAccess('entry_delete')): ?>
                    <th class="text-center dis_print" style="width:3%;"><input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)"></th>
                <?php endif; ?>
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
                    <td><?php echo date("j M Y", strtotime($data->create_date)); ?></td>
                    <td><?php echo $data->customer->name; ?></td>
                    <td><?php echo!empty($data->customer->father_name) ? $data->customer->father_name : ""; ?></td>
                    <td><?php echo!empty($data->customer->village) ? $data->customer->village : ""; ?></td>
                    <td><?php echo!empty($data->type) ? ProductType::model()->findByPk($data->type)->name : ""; ?></td>
                    <td class="text-center"><?php echo $data->sr_no; ?></td>
                    <td class="text-center"><?php echo $data->quantity; ?></td>
                    <td class="text-center"><?php echo $data->loan_pack; ?></td>
                    <td class="text-center"><?php echo $data->agent_code; ?></td>
                    <td class="text-center dis_print">
                        <?php if ($this->hasUserAccess('entry_view')): ?>
                            <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_PRODUCT_IN_VIEW, ['id' => $data->_key]); ?>"><?php echo Yii::t("strings", "View"); ?></a>
                        <?php endif; ?>
                        <?php if ($this->hasUserAccess('entry_edit')): ?>
                            <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_PRODUCT_IN_EDIT, ['id' => $data->_key]); ?>"><?php echo Yii::t("strings", "Edit"); ?></a>
                        <?php endif; ?>
                    </td>
                    <?php if ($this->hasUserAccess('entry_delete')): ?>
                        <td class="text-center dis_print" style="width:3%;"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"></td>
                    <?php endif; ?>
                </tr>
                <?php
                $sum['qty'][] = $data->quantity;
                $sum['lp'][] = $data->loan_pack;
            endforeach;
            ?>
            <tr class="bg_gray dis_print">
                <th class="text-right" colspan="7"><?php echo Yii::t("strings", "Total"); ?></th>
                <th class="text-center"><?php echo array_sum($sum['qty']); ?></th>
                <th class="text-center"><?php echo array_sum($sum['lp']); ?></th>
                <th></th>
                <th class="dis_print"></th>
                <?php if ($this->hasUserAccess('entry_delete')): ?>
                    <th class="dis_print"></th>
                <?php endif; ?>
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