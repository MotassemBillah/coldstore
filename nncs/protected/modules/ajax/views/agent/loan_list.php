<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <tr class="bg_gray">
                <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th>Date</th>
                <th>SR Number</th>
                <th class="text-center">Agent Code</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Loan Per Qty</th>
                <th class="text-right">Total</th>
            </tr>
            <?php
            $counter = 0;
            if (isset($_GET['page']) && $_GET['page'] > 1) {
                $counter = ($_GET['page'] - 1) * $pages->pageSize;
            }
            foreach ($dataset as $item):
                $counter++;
                ?>
                <tr>
                    <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                    <td><?php echo date("j M Y", strtotime($item->created)); ?></td>
                    <td><?php echo $item->sr_no; ?></td>
                    <td class="text-center"><?php echo!empty($item->agent_code) ? $item->agent_code : ''; ?></td>
                    <td class="text-center"><?php echo $item->qty; ?></td>
                    <td class="text-center"><?php echo $item->qty_cost; ?></td>
                    <td class="text-right"><?php echo AppHelper::getFloat($item->net_amount); ?></td>
                </tr>
                <?php
                $sum_qty[] = $item->qty;
                $sum_amount[] = $item->net_amount;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th colspan="4">Sum Total</th>
                <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                <th></th>
                <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_amount)); ?></th>
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