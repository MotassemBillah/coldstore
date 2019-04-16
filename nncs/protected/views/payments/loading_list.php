<?php
$this->breadcrumbs = array(
    'Payments' => array(AppUrl::URL_PAYMENT),
    'Loading'
);
?>
<div class="well">
    <table width="100%">
        <tr>
            <td class="wmd_70">
                <form class="search-form" method="post" name="frmSearch" id="frmSearch">
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
                            <?php if (Yii::app()->user->role == AppConstant::ROLE_SUPERADMIN): ?>
                                <div class="col-md-2 col-sm-2 no_pad">
                                    <?php
                                    $userList = User::model()->getList();
                                    $ulist = CHtml::listData($userList, 'id', 'display_name');
                                    echo CHtml::dropDownList('user', 'user', $ulist, array('empty' => 'User', 'class' => 'form-control', 'style' => 'text-transform:capitalize;'));
                                    ?>
                                </div>
                            <?php endif; ?>
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
                            <input type="text" id="q" name="q" class="form-control" placeholder="sr number" style="width:15%;">
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_from" class="btn btn-primary" data-info="/payments/list_loading">Clear</button>
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right wmd_30" style="">
                <?php if ($this->hasUserAccess('loading_payment_create')): ?>
                    <a class="btn btn-success btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_PAYMENT_LOADING_NEW); ?>"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t('strings', 'New'); ?></a>
                <?php endif; ?>
                <?php if ($this->hasUserAccess('loading_payment_delete')): ?>
                    <button type="button" class="btn btn-danger btn-xs" id="admin_del_btn" disabled="disabled" ><?php echo Yii::t("strings", "Delete"); ?></button>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>

<form id="deleteForm" action="#" method="post">
    <div id="ajaxContent">
        <?php if (!empty($dataset) && count($dataset) > 0) : ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tr class="bg_gray" id="r_checkAll">
                        <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                        <th><?php echo Yii::t('strings', 'Date'); ?></th>
                        <th><?php echo Yii::t('strings', 'Type'); ?></th>
                        <th><?php echo Yii::t('strings', 'Sr No'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Quantity'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Quantity Price'); ?></th>
                        <th class="text-right"><?php echo Yii::t('strings', 'Qty Price Total'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Actions'); ?></th>
                        <?php if ($this->hasUserAccess('loading_payment_delete')): ?>
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
                            <td><?php echo date('j M Y', strtotime($data->created)); ?></td>
                            <td><?php echo ucfirst($data->pament_for); ?></td>
                            <td><?php echo $data->sr_no; ?></td>
                            <td class="text-center"><?php echo $data->quantity; ?></td>
                            <td class="text-right"><?php echo $data->quantity_price; ?></td>
                            <td class="text-right"><?php echo AppHelper::getFloat($data->price_total); ?></td>
                            <td class="text-center">
                                <?php if ($this->hasUserAccess('loading_payment_edit')): ?>
                                    <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_PAYMENT_LOADING_EDIT, ['id' => $data->_key]); ?>"><?php echo Yii::t('strings', 'Edit'); ?></a>
                                <?php endif; ?>
                            </td>
                            <?php if ($this->hasUserAccess('loading_payment_delete')): ?>
                                <td class="text-center" style="width:3%;"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"></td>
                            <?php endif; ?>
                        </tr>
                        <?php
                        $sum_qty[] = $data->quantity;
                        $sum_qty_cost[] = $data->quantity_price;
                        $sum_cost_total[] = $data->price_total;
                    endforeach;
                    ?>
                    <tr class="bg_gray">
                        <th colspan="4" class="text-right"><?php echo Yii::t("strings", "Total"); ?></th>
                        <th class="text-center"><?php echo array_sum($sum_qty); ?></th>
                        <th class="text-right"></th>
                        <th class="text-right"><?php echo AppHelper::getFloat(array_sum($sum_cost_total)); ?></th>
                        <th></th>
                        <?php if ($this->hasUserAccess('loading_payment_delete')): ?>
                            <th colspan="1"></th>
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
            <div class="alert alert-info"><?php echo Yii::t('strings', 'No records found!'); ?></div>
        <?php endif; ?>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $("#from_date, #to_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on("click", "#search", function(e) {
            showLoader("Processing...", true);
            var _form = $("#frmSearch");

            $.ajax({
                type: "POST",
                url: ajaxUrl + "/payments/list_loading",
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
                var _url = ajaxUrl + '/payments/deleteall_loading';

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