<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language; ?>">
    <head>
        <?php $this->beginContent('/layouts/panels/header'); ?>
        <?php $this->endContent(); ?>
    </head>
    <body class="body_pad">
        <div id="headerPanel">
            <?php $this->beginContent('/layouts/panels/headerPanel'); ?>
            <?php $this->endContent(); ?>
        </div>

        <div id="bodyPanel">
            <div class="wrapper">
                <div id="SearchPanel">
                    <?php $this->widget('Search'); ?>
                </div>

                <div id="homeContentPanel">
                    <div class="" style="margin:10px 0px;">
                        <div class="app-left-menu" id="controlNavigation">
                            <?php $this->beginContent('/layouts/panels/sidebar'); ?>
                            <?php $this->endContent(); ?>
                        </div>
                        <div class="app-right-content" id="controlContent">
                            <?php echo $content; ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div id="footerPanel">
            <div class="wrapper">
                <p>2013 &copy; Copyright Protected. All Right Reserved.</p>
            </div>
        </div>
        <div id="popup"></div>
        <div id="superLoader">
            <img alt="loading..." class="img-responsive" src="<?php echo Yii::app()->request->baseUrl; ?>/img/loading.gif">
            <span id="superLoaderText"></span>
        </div>
        <div id="cover"></div>
    </body>
</html>