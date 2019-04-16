<div id="footerBottom">
    <div class="container-fluid">
        <div class="text-center">
            <p>
                <?php echo Yii::app()->params['copyrightInfo']; ?><br>
                <?php
                if (YII_DEBUG) {
                    echo "PLT = " . Yii::getLogger()->getExecutionTime();
                }
                ?>
            </p>
        </div>
    </div>
</div>