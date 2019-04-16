<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th><?php echo Yii::t('strings', 'Date'); ?></th>
                <th><?php echo Yii::t('strings', 'Customer'); ?></th>
                <th><?php echo Yii::t('strings', 'Sr No'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Advance'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Carrying'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Labor'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Others'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Total'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Actions'); ?></th>
                <?php if ($this->hasUserAccess('payment_delete')): ?>
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
                    <td><?php echo date('j M Y', strtotime($data->create_date)); ?></td>
                    <td><?php echo $data->customer->name; ?></td>
                    <td><?php echo $data->sr_no; ?></td>
                    <td class="text-right"><?php echo AppHelper::getFloat($data->payment->advance_amount); ?></td>
                    <td class="text-right"><?php echo AppHelper::getFloat($data->payment->carrying_cost); ?></td>
                    <td class="text-right"><?php echo AppHelper::getFloat($data->payment->labor_cost); ?></td>
                    <td class="text-right"><?php echo AppHelper::getFloat($data->payment->other_cost); ?></td>
                    <td class="text-right"><?php echo AppHelper::getFloat($data->payment->net_amount); ?></td>
                    <td class="text-center">
                        <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_PRODUCT_IN_VIEW, ['id' => $data->_key]); ?>" target="_blank"><?php echo Yii::t('strings', 'View'); ?></a>
                    </td>
                    <?php if ($this->hasUserAccess('payment_delete')): ?>
                        <td class="text-center" style="width:3%;"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"/></td>
                    <?php endif; ?>
                </tr>
                <?php
                $sum_advcost[] = $data->payment->advance_amount;
                $sum_ccost[] = $data->payment->carrying_cost;
                $sum_lcost[] = $data->payment->labor_cost;
                $sum_ocost[] = $data->payment->other_cost;
                $sum_tcost[] = $data->payment->net_amount;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th colspan="4" class="text-right"><?php echo Yii::t("strings", "Total"); ?></th>
                <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_advcost)); ?></th>
                <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_ccost)); ?></th>
                <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_lcost)); ?></th>
                <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_ocost)); ?></th>
                <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_tcost)); ?></th>
                <th colspan="1"></th>
                <?php if ($this->hasUserAccess('payment_delete')): ?>
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