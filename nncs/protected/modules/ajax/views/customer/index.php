<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th><?php echo Yii::t('strings', 'Name'); ?></th>
                <th><?php echo Yii::t('strings', 'Father Name'); ?></th>
                <th><?php echo Yii::t('strings', 'Mobile'); ?></th>
                <th><?php echo Yii::t('strings', 'Address'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Actions'); ?></th>
                <?php if ($this->hasUserAccess('customer_delete')): ?>
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
                <tr>
                    <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                    <td><?php echo AppHelper::getCleanValue($data->name); ?></td>
                    <td><?php echo AppHelper::getCleanValue($data->father_name); ?></td>
                    <td><?php echo AppHelper::getCleanValue($data->mobile); ?></td>
                    <td>
                        <?php
                        if (!empty($data->district)) {
                            echo "District : " . $data->district . "\r\n";
                        }
                        if (!empty($data->thana)) {
                            echo " | Thana : " . $data->thana . "\r\n";
                        }
                        echo " | Village : " . $data->village . "\r\n";
                        ?>
                    </td>
                    <td class="text-center">
                        <?php if ($this->hasUserAccess('customer_edit')): ?>
                            <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_CUSTOMER_EDIT, array('id' => $data->_key)); ?>"><?php echo Yii::t('strings', 'Edit'); ?></a>
                        <?php endif; ?>
                        <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_CUSTOMER_LOAN, ['id' => $data->_key]); ?>"><?php echo Yii::t('strings', 'Loans'); ?></a>
                    </td>
                    <?php if ($this->hasUserAccess('customer_delete')): ?>
                        <td class="text-center" style="width:3%;"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"></td>
                    <?php endif; ?>
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
            'prevPageLabel' => '<',
            'selectedPageCssClass' => 'active ',
            'hiddenPageCssClass' => 'disabled ',
            'maxButtonCount' => 5,
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