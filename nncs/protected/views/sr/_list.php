<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped tbl_invoice_view">
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                <th class="text-center" style="width:10%;"><?php echo Yii::t("strings", "SR No"); ?></th>
                <th class="text-center" style="width:10%;"><?php echo Yii::t("strings", "Lot No"); ?></th>
                <th style=""><?php echo Yii::t("strings", "Customer"); ?></th>
                <th style=""><?php echo Yii::t("strings", "Father Name"); ?></th>
                <th style=""><?php echo Yii::t("strings", "Village"); ?></th>
                <th class="text-center dis_print" style="width:10%;"><?php echo Yii::t("strings", "Actions"); ?></th>
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
                    <td class="text-center"><?php echo $data->sr_no; ?></td>
                    <td class="text-center"><?php echo $data->lot_no; ?></td>
                    <td><?php echo $data->customer->name; ?></td>
                    <td><?php echo!empty($data->customer->father_name) ? $data->customer->father_name : ""; ?></td>
                    <td><?php echo!empty($data->customer->village) ? $data->customer->village : ""; ?></td>
                    <td class="text-center dis_print">
                        <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_SR_VIEW, ['id' => $data->sr_no]); ?>"><?php echo Yii::t("strings", "View"); ?></a>
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