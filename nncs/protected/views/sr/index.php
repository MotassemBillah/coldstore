<?php
$this->breadcrumbs = array(
    'SR List'
);
?>
<div class="well">
    <table width="100%">
        <tr>
            <td class="">
                <form class="search-form" method="post" name="frmSearch" id="frmSearch">
                    <div class="input-group">
                        <div class="input-group-btn clearfix">
                            <select id="itemCount" class="form-control" name="itemCount" style="width:55px;">
                                <?php
                                for ($i = 10; $i <= 500; $i+=10) {
                                    if ($i > 100)
                                        $i+=40;
                                    if ($i > 200)
                                        $i+=50;
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
                                <select id="sortBy" class="form-control" name="sort_by">
                                    <option value="customer_id" style="text-transform:capitalize">Customer</option>
                                    <option value="sr_no" style="text-transform:capitalize" selected>SR No</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-3 no_pad">
                                <select id="sortType" class="form-control" name="sort_type">
                                    <option value="ASC" selected>Ascending</option>
                                    <option value="DESC">Descending</option>
                                </select>
                            </div>
                            <input type="text" name="srno" id="sr" class="form-control" placeholder="sr number" size="25" style="width:12%;">
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="btn_clear" class="btn btn-warning"><?php echo Yii::t("strings", "Clear"); ?></button>
                        </div>
                    </div>
                </form>
            </td>
        </tr>
    </table>
</div>
<form id="deleteForm" action="" method="post">
    <div id="printDiv">
        <div class="form-group clearfix text-center txt_left_xs mp_center media_print show_in_print mp_mt">
            <?php if (!empty($this->settings->title)): ?>
                <h1 style="font-size: 30px;margin: 0;"><?php echo $this->settings->title; ?></h1>
            <?php endif; ?>
            <?php if (!empty($this->settings->author_address)): ?>
                <?php echo $this->settings->author_address; ?><br>
            <?php endif; ?>
            <h4 class="inv_title"><u><?php echo Yii::t("strings", "Product Entry List"); ?></u></h4>
        </div>
        <div id="ajaxContent">
            <?php if (!empty($dataset) && count($dataset) > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped tbl_invoice_view">
                        <tr class="bg_gray" id="r_checkAll">
                            <th class="text-center" style="width:5%;"><?php echo Yii::t("strings", "SL#"); ?></th>
                            <th class="text-center" style="width:10%;"><?php echo Yii::t("strings", "SR No"); ?></th>
                            <th class="text-center" style="width:10%;"><?php echo Yii::t("strings", "Lot No"); ?></th>
                            <th style=""><?php echo Yii::t("strings", "Customer"); ?></th>
                            <th style=""><?php echo Yii::t("strings", "Father Name"); ?></th>
                            <th style=""><?php echo Yii::t("strings", "Village"); ?></th>
                            <th class="text-center dis_print" style="width:10%;"><?php echo Yii::t("strings", "Actions"); ?></th>
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
                                <td class="text-center"><?php echo $counter; ?></td>
                                <td class="text-center"><?php echo $data->sr_no; ?></td>
                                <td class="text-center"><?php echo $data->lot_no; ?></td>
                                <td><?php echo $data->customer->name; ?></td>
                                <td><?php echo!empty($data->customer->father_name) ? $data->customer->father_name : ""; ?></td>
                                <td><?php echo!empty($data->customer->village) ? $data->customer->village : ""; ?></td>
                                <td class="text-center dis_print">
                                    <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_SR_VIEW, ['id' => $data->sr_no]); ?>"><?php echo Yii::t("strings", "View"); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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
                        )
                    ));
                    ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No records found!</div>
            <?php endif; ?>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#search", function(e) {
            showLoader("Processing...", true);
            var _form = $("#frmSearch");

            $.ajax({
                type: "POST",
                url: baseUrl + "/sr/search",
                data: _form.serialize(),
                success: function(res) {
                    showLoader("", false);
                    $("#ajaxContent").html('');
                    $("#ajaxContent").html(res);
                }
            });
            e.preventDefault();
        });

        $(document).on("click", "#btn_clear", function(e) {
            var _form = $("#frmSearch");
            _form[0].reset();
            $("#search").trigger('click');
            e.preventDefault();
        });
    });
</script>