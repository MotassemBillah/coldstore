<?php
if (!empty($dataset) && count($dataset) > 0) :
    if (!empty($customerMobileNumber)) {
        $currentStock = AppObject::currentStockCustomer($dataset[0]->customer->id);
    } else {
        $currentStock = AppObject::currentStock($dataset[0]->sr_no);
    }
    if ($currentStock > 0) :
        ?>
        <div class="row clearfix">
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <input type="text" class="form-control" value="<?php echo $dataset[0]->customer->name; ?>" readonly>
                    <input type="hidden" name="customer_id" value="<?php echo $dataset[0]->customer->id; ?>">
                    <input type="hidden" id="curStock" name="curStock" value="<?php echo $currentStock; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label class=""><?php echo Yii::t("strings", "Total Stock"); ?>: <?php echo $currentStock; ?></label>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered no_mrgn">
                <tr class="bg_gray">
                    <th class="text-center"><?php echo Yii::t("strings", "SL#"); ?></th>
                    <th><?php echo Yii::t("strings", "Sr No"); ?></th>
                    <th><?php echo Yii::t("strings", "Delivery Sr No"); ?></th>
                    <th><?php echo Yii::t("strings", "Loan Qty"); ?></th>
                    <th><?php echo Yii::t("strings", "Loan Paid Qty"); ?></th>
                    <th><?php echo Yii::t("strings", "Quantity"); ?></th>
                    <th><?php echo Yii::t("strings", "Loan Bag"); ?></th>
                    <th><?php echo Yii::t("strings", "Loan Bag Price"); ?></th>
                    <th><?php echo Yii::t("strings", "Lot No"); ?></th>
                    <th><?php echo Yii::t("strings", "Agent Code"); ?></th>
                </tr>
                <?php
                $counter = 0;
                foreach ($dataset as $data):
                    $curStock = AppObject::currentStock($data->sr_no);
                    $loan_bag = AppObject::loanPackStock($data->sr_no);
                    $loan_pending = LoanPending::model()->getObj(['sr_no' => $data->sr_no]);
                    $loan_receive = LoanReceived::model()->getObj(['sr_no' => $data->sr_no]);
                    $pendingQty = !empty($loan_pending) ? $loan_pending->quantity : '';
                    $recQty = !empty($loan_receive) ? $loan_receive->quantity : '';
                    $maxQty = ($curStock - $pendingQty + $recQty);
                    if ($curStock > 0) :
                        $counter++;
                        ?>
                        <tr>
                            <td class="no_pad text-center" style="vertical-align: middle">
                                <input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" style="height:20px;width:20px;">
                                <input type="hidden" name="cur_stock[<?php echo $data->id; ?>]" value="<?php echo $curStock; ?>">
                            </td>
                            <td class="no_pad"><input type="text" class="form-control" id="sr_no_<?php echo $data->id; ?>" name="sr_no[<?php echo $data->id; ?>]" value="<?php echo $data->sr_no; ?>" readonly></td>
                            <td class="no_pad"><input type="text" class="form-control" id="delivery_sr_no_<?php echo $data->id; ?>" name="delivery_sr_no[<?php echo $data->id; ?>]" value="<?php echo $dsrNumber + $counter; ?>" placeholder="delivery sr no"></td>
                            <td class="no_pad"><input type="text" class="form-control" id="loan_qty_<?php echo $data->id; ?>" name="loan_qty[<?php echo $data->id; ?>]" value="<?php echo $pendingQty; ?>" placeholder="loan qty" readonly></td>
                            <td class="no_pad"><input type="text" class="form-control" id="loan_qty_paid_<?php echo $data->id; ?>" name="loan_qty_paid[<?php echo $data->id; ?>]" value="<?php echo $recQty; ?>" placeholder="loan qty paid" readonly></td>
                            <td class="no_pad"><input type="number" class="form-control" id="quantity_<?php echo $data->id; ?>" name="quantity[<?php echo $data->id; ?>]" value="<?php echo $maxQty; ?>" placeholder="quantity" min="0" max="<?php echo $maxQty; ?>"></td>
                            <td class="no_pad"><input type="number" class="form-control" id="loan_pack_<?php echo $data->id; ?>" name="loan_pack[<?php echo $data->id; ?>]" value="<?php echo $loan_bag; ?>" placeholder="loan bag" min="0" max="<?php echo $loan_bag; ?>"<?php echo!empty($loan_bag) ? '' : ' readonly'; ?>></td>
                            <td class="no_pad"><input type="number" class="form-control" id="loan_pack_price_<?php echo $data->id; ?>" name="loan_pack_price[<?php echo $data->id; ?>]" value="" placeholder="loan bag price" min="0" step="any" <?php echo!empty($loan_bag) ? '' : ' readonly'; ?>></td>
                            <td class="no_pad"><input type="text" class="form-control" id="lot_no_<?php echo $data->id; ?>" name="lot_no[<?php echo $data->id; ?>]" value="<?php echo $data->lot_no; ?>" placeholder="lot no" readonly></td>
                            <td class="no_pad"><input type="text" class="form-control" id="agent_<?php echo $data->id; ?>" name="agent[<?php echo $data->id; ?>]" value="<?php echo $data->agent_code; ?>" placeholder="agent code" readonly></td>
                        </tr>
                        <?php
                    endif;
                endforeach;
                ?>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No record found!</div>
    <?php endif; ?>
<?php else: ?>
    <div class="alert alert-info">No record found!</div>
<?php endif; ?>