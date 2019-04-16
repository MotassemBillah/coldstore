<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered tbl_invoice_view">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th><?php echo Yii::t('strings', 'Name'); ?></th>
                <th><?php echo Yii::t('strings', 'Father Name'); ?></th>
                <th><?php echo Yii::t('strings', 'Mobile'); ?></th>
                <th><?php echo Yii::t('strings', 'Address'); ?></th>
                <th class="text-center" style="width:6%;"><?php echo Yii::t('strings', 'Code'); ?></th>
                <th class="text-center" style="width:7%;"><?php echo Yii::t('strings', 'Quantity'); ?></th>
                <th class="text-center dis_print"><?php echo Yii::t('strings', 'Actions'); ?></th>
                <?php if ($this->hasUserAccess('agent_delete')): ?>
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
                <tr>
                    <td class="text-center"><?php echo $counter; ?></td>
                    <td><?php echo AppHelper::getCleanValue($data->name); ?></td>
                    <td><?php echo AppHelper::getCleanValue($data->father_name); ?></td>
                    <td><?php echo AppHelper::getCleanValue($data->mobile); ?></td>
                    <td>
                        <?php
                        echo!empty($data->zila) ? "District: " . $data->zila : '';
                        echo!empty($data->upozila) ? " | Upozila: " . $data->upozila : '';
                        echo!empty($data->village) ? " | Village: " . $data->village : '';
                        ?>
                    </td>
                    <td class="text-center"><?php echo $data->code; ?></td>
                    <td class="text-center"><?php echo AppObject::stockOfAgent($data->code); ?></td>
                    <td class="text-center dis_print">
                        <?php if ($this->hasUserAccess('agent_edit')): ?>
                            <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_AGENT_EDIT, array('id' => $data->_key)); ?>"><?php echo Yii::t('strings', 'Edit'); ?></a>
                        <?php endif; ?>
                        <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_AGENT_LEDGER, ['id' => $data->_key]); ?>"><?php echo Yii::t('strings', 'Ledger'); ?></a>
                    </td>
                    <?php if ($this->hasUserAccess('agent_delete')): ?>
                        <td class="text-center dis_print"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"></td>
                    <?php endif; ?>
                </tr>
                <?php
                $sum_qty[] = AppObject::stockOfAgent($data->code);
            endforeach;
            ?>
            <tr class="bg_gray">
                <th class="text-right" colspan="6">Total Quantity</th>
                <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                <th></th>
                <?php if ($this->hasUserAccess('agent_delete')): ?>
                    <th></th>
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