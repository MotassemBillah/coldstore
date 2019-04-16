<?php $this->breadcrumbs = array('Customer'); ?>
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
                            <div class="col-md-2 col-sm-3 no_pad">
                                <?php $schemaInfo = Customer::model()->schemaInfo(); ?>
                                <select id="sortBy" class="form-control" name="sort_by">
                                    <option value="">Sort By</option>
                                    <?php
                                    foreach ($schemaInfo->columns as $_key => $columns) {
                                        $_nice_key = str_replace("_", " ", $_key);
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
                            <input type="text" name="search" id="q" class="form-control" placeholder="search name or mobile" size="30">
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_from" class="btn btn-primary" data-info="/customer"><?php echo Yii::t("strings", "Clear"); ?></button>
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right wmd_30" style="">
                <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_CUSTOMER_CREATE); ?>"><button class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "New"); ?></button></a>
                <?php if ($this->hasUserAccess('customer_delete')): ?>
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
                <table class="table table-striped table-bordered tbl_invoice_view">
                    <tr class="bg_gray" id="r_checkAll">
                        <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                        <th><?php echo Yii::t('strings', 'Name'); ?></th>
                        <th><?php echo Yii::t('strings', 'Father Name'); ?></th>
                        <th><?php echo Yii::t('strings', 'Mobile'); ?></th>
                        <th><?php echo Yii::t('strings', 'Address'); ?></th>
                        <th class="text-center"><?php echo Yii::t('strings', 'Actions'); ?></th>
                        <?php if ($this->hasUserAccess('customer_delete')): ?>
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
                        <tr>
                            <td class="text-center"><?php echo $counter; ?></td>
                            <td><?php echo AppHelper::getCleanValue($data->name); ?></td>
                            <td><?php echo AppHelper::getCleanValue($data->father_name); ?></td>
                            <td><?php echo AppHelper::getCleanValue($data->mobile); ?></td>
                            <td>
                                <?php
                                if (!empty($data->district)) {
                                    echo "District : " . $data->district . "\r\n";
                                }
                                if (!empty($data->thana)) {
                                    echo " | Thana : " . $data->thana . "\r\n";
                                }
                                echo " | Village : " . $data->village . "\r\n";
                                ?>
                            </td>
                            <td class="text-center">
                                <?php if ($this->hasUserAccess('customer_edit')): ?>
                                    <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_CUSTOMER_EDIT, array('id' => $data->_key)); ?>"><?php echo Yii::t('strings', 'Edit'); ?></a>
                                <?php endif; ?>
                                <a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_CUSTOMER_LEDGER, ['id' => $data->_key]); ?>"><?php echo Yii::t('strings', 'Ledger'); ?></a>
                            </td>
                            <?php if ($this->hasUserAccess('customer_delete')): ?>
                                <td class="text-center"><input type="checkbox" name="data[]" value="<?php echo $data->id; ?>" class="check"></td>
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