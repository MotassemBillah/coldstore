<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered tbl_invoice_view">
            <tr class="bg_gray">
                <th colspan="7">
                    <span style="font-weight:400;">[Prepared By : <?php echo $display_name; ?>]</span><span style="font-weight:400;margin-left:20px;">[Total Loan Given : <?php echo AppHelper::getFloat(LoanItem::model()->sumTotal()); ?>&nbsp;TK]</span>
                </th>
            </tr>
            <tr id="r_checkAll">
                <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th style="width:12%;"><?php echo Yii::t('strings', 'Date'); ?></th>
                <th class="text-center" style="width:12%;"><?php echo Yii::t('strings', 'Loan Number'); ?></th>
                <th class="text-center" style="width:12%;"><?php echo Yii::t('strings', 'Items'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Amount'); ?></th>
                <th class="text-center dis_print" style="width:10%;"><?php echo Yii::t('strings', 'Actions'); ?></th>
                <th class="text-center dis_print" style="width:3%;">
                    <?php if ($this->hasUserAccess('loan_payment_delete')): ?>
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
                    <td><?php echo date("j M Y", strtotime($data->created)); ?></td>
                    <td class="text-center"><?php echo $data->case_no; ?></td>
                    <td class="text-center"><?php echo count($data->items); ?></td>
                    <td class="text-right"><?php echo AppHelper::getFloat($data->sumAmount); ?></td>
                    <td class="text-center dis_print">
                        <?php if ($this->hasUserAccess('loan_payment_edit')): ?>
                            <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_LOAN_PAYMENT_EDIT, ['id' => $data->_key]); ?>"><?php echo Yii::t('strings', 'Edit'); ?></a>
                        <?php endif; ?>
                        <?php if ($this->hasUserAccess('loan_payment_view')): ?>
                            <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_LOAN_PAYMENT_VIEW, ['id' => $data->_key]); ?>"><?php echo Yii::t('strings', 'View'); ?></a>
                        <?php endif; ?>
                    </td>
                    <td class="text-center dis_print">
                        <?php if ($this->hasUserAccess('loan_payment_delete')): ?>
                            <input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check">
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
                $sum_net_total[] = $data->sumAmount;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th colspan="4" class="text-right">Total</th>
                <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_net_total)); ?></th>
                <th class="text-center dis_print" colspan="2"></th>
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
            'nextPageLabel' => '>',
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