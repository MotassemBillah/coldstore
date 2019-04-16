<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered tbl_invoice_view">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                <th style="width:12%;"><?php echo Yii::t("strings", "Date"); ?></th>
                <th class="text-center" style="width:12%;"><?php echo Yii::t("strings", "SR Number"); ?></th>
                <th class="text-center" style="width:12%;"><?php echo Yii::t("strings", "Quantity"); ?></th>
                <th class="text-center" style="width:15%;"><?php echo Yii::t('strings', 'Author'); ?></th>
                <th class="text-right"><?php echo Yii::t("strings", "Amount"); ?></th>
                <th class="text-center dis_print" style="width:10%;"><?php echo Yii::t("strings", "Actions"); ?></th>
                <?php if ($this->hasUserAccess('delivery_delete')): ?>
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
                    <td><?php echo date("j M Y", strtotime($data->delivery_date)); ?></td>
                    <td class="text-center"><?php echo $data->sr_no; ?></td>
                    <td class="text-center"><?php echo $data->sumQty; ?></td>
                    <td class="text-center"><?php echo User::model()->displayname($data->created_by); ?></td>
                    <td class="text-right"><?php echo AppHelper::getFloat($data->sumAmount); ?></td>
                    <td class="text-center dis_print">
                        <?php if ($this->hasUserAccess('delivery_view')): ?>
                            <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_DELIVERY_VIEW, ['id' => $data->_key]); ?>"><?php echo Yii::t('strings', 'View'); ?></a>
                        <?php endif; ?>
                        <?php if ($this->hasUserAccess('delivery_edit')): ?>
                            <a class="btn btn-info btn-xs" href="#"><?php echo Yii::t("strings", "Edit"); ?></a>
                        <?php endif; ?>
                    </td>
                    <?php if ($this->hasUserAccess('delivery_delete')): ?>
                        <td class="text-center dis_print"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"></td>
                    <?php endif; ?>
                </tr>
                <?php
                $sum_qty_total[] = $data->sumQty;
                $sum_net_total[] = $data->sumAmount;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th colspan="3" class="text-right">Total</th>
                <th class="text-center"><?php echo array_sum($sum_qty_total); ?></th>
                <th></th>
                <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_net_total)); ?></th>
                <th class="text-center dis_print"></th>
                <?php if ($this->hasUserAccess('delivery_delete')): ?>
                    <th class="text-center dis_print"></th>
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
