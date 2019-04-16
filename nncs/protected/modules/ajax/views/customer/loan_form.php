<?php
if (!empty($dataset) && count($dataset) > 0) :
    foreach ($dataset as $sdt) {
        $currentStock[] = AppObject::currentStock($sdt->sr_no);
    }
    ?>
    <div class="row clearfix">
        <div class="col-md-3 col-sm-3">
            <div class="form-group">
                <input type="hidden" id="curStock" name="curStock" value="<?php echo array_sum($currentStock); ?>">
                <input type="hidden" id="agent_code" name="agent_code" value="<?php echo!empty($dataset[0]->agent_code) ? $dataset[0]->agent_code : ''; ?>">
                <label class=""><?php echo Yii::t("strings", "Total Stock"); ?>: <?php echo array_sum($currentStock); ?></label>
                <div class="input-group">
                    <input type="text" id="pay_date" name="pay_date" class="form-control" value="<?php echo date('d-m-Y'); ?>" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-3">
            <div class="form-group">
                <label>Loan Per Quantity</label>
                <input type="text" id="cost_per_qty" name="cost_per_qty" class="form-control" oninput="multiply_value(this, '#total_loan_amount')" data-target="#curStock">
            </div>
        </div>
        <div class="col-md-3 col-sm-3">
            <div class="form-group">
                <label>Loan Paid Amount</label>
                <input type="text" id="total_loan_given" name="total_loan_given" class="form-control" oninput="devide_value(this, '#cost_per_qty')" data-target="#curStock">
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr class="bg_gray">
                <th class="text-center"><?php echo Yii::t("strings", "SL#"); ?></th>
                <th><?php echo Yii::t("strings", "Sr Number"); ?></th>
                <th><?php echo Yii::t("strings", "Type"); ?></th>
                <th><?php echo Yii::t("strings", "Loan Taken Qty"); ?></th>
                <th><?php echo Yii::t("strings", "Applied Quantity"); ?></th>
                <th><?php echo Yii::t("strings", "Amount Per Qty"); ?></th>
                <th><?php echo Yii::t("strings", "Loan Amount"); ?></th>
            </tr>
            <?php
            $counter = 0;
            foreach ($dataset as $data):
                $counter++;
                $product_type = !empty($data->type) ? ProductType::model()->findByPk($data->type)->name : '';
                $stock = AppObject::currentStock($data->sr_no);
                $taken = AppObject::loanTakenQty($data->sr_no);
                $restQty = $stock - $taken;
                if ($restQty > 0):
                    ?>
                    <tr>
                        <td class="no_pad text-center" style="vertical-align: middle">
                            <input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" style="height:20px;width:20px;">
                            <input type="hidden" name="cur_stock[<?php echo $data->id; ?>]" value="<?php echo AppObject::currentStock($data->sr_no); ?>">
                            <input type="hidden" name="agent[<?php echo $data->id; ?>]" value="<?php echo $data->agent_code; ?>">
                        </td>
                        <td class="no_pad"><input type="text" id="sr_no_<?php echo $counter; ?>" name="sr_no[<?php echo $data->id; ?>]" class="form-control" value="<?php echo $data->sr_no; ?>" readonly></td>
                        <td class="no_pad"><input type="text" id="type_<?php echo $counter; ?>" name="type[<?php echo $data->id; ?>]" class="form-control" value="<?php echo $product_type; ?>" readonly></td>
                        <td class="no_pad"><input type="text" id="taken_qty_<?php echo $counter; ?>" name="taken_qty[<?php echo $data->id; ?>]" class="form-control" value="<?php echo AppObject::loanTakenQty($data->sr_no); ?>" readonly></td>
                        <td class="no_pad"><input type="number" id="quantity_<?php echo $counter; ?>" name="quantity[<?php echo $data->id; ?>]" data-info="<?php echo $restQty . '/' . $data->sr_no; ?>" class="form-control qty" value="<?php echo!empty($restQty) ? $restQty : ''; ?>" min="0" max="<?php echo $restQty; ?>" step="any" oninput="multiply_value(this, '#loan_amount_<?php echo $counter; ?>')" data-target="#rent_<?php echo $counter; ?>"<?php echo empty($restQty) ? 'readonly' : ''; ?>></td>
                        <td class="no_pad"><input type="text" id="rent_<?php echo $counter; ?>" name="rent[<?php echo $data->id; ?>]" class="form-control rent_field" oninput="multiply_value(this, '#loan_amount_<?php echo $counter; ?>')" data-target="#quantity_<?php echo $counter; ?>" readonly></td>
                        <td class="no_pad"><input type="text" id="loan_amount_<?php echo $counter; ?>" name="loan_amount[<?php echo $data->id; ?>]" class="form-control unitprice" readonly></td>
                    </tr>
                    <?php
                endif;
            endforeach;
            ?>
            <tr class="bg_gray">
                <th colspan="6" class="text-right">Total Loan Amount</th>
                <th class="no_pad">
                    <input type="text" id="total_loan_amount" name="total_loan_amount" class="form-control" min="0" step="any" readonly>
                </th>
            </tr>
        </table>
    </div>

    <div class="text-center">
        <?php echo CHtml::submitButton('Save', array('class' => 'btn btn-primary')); ?>
    </div>
<?php else: ?>
    <div class="alert alert-info">No products found!</div>
<?php endif; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#pay_date").datepicker({
            format: 'dd-mm-yyyy'
        });
    });
</script>