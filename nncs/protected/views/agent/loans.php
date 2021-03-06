<?php
$this->breadcrumbs = array(
    'Agent' => array(AppUrl::URL_AGENT),
    'Loans'
);
?>
<div class="well">
    <table width="100%">
        <tr>
            <td class="wmd_70">
                <form class="search-form" method="post" name="frmSearch" id="frmSearch">
                    <input type="hidden" name="code_no" value="<?php echo $model->code; ?>">
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
                            <div class="col-md-2 col-sm-3 no_pad">
                                <div class="input-group xsw_100">
                                    <input type="text" id="from_date" class="form-control" name="from_date" placeholder="(dd-mm-yyyy)" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-1 text-center" style="font-size:14px;width: 5%;">
                                <b style="color: rgb(0, 0, 0); vertical-align: middle; display: block; padding: 6px 0px;">TO</b>
                            </div>
                            <div class="col-md-2 col-sm-3 no_pad">
                                <div class="input-group xsw_100">
                                    <input type="text" id="to_date" class="form-control" name="to_date" placeholder="(dd-mm-yyyy)" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_from" class="btn btn-primary" data-info="/agent/loan_adv_list"><?php echo Yii::t("strings", "Clear"); ?></button>
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right wmd_30" style="position: relative;">
                <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_AGENT_LOAN_CREATE_ADV, ['id' => $model->_key]); ?>"><button type="button" class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "Advance"); ?></button></a>
                <?php if ($this->hasUserAccess('agent_loan_delete')): ?>
                    <button type="button" class="btn btn-danger btn-xs" id="admin_del_btn" disabled="disabled"><?php echo Yii::t("strings", "Delete"); ?></button>
                <?php endif; ?>
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
                        <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                        <th style="width:10%;"><?php echo Yii::t('strings', 'Date'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Empty Bag'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Bag Price'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Price Total'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Carrying'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Loan Amount'); ?></th>
                        <th class="text-right" style="width:10%;"><?php echo Yii::t('strings', 'Debit'); ?></th>
                        <th class="text-right" style="width:10%;"><?php echo Yii::t('strings', 'Credit'); ?></th>
                        <th class="text-right" style="width:10%;"><?php echo Yii::t('strings', 'Balance'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Actions'); ?></th>
                        <?php if ($this->hasUserAccess('agent_loan_delete')): ?>
                            <th class="text-center" style="width:3%;"><input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)"></th>
                        <?php endif; ?>
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
                            <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                            <td style="width:10%;"><?php echo date('j M Y', strtotime($data->created)); ?></td>
                            <td class="text-center"><?php echo $data->empty_bag; ?></td>
                            <td class="text-right"><?php echo $data->empty_bag_price; ?></td>
                            <td class="text-right"><?php echo $data->empty_bag_price_total; ?></td>
                            <td class="text-right"><?php echo $data->carrying_cost; ?></td>
                            <td class="text-right"><?php echo $data->loan_amount; ?></td>
                            <td class="text-right"><?php echo $data->debit; ?></td>
                            <td class="text-right"><?php echo $data->credit; ?></td>
                            <td class="text-right"><?php echo $data->balance; ?></td>
                            <td class="text-center">
                                <?php if ($this->hasUserAccess('agent_loan_edit')): ?>
                                    <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_AGENT_LOAN_EDIT, ['id' => $model->_key, 'lid' => $data->_key]); ?>"><?php echo Yii::t("strings", "Edit"); ?></a>
                                <?php endif; ?>
                            </td>
                            <?php if ($this->hasUserAccess('agent_loan_delete')): ?>
                                <td class="text-center" style="width:3%;"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"></td>
                            <?php endif; ?>
                        </tr>
                        <?php
                        $sum_empty_bag[] = $data->empty_bag;
                        $sum_empty_bag_price[] = $data->empty_bag_price_total;
                        $sum_ccost[] = $data->carrying_cost;
                        $sum_ln_amount[] = $data->loan_amount;
                        $sum_debit[] = $data->debit;
                        $sum_credit[] = $data->credit;
                        $sum_balance[] = $data->balance;
                    endforeach;
                    ?>
                    <tr class="bg_gray">
                        <th colspan="2" class="text-right"><?php echo Yii::t("strings", "Total"); ?></th>
                        <th class="text-center"><?php echo array_sum($sum_empty_bag); ?></th>
                        <th></th>
                        <th class="text-right"><?php echo array_sum($sum_empty_bag_price); ?></th>
                        <th class="text-right"><?php echo array_sum($sum_ccost); ?></th>
                        <th class="text-right"><?php echo array_sum($sum_ln_amount); ?></th>
                        <th class="text-right"><?php echo array_sum($sum_debit); ?></th>
                        <th class="text-right"><?php echo array_sum($sum_credit); ?></th>
                        <th class="text-right"><?php echo array_sum($sum_balance); ?></th>
                        <th class="text-right"></th>
                        <?php if ($this->hasUserAccess('agent_loan_delete')): ?>
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
                    'nextPageLabel' => '> ',
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
        <?php else: ?>
            <div class="alert alert-info">No records found!</div>
        <?php endif; ?>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $("#from_date, #to_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on('click', '#search', function(e) {
            showLoader("Processing...", true);
            var _form = $("#frmSearch");

            $.ajax({
                type: "POST",
                url: ajaxUrl + "/agent/loan_adv_list",
                data: _form.serialize(),
                success: function(res) {
                    showLoader("", false);
                    $("#ajaxContent").html('');
                    $("#ajaxContent").html(res);
                }
            });
            e.preventDefault();
        });

        $(document).on('click', '#admin_del_btn', function(e) {
            var _rc = confirm('Are you sure about this action? This cannot be undone!');

            if (_rc === true) {
                showLoader("Processing...", true);
                var _form = $("#deleteForm");
                var _url = ajaxUrl + '/loan/deleteall_advance_loan';

                $.post(_url, _form.serialize(), function(res) {
                    if (res.success === true) {
                        $("#ajaxMessage").showAjaxMessage({html: res.message, type: 'success'});
                        $("tr.bg-danger").remove();
                        $("#clear_from").trigger('click');
                    } else {
                        $("#ajaxMessage").showAjaxMessage({html: res.message, type: 'error'});
                    }
                    reset_index();
                    showLoader("", false);
                }, "json");
            } else {
                return false;
            }
            e.preventDefault();
        });
    });
</script>