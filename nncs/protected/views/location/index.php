<?php
$this->breadcrumbs = array(
    'Location'
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
                            <input type="text" id="q" name="q" class="form-control" placeholder="search by name" size="30">
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right wmd_30" style="width: 25%;">
                <?php if ($this->hasUserAccess('location_create')): ?>
                    <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOCATION_CREATE_ROOM); ?>"><button class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "Room"); ?></button></a>
                    <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOCATION_CREATE_FLOOR); ?>"><button class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "Floor"); ?></button></a>
                    <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOCATION_CREATE_POCKET); ?>"><button class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "Pocket"); ?></button></a>
                <?php endif; ?>
                <?php if ($this->hasUserAccess('location_delete')): ?>
                    <button type="button" class="btn btn-danger btn-xs" id="admin_del_btn" disabled="disabled" ><?php echo Yii::t("strings", "Delete"); ?></button>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>
<div id="ajaxContent">
    <?php if (!empty($dataset) && count($dataset) > 0) : ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr class="bg_gray" id="r_checkAll">
                    <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                    <th><?php echo Yii::t("strings", "Location"); ?></th>
                    <th class="text-center" style="width: 15%;"><?php echo Yii::t("strings", "Actions"); ?></th>
                    <?php if ($this->hasUserAccess('location_delete')): ?>
                        <th class="text-center" style="width:3%;"><input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)"></th>
                    <?php endif; ?>
                </tr>
                <?php
                $counter = 0;
                if (isset($_GET['page']) && $_GET['page'] > 1) {
                    $counter = ($_GET['page'] - 1) * $pages->pageSize;
                }
                foreach ($dataset as $data) :
                    $counter++;
                    ?>
                    <tr>
                        <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                        <td><span class="dis_blok" style="font-weight: 600;"><?php echo $data->name; ?></span>
                            <?php if (!empty($data->floors)): ?>
                                <ul class="floor_list">
                                    <?php foreach ($data->floors as $floor): ?>
                                        <li id="floor_no_<?= $floor->id; ?>">
                                            <div class="" style="border-bottom:1px solid #cdcdcd;">
                                                <span style="display:inline-block;width:150px"><?= $floor->name; ?></span>
                                                <span><a href='javascript:void(0)'>edit</a></span>
                                            </div>
                                            <?php if (!empty($floor->pockets)): ?>
                                                <ul class="pocket_list clearfix">
                                                    <?php foreach ($floor->pockets as $pocket): ?>
                                                        <li id="pocket_no_<?= $pocket->id; ?>">
                                                            <div id="form_holder_<?= $pocket->id; ?>" style="display:none">
                                                                <form action="" id="frm_<?= $pocket->id; ?>" method="post">
                                                                    <input type="hidden" name="pktid" value="<?= $pocket->id; ?>">
                                                                    <input type="text" id="pktname_<?= $pocket->id; ?>" name="pktname" value="<?= $pocket->name; ?>">
                                                                    <input type="button" class="btn btn-success btn-xs save_change" value="update" data-info="<?= $pocket->id; ?>">
                                                                    <input type="button" class="btn btn-info btn-xs cancel_update" value="cancel" data-info="<?= $pocket->id; ?>">
                                                                </form>
                                                            </div>
                                                            <div id="info_holder_<?= $pocket->id; ?>">
                                                                <span style="display:inline-block" id="txt_<?= $pocket->id; ?>"><?= $pocket->name; ?></span>
                                                                <span class="btn_edel btn_del"><a href="javascript:void(0)" data-info="<?= $pocket->id; ?>">delete</a></span>
                                                                <span class="btn_edel btn_edit"><a href="javascript:void(0)" data-info="<?= $pocket->id; ?>">edit</a></span>
                                                            </div>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </td>
                        <td class="text-center" style="width: 15%;">
                            <?php if ($this->hasUserAccess('location_edit')): ?>
                                <a class="btn btn-info btn-xs" href="#"><?php echo Yii::t("strings", "Edit"); ?></a>
                            <?php endif; ?>
                            <?php if ($this->hasUserAccess('location_delete')): ?>
                                <a class="btn btn-danger btn-xs" href="#" onclick="return confirm('Are you sure about deletion? This process cannot be undone.')"><?php echo Yii::t("strings", "Delete"); ?></a>
                            <?php endif; ?>
                        </td>
                        <?php if ($this->hasUserAccess('location_delete')): ?>
                            <td class="text-center" style="width:3%;"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
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
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", ".btn_edit a", function(e) {
            var _id = $(this).attr('data-info');
            console.log(_id);
            $("#info_holder_" + _id).hide();
            $("#form_holder_" + _id).show();
            e.preventDefault();
        });

        $(document).on("click", ".save_change", function(e) {
            showLoader("Processing...", true);
            var _id = $(this).attr('data-info');
            var _form = $("#frm_" + _id);
            var _url = baseUrl + '/location/update_pocket';

            $.post(_url, _form.serialize(), function(res) {
                if (res.success === true) {
                    $("#txt_" + _id).html($("#pktname_" + _id).val());
                    $("#form_holder_" + _id).hide();
                    $("#info_holder_" + _id).show();
                    $("#ajaxMessage").showAjaxMessage({html: res.message, type: 'success'});
                } else {
                    $("#ajaxMessage").showAjaxMessage({html: res.message, type: 'error'});
                }
                showLoader("", false);
            }, "json");
            e.preventDefault();
        });

        $(document).on("click", ".cancel_update", function(e) {
            var _id = $(this).attr('data-info');
            $("#form_holder_" + _id).hide();
            $("#info_holder_" + _id).show();
            e.preventDefault();
        });

        $(document).on("click", ".btn_del a", function(e) {
            var _rc = confirm('Are you sure about this action? This cannot be undone!');

            if (_rc === true) {
                showLoader("Processing...", true);
                var _id = $(this).attr('data-info');
                var _url = baseUrl + '/location/delete_pocket?id=' + _id;

                $.post(_url, function(res) {
                    if (res.success === true) {
                        $("#ajaxMessage").showAjaxMessage({html: res.message, type: 'success'});
                        $("li#pocket_no_" + _id).remove();
                    } else {
                        $("#ajaxMessage").showAjaxMessage({html: res.message, type: 'error'});
                    }
                    showLoader("", false);
                }, "json");
            } else {
                return false;
            }
            e.preventDefault();
        });
    });
</script>