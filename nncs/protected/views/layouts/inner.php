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

        <div class="clear common-gap"></div>

        <div id="bodyPanel">
            <div class="wrapper">
                <div id="leftContent">
                    <?php $this->beginContent('/layouts/widgets/left-menu'); ?>
                    <?php $this->endContent(); ?>
                </div>
                <div id="rightContent">
                    <?php echo $content; ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div id="footerPanel">
            <div id="footerBottom">
                <div class="wrapper">
                    <p>2013 &copy; Copyright Protected. All Right Reserved By E-TRADE Ltd.</p>
                </div>
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