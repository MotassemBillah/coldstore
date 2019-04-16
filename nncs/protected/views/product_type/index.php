<?php $this->breadcrumbs = array('Product Type'); ?>
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
                            <input type="text" name="search" id="q" class="form-control" placeholder="search name or mobile" size="30">
                            <button type="button" id="search" class="btn btn-info"><?php echo Yii::t("strings", "Search"); ?></button>
                            <button type="button" id="clear_from" class="btn btn-primary" data-info="/product/type"><?php echo Yii::t("strings", "Clear"); ?></button>
                        </div>
                    </div>
                </form>
            </td>
            <td class="text-right wmd_30" style="">
                <?php if ($this->hasUserAccess('product_type_create')): ?>
                    <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_PRODUCT_TYPE_CREATE); ?>"><button class="btn btn-success btn-xs"><i class="fa fa-plus"></i>&nbsp;<?php echo Yii::t("strings", "New"); ?></button></a>
                <?php endif; ?>
                <button type="button" class="btn btn-primary btn-xs" onclick="printDiv('printDiv')"><i class="fa fa-print"></i>&nbsp;<?php echo Yii::t("strings", "Print"); ?></button>
            </td>
        </tr>
    </table>
</div>
<form id="deleteForm" action="" method="post">
    <div id="printDiv">
        <div class="clearfix text-center txt_left_xs mp_center mb_10 show_in_print mp_mt">
            <?php if (!empty($this->settings->logo)) : ?>
                <img alt="" src="<?php echo Yii::app()->request->baseUrl . '/uploads/' . $this->settings->logo; ?>" style="max-height:50px;position:absolute;left:0;top:0;">
            <?php endif; ?>
            <?php if (!empty($this->settings->title)): ?>
                <h1 style="font-size:20px;margin:0;"><?php echo $this->settings->title; ?></h1>
            <?php endif; ?>
            <?php if (!empty($this->settings->author_address)): ?>
                <?php echo $this->settings->author_address; ?><br>
            <?php endif; ?>
            <h3 class="inv_title" style="font-size:18px;"><u><?php echo Yii::t("strings", "Stock List By Product Type"); ?></u></h3>
        </div>
        <div id="ajaxContent">
            <?php if (!empty($dataset) && count($dataset) > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered tbl_invoice_view">
                        <tr id="r_checkAll" class="bg_gray">
                            <th class="text-center" style="width:5%;"><?php echo Yii::t('strings', 'SL#'); ?></th>
                            <th><?php echo Yii::t('strings', 'Name'); ?></th>
                            <th><?php echo Yii::t('strings', 'Quantity In'); ?></th>
                            <th><?php echo Yii::t('strings', 'Quantity Out'); ?></th>
                            <th><?php echo Yii::t('strings', 'Quantity Remain'); ?></th>
                            <th class="text-center dis_print" style="width:10%;"><?php echo Yii::t('strings', 'Actions'); ?></th>
                        </tr>
                        <?php
                        $counter = 0;
                        if (isset($_GET['page']) && $_GET['page'] > 1) {
                            $counter = ($_GET['page'] - 1) * $pages->pageSize;
                        }
                        foreach ($dataset as $data):
                            $counter++;
                            $_out = DeliveryItem::model()->sumQtyOfType($data->id);
                            $_remain = ($data->sumAmount - $_out);
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $counter; ?></td>
                                <td><?php echo AppHelper::getCleanValue($data->name); ?></td>
                                <td><?php echo $data->sumAmount; ?></td>
                                <td><?php echo $_out; ?></td>
                                <td><?php echo $_remain; ?></td>
                                <td class="text-center dis_print">
                                    <?php if ($this->hasUserAccess('product_type_edit')): ?>
                                        <a class="btn btn-info btn-xs" href="<?php echo $this->createUrl(AppUrl::URL_PRODUCT_TYPE_EDIT, ['id' => $data->_key]); ?>"><?php echo Yii::t('strings', 'Edit'); ?></a>
                                    <?php endif; ?>
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
    </div>
</form>