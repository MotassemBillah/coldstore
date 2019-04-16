<?php
$this->breadcrumbs = array(
    'Loan' => array(AppUrl::URL_LOAN),
    'Duplicate List'
);
?>
<form id="deleteForm" action="" method="post">
    <?php if (!empty($dataset) && count($dataset) > 0) : ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped tbl_invoice_view">
                <tr class="bg_gray" id="r_checkAll">
                    <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                    <th style="width:15%"><?php echo Yii::t("strings", "Date"); ?></th>
                    <th><?php echo Yii::t("strings", "Customer"); ?></th>
                    <th><?php echo Yii::t("strings", "Create By"); ?></th>
                    <th class="text-center" style="width:10%;"><?php echo Yii::t("strings", "SR Number"); ?></th>
                    <th class="text-center" style="width:8%;"><?php echo Yii::t("strings", "Quantity"); ?></th>
                    <th class="text-right" style="width:15%;"><?php echo Yii::t("strings", "Loan"); ?>(tk)</th>
                </tr>
                <?php
                $counter = 0;
                foreach ($dataset as $data):
                    $counter++;
                    ?>
                    <tr class="">
                        <td class="text-center"><?php echo $counter; ?></td>
                        <td><?php echo date("j M Y", strtotime($data['create_date'])); ?></td>
                        <td><?php echo Customer::model()->findByPk($data['customer_id'])->name; ?></td>
                        <td><?php echo User::model()->displayname($data['created_by']); ?></td>
                        <td class="text-center"><?php echo $data['sr_no']; ?></td>
                        <td class="text-center"><?php echo $data['qty']; ?></td>
                        <td class="text-right"><?php echo AppHelper::getFloat($data['net_amount']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No records found!</div>
    <?php endif; ?>
</form>