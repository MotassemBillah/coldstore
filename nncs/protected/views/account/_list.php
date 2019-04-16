<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered tbl_invoice_view">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                <th><?php echo Yii::t("strings", "Bank Name"); ?></th>
                <th><?php echo Yii::t("strings", "Account Name"); ?></th>
                <th><?php echo Yii::t("strings", "Account Number"); ?></th>
                <th><?php echo Yii::t("strings", "Account Type"); ?></th>
                <th class="text-center"><?php echo Yii::t("strings", "Actions"); ?></th>
                <th class="text-center" style="width:3%;">
                    <?php if ($this->hasUserAccess('account_delete')): ?>
                        <input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)">
                    <?php endif; ?>
                </th>
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
                    <td><?php echo AppObject::getBankName($data->bank_id); ?></td>
                    <td><?php echo AppHelper::getCleanValue($data->account_name); ?></td>
                    <td><?php echo AppHelper::getCleanValue($data->account_number); ?></td>
                    <td><?php echo AppHelper::getCleanValue($data->account_type); ?></td>
                    <td class="text-center">
                        <?php if ($this->hasUserAccess('account_edit')): ?>
                            <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_ACCOUNT_EDIT, ['id' => $data->_key]); ?>"><?php echo Yii::t("strings", "Edit"); ?></a>
                        <?php endif; ?>
                        <?php if ($this->hasUserAccess('account_balance')): ?>
                            <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_ACCOUNT_BALANCE, ['id' => $data->id]); ?>"><?php echo Yii::t('strings', 'Balance'); ?></a>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if ($this->hasUserAccess('account_delete')): ?>
                            <input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check">
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
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