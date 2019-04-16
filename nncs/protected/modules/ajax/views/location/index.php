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
                    <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOCATION_CREATE); ?>"><button class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "Floor"); ?></button></a>
                    <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOCATION_CREATE_ROOM); ?>"><button class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "Room"); ?></button></a>
                    <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOCATION_CREATE_POCKET); ?>"><button class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "Pocket"); ?></button></a>
                <?php endif; ?>
                <?php if ($this->hasUserAccess('location_delete')): ?>
                    <button type="button" class="btn btn-danger btn-xs" id="admin_del_btn" disabled="disabled" ><?php echo Yii::t("strings", "Delete"); ?></button>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>
<form id="deleteForm" action="" method="post">
    <div id="ajaxContent">
        <?php if (!empty($dataset) && count($dataset) > 0) : ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr class="bg_gray" id="r_checkAll">
                        <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                        <th><?php echo Yii::t("strings", "Floor No"); ?></th>
                        <th><?php echo Yii::t("strings", "Room No"); ?></th>
                        <th><?php echo Yii::t("strings", "Pocket No"); ?></th>
                        <th class="text-center" style=""><?php echo Yii::t("strings", "Actions"); ?></th>
                        <?php if ($this->hasUserAccess('location_delete')): ?>
                            <th class="text-center" style="width:3%;"><input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)"></th>
                        <?php endif; ?>
                    </tr>
                    <?php
                    $counter = 0;
                    if (isset($_GET['page']) && $_GET['page'] > 1) {
                        $counter = ($_GET['page'] - 1) * $this->settings->page_size;
                    }
                    foreach ($dataset as $data) :
                        $counter++;
                        ?>
                        <tr>
                            <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                            <td><?php echo AppHelper::getCleanValue($data->floor_no); ?></td>
                            <td>
                                <?php
                                if (!empty($data->rooms)) {
                                    foreach ($data->rooms as $room) {
                                        echo "<span style='display:block'>{$room->room_no}</span>";
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (!empty($data->pockets)) {
                                    foreach ($data->pockets as $pocket) {
                                        echo "<span style='display:block'>{$pocket->pocket_no}</span>";
                                    }
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php if ($this->hasUserAccess('location_edit')): ?>
                                    <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_LOCATION_EDIT, ['id' => $data->_key]); ?>"><?php echo Yii::t("strings", "Edit"); ?></a>
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
</form>