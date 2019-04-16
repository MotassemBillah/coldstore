<?php if (!empty($dataset) && count($dataset) > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped tbl_invoice_view">
            <tr class="bg_gray">
                <th colspan="13">
                    <?php if (!empty($agentTotal)) : ?>
                        <span style="font-weight:400;margin-left:10px">[ agent stock = <?php echo $agentTotal; ?> ]</span>
                    <?php else: ?>
                        <?php echo Yii::t("strings", "Total Stock") . " = " . AppObject::sumStock(); ?>
                        <span style="margin-left:20px">Total Loan Pack : <?php echo ProductIn::model()->totalLoanPackGiven(); ?></span>
                        <span style="font-weight:400;margin-left:20px">[ Prepared By : <?php echo $display_name; ?>&nbsp;{ stock = <?php echo $userStock; ?> }&nbsp;<?php if (!empty($officeStock)) echo "{ Office Stock = {$officeStock} }"; ?> ]</span>
                    <?php endif; ?>
                </th>
            </tr>
            <tr class="bg_gray" id="r_checkAll">
                <th class="text-center" style="width:4%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                <th style="width:12%;"><?php echo Yii::t("strings", "Date"); ?></th>
                <th style="width:15%;"><?php echo Yii::t("strings", "Customer"); ?></th>
                <th class="text-center" style="width:6%;"><?php echo Yii::t("strings", "SR No"); ?></th>
                <th style="width:15%;"><?php echo Yii::t("strings", "Type"); ?></th>
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "In"); ?></th>
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "Out"); ?></th>
                <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "Stock"); ?></th>
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Loan Bag"); ?></th>
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Bag Paid"); ?></th>
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Bag Remain"); ?></th>
                <th><?php echo Yii::t("strings", "Pocket No"); ?></th>
                <th class="text-center" style="width:4%;"><?php echo Yii::t("strings", "Agent Code"); ?></th>
                <!--<th style="width: 10%"><?php // echo Yii::t("strings", "Agent");                ?></th>-->
            </tr>
            <?php
            $counter = 0;
            if (isset($_GET['page']) && $_GET['page'] > 1) {
                $counter = ($_GET['page'] - 1) * $pages->pageSize;
            }
            foreach ($dataset as $data):
                $counter++;
                $agent = !empty($data->agent_code) ? Agent::model()->find('code=:code', [':code' => $data->agent_code]) : '';
                ?>
                <tr class="">
                    <td class="text-center"><?php echo $counter; ?></td>
                    <td><?php echo date("j M Y", strtotime($data->create_date)); ?></td>
                    <td><?php echo $data->customer->name; ?></td>
                    <td class="text-center"><?php echo!empty($data->sr_no) ? $data->sr_no : ""; ?></td>
                    <td><?php echo!empty($data->type) ? ProductType::model()->findByPk($data->type)->name : ""; ?></td>
                    <td class="text-center"><?php echo!empty($data->quantity) ? $data->quantity : ""; ?></td>
                    <td class="text-center"><?php echo AppObject::stockOut($data->sr_no); ?></td>
                    <td class="text-center"><?php echo AppObject::currentStock($data->sr_no); ?></td>
                    <td class="text-center"><?php echo AppObject::loanPackIn($data->sr_no); ?></td>
                    <td class="text-center"><?php echo AppObject::loanPackOut($data->sr_no); ?></td>
                    <td class="text-center"><?php echo AppObject::loanPackStock($data->sr_no); ?></td>
                    <td>
                        <?php
                        $_stockID = !empty($data->stock->id) ? $data->stock->id : '';
                        $loc_criteria = new CDbCriteria();
                        $loc_criteria->condition = "stock_id=:sid";
                        $loc_criteria->params = [":sid" => $data->stock->id];
                        $loc_criteria->order = "id DESC";
                        $loc_criteria->limit = 1;
                        $_location = StockLocation::model()->find($loc_criteria);
                        if (!empty($_location)) {
                            echo "Room: " . LocationRoom::model()->findByPk($_location->room_id)->name . " | ";
                            echo "Floor: " . LocationFloor::model()->findByPk($_location->floor_id)->name;
                            if (!empty($_location->pockets)) {
                                $_pkets = json_decode($_location->pockets);
                                echo "<br>{ ";
                                foreach ($_pkets as $_pk => $_pv) {
                                    if ($_pk == (count($_pkets) - 1))
                                        echo "{$_pv}";
                                    else
                                        echo "{$_pv} | ";
                                }
                                echo " }";
                            }
                        }
                        ?>
                    </td>
                    <td class="text-center"><?php echo!empty($agent) ? $agent->code : ''; ?></td>
                    <!--<td><?php // echo!empty($agent) ? $agent->name : '';                ?></td>-->
                </tr>
                <?php
                $sum['in'][] = $data->quantity;
                $sum['out'][] = AppObject::stockOut($data->sr_no);
                $sum['total'][] = AppObject::currentStock($data->sr_no);
                $sum['lp_in'][] = AppObject::loanPackIn($data->sr_no);
                $sum['lp_out'][] = AppObject::loanPackOut($data->sr_no);
                $sum['lp_total'][] = AppObject::loanPackStock($data->sr_no);
            endforeach;
            ?>
            <tr class="bg_gray dis_print">
                <th class="text-right" colspan="5"><?php echo Yii::t("strings", "Total"); ?></th>
                <th class="text-center"><?php echo array_sum($sum['in']); ?></th>
                <th class="text-center"><?php echo array_sum($sum['out']); ?></th>
                <th class="text-center"><?php echo array_sum($sum['total']); ?></th>
                <th class="text-center"><?php echo array_sum($sum['lp_in']); ?></th>
                <th class="text-center"><?php echo array_sum($sum['lp_out']); ?></th>
                <th class="text-center"><?php echo array_sum($sum['lp_total']); ?></th>
                <th></th>
                <th></th>
                <!--<th></th>-->
            </tr>
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