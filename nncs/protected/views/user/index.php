<?php $this->breadcrumbs = array('Users'); ?>
<div class="well">
    <table width="100%">
        <tr>
            <td class="wmd_70 wxs_100">
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
                            <div class="col-md-2 col-sm-3 no_pad">
                                <?php $schemaInfo = User::model()->schemaInfo(); ?>
                                <select id="sortBy" class="form-control" name="sort_by">
                                    <option value="">Sort By</option>
                                    <?php
                                    foreach ($schemaInfo->columns as $_key => $columns) {
                                        $_nice_key = str_replace("_", " ", $_key);
                                        if ($_key === "display_name") {
                                            $_nice_key = "Username";
                                        }
                                        echo "<option value='{$_key}' style='text-transform:capitalize'>" . ucfirst($_nice_key) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-3 no_pad">
                                <select id="sortType" class="form-control" name="sort_type">
                                    <option value="ASC">Ascending</option>
                                    <option value="DESC">Descending</option>
                                </select>
                            </div>
                            <input type="text" name="q" id="q" class="form-control" placeholder="search name or email" size="30"/>
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_from" class="btn btn-primary" data-info="/user">Clear</button>
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right wmd_30 wxs_100">
                <a class="btn btn-success btn-xs" href="<?php echo Yii::app()->createUrl(AppUrl::URL_USER_CREATE); ?>"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "New"); ?></a>
                <?php if ($this->hasUserAccess('user_delete')): ?>
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
                <table class="table table-bordered table-hover">
                    <tr class="bg_gray" id="r_checkAll">
                        <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                        <th><?php echo Yii::t('strings', 'Username'); ?></th>
                        <th><?php echo Yii::t('strings', 'Email'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Status'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Loggedin'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Actions'); ?></th>
                        <?php if ($this->hasUserAccess('user_delete')): ?>
                            <th class="text-center" style="width:3%;"><input type="checkbox" id="checkAll" onclick="toggleCheckboxes(this)"></th>
                        <?php endif; ?>
                    </tr>
                    <?php
                    $counter = 0;
                    foreach ($dataset as $data):
                        $counter++;
                        ?>
                        <tr>
                            <td class="text-center" style="width:5%;"><?php echo $counter; ?></td>
                            <td><?php echo $data->displayname(); ?></td>
                            <td><?php echo AppHelper::getCleanValue($data->email); ?></td>
                            <td class="text-center">
                                <?php
                                $userStatus = AppObject::userStatus($data->status);
                                if ($userStatus == "Active") {
                                    $btn_class = "btn btn-success btn-xs";
                                    $_link = $this->createUrl(AppUrl::URL_USER_DEACTIVATE, array('id' => $data->_key));
                                } else {
                                    $btn_class = "btn btn-warning btn-xs";
                                    $_link = $this->createUrl(AppUrl::URL_USER_ACTIVATE, array('id' => $data->_key));
                                }
                                if ($this->hasUserAccess('user_activate')):
                                    echo "<a class='{$btn_class}' href='{$_link}'>" . Yii::t('strings', $userStatus) . "</a>";
                                else:
                                    echo "<span class='{$btn_class}'>" . Yii::t('strings', $userStatus) . "</span>";
                                endif;
                                ?>
                            </td>
                            <td class="text-center"><?php echo ($data->is_loggedin == 1) ? "Yes" : "No"; ?></td>
                            <td class="text-center">
                                <?php if ($this->hasUserAccess('admin_user_edit')): ?>
                                    <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_USER_ADMIN_EDIT, array('id' => $data->_key)); ?>"><?php echo Yii::t('strings', 'Edit'); ?></a>
                                <?php endif; ?>
                                <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_USER_PROFILE, array('id' => $data->_key)); ?>"><?php echo Yii::t('strings', 'View'); ?></a>
                                <?php if ($this->hasUserAccess('access_control') && $data->deletable == 1): ?>
                                    <a class="btn btn-warning btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_USER_PERMISSION, array('id' => $data->_key)); ?>"><?php echo Yii::t('strings', 'Access Control'); ?></a>
                                <?php endif; ?>
                            </td>
                            <?php if ($this->hasUserAccess('user_delete')): ?>
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
                    'maxButtonCount' => 5,
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
<div id="container_for_modal" class="modal fade" tabindex="-1" style=""></div>