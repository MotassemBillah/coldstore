<?php
$this->breadcrumbs = array(
    'Location' => array(AppUrl::URL_LOCATION),
    'Create Room'
);
?>
<div class="row content-panel">
    <div class="col-md-4 col-sm-6">
        <form action="" method="post">
            <div class="clearfix">
                <div class="form-group">
                    <label><?php echo Yii::t("strings", "Room Name"); ?></label>
                    <?php for ($i = 0; $i < 5; $i++) : ?>
                        <input class="form-control" name="room_name[]" type="text">
                    <?php endfor; ?>
                </div>
                <div class="form-group text-right">
                    <input class="btn btn-primary btn-block" name="create_room" type="submit" value="Save">
                </div>
            </div>
        </form>
    </div>
</div>