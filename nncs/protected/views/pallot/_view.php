<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered tbl_invoice_view">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th><?php echo Yii::t('strings', 'Date'); ?></th>
                <th><?php echo Yii::t('strings', 'SR Number'); ?></th>
                <th><?php echo Yii::t('strings', 'Pallot Number'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Pockets'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Quantity'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Author'); ?></th>
                <th class="text-center" style="width:15%;"><?php echo Yii::t('strings', 'Actions'); ?></th>
                <th class="text-center" style="width:4%;"><input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)"></th>
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
                    <td><?php echo date('j M Y', strtotime($data->pallot_date)); ?></td>
                    <td><?php echo $data->sr_no; ?></td>
                    <td><?php echo $data->pallot_number; ?></td>
                    <td class="text-center"><?php echo count($data->items); ?></td>
                    <td class="text-center"><?php echo $data->sum_qty; ?></td>
                    <td class="text-center"><?php echo User::model()->displayname($data->created_by); ?></td>
                    <td class="text-center">
                        <a href="#" class="btn btn-info btn-xs">Edit</a>
                        <a href="#" class="btn btn-primary btn-xs">View</a>
                    </td>
                    <td class="text-center">
                        <?php if ($this->hasUserAccess('pallot_delete')): ?>
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
    <div class="alert alert-info"><?php echo Yii::t('strings', 'No records found!'); ?></div>
<?php endif; ?>