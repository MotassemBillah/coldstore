<?php
$this->breadcrumbs = array(
    'Bank Account' => array(AppUrl::URL_ACCOUNT),
    'Balance'
);
?>
<div class="row clearfix" style="border-bottom:1px solid #cccccc;margin:0 -15px 10px -15px;padding-bottom:7px;">
    <div class="col-md-4 xs_txt_left">
        <strong><?php echo Yii::t("strings", "Bank"); ?></strong>:&nbsp;<?php echo AppObject::getBankName($account->bank_id); ?>
    </div>
    <div class="col-md-4 text-center xs_txt_left">
        <strong><?php echo Yii::t("strings", "Account Name"); ?></strong>:&nbsp;<?php echo $account->account_name; ?>
    </div>
    <div class="col-md-4 text-right xs_txt_left">
        <strong><?php echo Yii::t("strings", "Account Number"); ?></strong>:&nbsp;<?php echo $account->account_number; ?>
    </div>
</div>
<div class="well">
    <table width="100%">
        <tr>
            <td>
                <form class="search-form" method="post" name="frmSearch" id="frmSearch">
                    <input type="hidden" name="accountID" value="<?php echo $account->id; ?>">
                    <div class="input-group">
                        <div class="input-group-btn clearfix">
                            <select id="itemCount" class="form-control" name="itemCount" style="width:55px;">
                                <?php
                                for ($i = 10; $i <= 100; $i+=10) {
                                    if ($i == $this->settings->page_size) {
                                        echo "<option value='{$i}' selected='selected'>{$i}</option>";
                                    } else {
                                        echo "<option value='{$i}'>{$i}</option>";
                                    }
                                }
                                ?>
                            </select>
                            <select id="type" name="type" class="form-control" style="width:auto;">
                                <option value="All">All</option>
                                <option value="<?php echo AppConstant::CASH_IN; ?>"><?php echo AppConstant::CASH_IN; ?></option>
                                <option value="<?php echo AppConstant::CASH_OUT; ?>"><?php echo AppConstant::CASH_OUT; ?></option>
                            </select>
                            <div class="col-md-2 col-sm-3 no_pad">
                                <div class="input-group xsw_100">
                                    <input type="text" id="from_date" class="form-control" name="from_date" placeholder="(dd-mm-yyyy)" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-1 text-center" style="font-size:14px;width:5%;">
                                <b style="color: rgb(0, 0, 0); vertical-align: middle; display: block; padding: 6px 0px;">TO</b>
                            </div>
                            <div class="col-md-2 col-sm-3 no_pad">
                                <div class="input-group xsw_100">
                                    <input type="text" id="to_date" class="form-control" name="to_date" placeholder="(dd-mm-yyyy)" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_from" class="btn btn-primary" data-info="/account/balance"><?php echo Yii::t("strings", "Clear"); ?></button>
                        </div>
                    </div>
                </form>
            </td>
        </tr>
    </table>
</div>
<form id="deleteForm" action="" method="post">
    <div id="ajaxContent">
        <?php if (!empty($dataset) && count($dataset) > 0) : ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tr class="bg_gray" id="r_checkAll">
                        <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                        <th><?php echo Yii::t("strings", "Date"); ?></th>
                        <th><?php echo Yii::t("strings", "Purpose"); ?></th>
                        <th><?php echo Yii::t("strings", "Person"); ?></th>
                        <th class="text-right"><?php echo Yii::t("strings", "Debit"); ?></th>
                        <th class="text-right"><?php echo Yii::t("strings", "Credit"); ?></th>
                        <th class="text-right"><?php echo Yii::t("strings", "Balance"); ?></th>
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
                            <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                            <td><?php echo date('j M Y', strtotime($data->last_update)); ?></td>
                            <td><?php echo AppHelper::getCleanValue($data->purpose); ?></td>
                            <td><?php echo AppHelper::getCleanValue($data->by_whom); ?></td>
                            <td class="text-right"><?php echo AppHelper::getFloat($data->debit); ?></td>
                            <td class="text-right"><?php echo AppHelper::getFloat($data->credit); ?></td>
                            <td class="text-right"><?php echo AppHelper::getFloat($data->balance); ?></td>
                        </tr>
                        <?php
                        $sum_dbt[] = $data->debit;
                        $sum_crdt[] = $data->credit;
                        $sum_blance[] = $data->balance;
                    endforeach;
                    ?>
                    <tr class="bg_gray">
                        <th colspan="4" class="text-right"><?php echo Yii::t("strings", "Total"); ?></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_dbt)); ?></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_crdt)); ?></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_blance)); ?></th>
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
                    'prevPageLabel' => '< ',
                    'selectedPageCssClass' => 'active ',
                    'hiddenPageCssClass' => 'disabled ',
                    'maxButtonCount' => 10,
                    'htmlOptions' => array(
                        'class' => 'pagination',
                    )
                ));
                ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No records found!</div>
        <?php endif; ?>
    </div>
</form>
<div class="modal fade" id="containerForDetailInfo" tabindex="-1" role="dialog" aria-labelledby="containerForDetailInfoLabel"></div>
<script type="text/javascript">
    $(document).ready(function() {
    });
</script>