<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered tbl_invoice_view">
            <tr id="r_checkAll" class="bg_gray">
                <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th><?php echo Yii::t('strings', 'Name'); ?></th>
                <th><?php echo Yii::t('strings', 'Quantity In'); ?></th>
                <th><?php echo Yii::t('strings', 'Quantity Out'); ?></th>
                <th><?php echo Yii::t('strings', 'Quantity Remain'); ?></th>
                <th class="text-center dis_print" style="width:10%;"><?php echo Yii::t('strings', 'Actions'); ?></th>
            </tr>
            <?php
            $counter = 0;
            if (isset($_GET['page']) && $_GET['page'] > 1) {
                $counter = ($_GET['page'] - 1) * $pages->pageSize;
            }
            foreach ($dataset as $data):
                $counter++;
                $_out = DeliveryItem::model()->sumQtyOfType($data->id);
                $_remain = ($data->sumAmount - $_out);
                ?>
                <tr>
                    <td class="text-center"><?php echo $counter; ?></td>
                    <td><?php echo AppHelper::getCleanValue($data->name); ?></td>
                    <td><?php echo $data->sumAmount; ?></td>
                    <td><?php echo $_out; ?></td>
                    <td><?php echo $_remain; ?></td>
                    <td class="text-center dis_print">
                        <?php if ($this->hasUserAccess('product_type_edit')): ?>
                            <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_PRODUCT_TYPE_EDIT, ['id' => $data->_key]); ?>"><?php echo Yii::t('strings', 'Edit'); ?></a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
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