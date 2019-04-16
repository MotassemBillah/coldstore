<?php
$this->breadcrumbs = array(
    'Customer' => array(AppUrl::URL_CUSTOMER),
    'Loans'
);
?>
<div class="well">
    <table width="100%">
        <tr>
            <td class="wmd_70">
                <form class="search-form" method="post" name="frmSearch" id="frmSearch">
                    <input type="hidden" name="customer_id" value="<?php echo $model->id; ?>">
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
                                <select id="loanType" class="form-control" name="loanType">
                                    <option value="Advance">Advance</option>
                                    <option value="Regular" selected>Regular</option>
                                </select>
                            </div>
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
                            <button type="button" id="clear_from" class="btn btn-primary" data-info="/customer/loan"><?php echo Yii::t("strings", "Clear"); ?></button>
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right wmd_30" style="position: relative;">
                <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_CUSTOMER_LOAN_CREATE_ADV, ['id' => $model->_key]); ?>"><button type="button" class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "Advance"); ?></button></a>
                <!--<a href="<?php // echo Yii::app()->createUrl(AppUrl::URL_CUSTOMER_LOAN_CREATE, ['id' => $model->_key]);  ?>"><button type="button" class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php // echo Yii::t("strings", "Regular");  ?></button></a>-->
                <?php if ($this->hasUserAccess('loan_delete')): ?>
                    <button type="button" class="btn btn-danger btn-xs" id="admin_del_btn" disabled="disabled"><?php echo Yii::t("strings", "Delete"); ?></button>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>
<div id="ajaxContent">
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
                )
            ));
            ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No records found!</div>
    <?php endif; ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#from_date, #to_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on('click', '#search', function(e) {
            showLoader("Processing...", true);
            var _form = $("#frmSearch");
            var _url;
            if ($("#loanType").val() == "Advance") {
                _url = ajaxUrl + "/customer/loan_adv_list";
            } else {
                _url = ajaxUrl + "/customer/loan";
            }
            console.log(_url);

            $.ajax({
                type: "POST",
                url: _url,
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