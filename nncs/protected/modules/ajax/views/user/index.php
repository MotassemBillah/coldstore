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
            'maxButtonCount' => 10,
            'htmlOptions' => array(
                'class' => 'pagination',
                'id' => 'pagination',
            )
        ));
        ?>
    </div>
<?php else: ?>
    <div class="alert alert-info">No records found!</div>
<?php endif; ?>