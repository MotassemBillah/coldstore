<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th><?php echo Yii::t('strings', 'Date'); ?></th>
                <th><?php echo Yii::t('strings', 'Type'); ?></th>
                <th><?php echo Yii::t('strings', 'Sr No'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Quantity'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Quantity Price'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Qty Price Total'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Actions'); ?></th>
                <?php if ($this->hasUserAccess('loading_payment_delete')): ?>
                    <th class="text-center" style="width:3%;"><input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)"></th>
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
                    <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                    <td><?php echo date('j M Y', strtotime($data->created)); ?></td>
                    <td><?php echo ucfirst($data->pament_for); ?></td>
                    <td><?php echo $data->sr_no; ?></td>
                    <td class="text-center"><?php echo $data->quantity; ?></td>
                    <td class="text-right"><?php echo $data->quantity_price; ?></td>
                    <td class="text-right"><?php echo AppHelper::getFloat($data->price_total); ?></td>
                    <td class="text-center">
                        <?php if ($this->hasUserAccess('loading_payment_edit')): ?>
                            <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_PAYMENT_LOADING_EDIT, ['id' => $data->_key]); ?>"><?php echo Yii::t('strings', 'Edit'); ?></a>
                        <?php endif; ?>
                    </td>
                    <?php if ($this->hasUserAccess('loading_payment_delete')): ?>
                        <td class="text-center" style="width:3%;"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"></td>
                    <?php endif; ?>
                </tr>
                <?php
                $sum_qty[] = $data->quantity;
                $sum_qty_cost[] = $data->quantity_price;
                $sum_cost_total[] = $data->price_total;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th colspan="4" class="text-right"><?php echo Yii::t("strings", "Total"); ?></th>
                <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                <th class="text-right"></th>
                <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_cost_total)); ?></th>
                <th></th>
                <?php if ($this->hasUserAccess('loading_payment_delete')): ?>
                    <th colspan="1"></th>
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
    <div class="alert alert-info"><?php echo Yii::t('strings', 'No records found!'); ?></div>
<?php endif; ?>