<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <tr id="r_checkAll" class="bg_gray">
                <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th style="width:10%;"><?php echo Yii::t('strings', 'Date'); ?></th>
                <th><?php echo Yii::t('strings', 'Customer'); ?></th>
                <th><?php echo Yii::t('strings', 'Agent'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Loan Bag Qty'); ?></th>
                <th class="text-center"><?php echo Yii::t('strings', 'Price Per Bag'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Loan Amount'); ?></th>
                <th class="text-right"><?php echo Yii::t('strings', 'Total Amount'); ?></th>
                <?php if ($this->hasUserAccess('loan_payment_delete')): ?>
                    <th class="text-center" style="width:3%;"><input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)"></th>
                <?php endif; ?>
            </tr>
            <?php
            $counter = 0;
            if (isset($_GET['page']) && $_GET['page'] > 1) {
                echo $counter = ($_GET['page'] - 1) * $pages->pageSize;
            }
            foreach ($dataset as $data):
                $counter++;
                ?>
                <tr>
                    <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                    <td style="width:10%;"><?php echo date("j M Y", strtotime($data->created)); ?></td>
                    <td><?php echo!empty($data->customer_id) ? $data->customer->name : ''; ?></td>
                    <td><?php echo!empty($data->agent_code) ? Agent::model()->find('code=:code', [':code' => $data->agent_code])->name : ''; ?></td>
                    <td class="text-center"><?php echo!empty($data->loanbag) ? $data->loanbag : ''; ?></td>
                    <td class="text-center"><?php echo!empty($data->cost_per_loanbag) ? $data->cost_per_loanbag : ''; ?></td>
                    <td class="text-right"><?php echo!empty($data->loan_amount) ? $data->loan_amount : ''; ?></td>
                    <td class="text-right"><?php echo!empty($data->total_loan_amount) ? $data->total_loan_amount : ''; ?></td>
                    <?php if ($this->hasUserAccess('loan_payment_delete')): ?>
                        <td class="text-center" style="width:3%;"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"></td>
                    <?php endif; ?>
                </tr>
                <?php
                $sum_qty[] = !empty($data->loanbag) ? $data->loanbag : '';
                $sum_amount[] = !empty($data->loan_amount) ? $data->loan_amount : '';
                $sum_total_amount[] = !empty($data->total_loan_amount) ? $data->total_loan_amount : '';
            endforeach;
            ?>
            <tr class="bg_gray">
                <th colspan="4" class="text-right"><?php echo Yii::t('strings', 'Total'); ?></th>
                <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                <th></th>
                <th class="text-right"><?php echo array_sum($sum_amount); ?></th>
                <th class="text-right"><?php echo array_sum($sum_total_amount); ?></th>
                <?php if ($this->hasUserAccess('loan_payment_delete')): ?>
                    <th></th>
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
            'nextPageLabel' => '>',
            'prevPageLabel' => '<',
            'selectedPageCssClass' => 'active ',
            'hiddenPageCssClass' => 'disabled ',
            'maxButtonCount' => 10,
            'htmlOptions' => array(
                'class' => 'pagination',
            )
        ));
        ?>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on("click", ".pagination li a", function(e) {
                showLoader("Processing...", true);
                var _form = $("#frmSearch");
                var _srcUrl = $(this).attr('href');

                $.ajax({
                    type: "POST",
                    url: _srcUrl,
                    data: _form.serialize(),
                    success: function(res) {
                        showLoader("", false);
                        $("#ajaxContent").html('');
                        $("#ajaxContent").html(res);
                    }
                });
                e.preventDefault();
            });
        });
    </script>
<?php else: ?>
    <div class="alert alert-info">No records found!</div>
<?php endif; ?>