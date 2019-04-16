<?php
$this->breadcrumbs = array(
    'Location' => array(AppUrl::URL_LOCATION),
    'Create Pocket'
);
?>
<div class="row content-panel">
    <div class="col-md-4 col-sm-6">
        <form action="" method="post">
            <div class="clearfix">
                <div class="form-group">
                    <label><?php echo Yii::t("strings", "Room Name"); ?></label>
                    <?php
                    $roomList = LocationRoom::model()->getList();
                    $rmList = CHtml::listData($roomList, 'id', 'name');
                    echo CHtml::dropDownList('room_id', 'room_id', $rmList, array('empty' => 'Select', 'class' => 'form-control', 'required' => 'required'));
                    ?>
                </div>
                <div class="form-group">
                    <label><?php echo Yii::t("strings", "Floor Name"); ?></label>
                    <?php
                    $floorList = LocationFloor::model()->getList();
                    $flrList = CHtml::listData($floorList, 'id', 'name');
                    echo CHtml::dropDownList('floor_id', 'floor_id', $flrList, array('empty' => 'Select', 'class' => 'form-control', 'required' => 'required'));
                    ?>
                </div>
                <div class="form-group">
                    <label><?php echo Yii::t("strings", "Pocket No"); ?></label>
                    <?php for ($i = 0; $i < 10; $i++) : ?>
                        <input class="form-control" name="pocket_no[]" type="text">
                    <?php endfor; ?>
                </div>
                <div class="form-group text-right">
                    <input class="btn btn-primary btn-block" name="create_pocket" type="submit" value="Save">
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        //disable("#type");
        $(document).on("change", "#room_id", function() {
            //showLoader("Processing...", true);
            var _url = ajaxUrl + "/misc/find_floor";

            if ($(this).val() !== "") {
                $.post(_url, {room: $(this).val()}, function(resp) {
                    if (resp.success === true) {
                        $("#floor_id").html(resp.html);
                    } else {
                        $("#floor_id").html(resp.html);
                    }
                    showLoader("", false);
                }, "json");
            } else {
                $("#floor_id").html("<option value=''>Not Found</option>");
                showLoader("", false);
            }
        });
    });
</script>