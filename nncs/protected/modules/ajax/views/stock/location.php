<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Close"><span aria-hidden="true">x</span></button>
            <h3 class="modal-title"><?php echo Yii::t("strings", $modatTitle); ?></h3>
            <div id="ajaxModalMessage" class="alert" style="display: none"></div>
        </div>
        <form id="frmLocationSet" action="" method="post">
            <input type="hidden" name="stockID" value="<?php echo $stockID; ?>">
            <div class="modal-body" style="height:auto;overflow-y:auto;">
                <div class="row clearfix">
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <label><?php echo Yii::t("strings", "Room"); ?></label>
                            <?php
                            if (!empty($locationInfo)) {
                                $rmid = LocationRoom::model()->findByPk($locationInfo->room_id);
                                $flrid = LocationFloor::model()->findByPk($locationInfo->floor_id);
                                $roomList = LocationRoom::model()->getList();
                                $floorList = LocationFloor::model()->getList($locationInfo->room_id);

                                if (!empty($locationInfo->room_id)) {
                                    $floorList = LocationFloor::model()->getList($locationInfo->room_id);
                                } else {
                                    $floorList = LocationFloor::model()->getList();
                                }

                                if (!empty($locationInfo->floor_id)) {
                                    $pocketList = LocationPocket::model()->getList($locationInfo->floor_id);
                                } else {
                                    $pocketList = LocationPocket::model()->getList($locationInfo->floor_id);
                                }
                            } else {
                                $roomList = LocationRoom::model()->getList();
                                $floorList = LocationFloor::model()->getList();
                                $pocketList = LocationPocket::model()->getList();
                            }

                            $rmList = CHtml::listData($roomList, 'id', 'name');
                            if (!empty($locationInfo)) {
                                echo CHtml::dropDownList('room', $rmid, $rmList, array('empty' => 'Select', 'class' => 'form-control', 'required' => 'required'));
                            } else {
                                echo CHtml::dropDownList('room', 'room', $rmList, array('empty' => 'Select', 'class' => 'form-control', 'required' => 'required'));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <label><?php echo Yii::t("strings", "Floor"); ?></label>
                            <?php
                            $fList = CHtml::listData($floorList, 'id', 'name');
                            if (!empty($locationInfo)) {
                                echo CHtml::dropDownList('floor', $flrid, $fList, array('empty' => 'Select', 'class' => 'form-control', 'required' => 'required'));
                            } else {
                                echo CHtml::dropDownList('floor', 'floor', $fList, array('empty' => 'Select', 'class' => 'form-control', 'required' => 'required'));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <ul class="block_list" id="pockets">
                            <?php
                            if (!empty($locationInfo)) {
                                if (!empty($locationInfo->pockets)) {
                                    $_pkets = json_decode($locationInfo->pockets);
                                    foreach ($pocketList as $_pkt) {
                                        if (in_array($_pkt->pocket_no, $_pkets)) {
                                            $_ckd = ' checked';
                                        } else {
                                            $_ckd = '';
                                        }
                                        echo "<li><label style='font-weight:500' for='pkt_{$_pkt->id}'><input id='pkt_{$_pkt->id}' type='checkbox' name='pockets[]' value='{$_pkt->pocket_no}' {$_ckd}>&nbsp;{$_pkt->pocket_no}</label></li>";
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button type="button" class="btn btn-info" data-dismiss="modal" aria-label="Close" title="Close"><?php echo Yii::t("strings", "Cancel"); ?></button>
                <button type="button" class="btn btn-primary" id="processFrm"><?php echo Yii::t("strings", $btnText); ?></button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("change", "#room", function() {
            $("#pockets").html("<li>No pocket found.<li>");
            var _url = ajaxUrl + "/misc/find_floor";

            if ($(this).val() !== "") {
                $.post(_url, {room: $(this).val()}, function(resp) {
                    if (resp.success === true) {
                        $("#floor").html(resp.html);
                    } else {
                        $("#floor").html(resp.html);
                    }
                }, "json");
            } else {
                $("#floor").html("<option value=''>Not Found</option>");
                $("#pockets").html("<li>No pocket found.<li>");
            }
        });

        $(document).on("change", "#floor", function() {
            var _url = ajaxUrl + "/misc/find_pocket_by_floor";

            if ($(this).val() !== "") {
                $.post(_url, {room: $("#room").val(), floor: $(this).val()}, function(resp) {
                    if (resp.success === true) {
                        $("#pockets").html(resp.html);
                    } else {
                        $("#pockets").html(resp.html);
                    }
                }, "json");
            } else {
                $("#pockets").html("<li>No pocket found.<li>");
            }
        });

        $(document).on("click", "#processFrm", function(e) {
            e.preventDefault();
            var _form = $("#frmLocationSet");
            var _url = ajaxUrl + '/stock/location_save';

            $.post(_url, _form.serialize(), function(response) {
                if (response.success === true) {
                    $("#ajaxModalMessage").showAjaxMessage({html: response.message, type: 'success'});
                    window.location.reload();
                } else {
                    $("#ajaxModalMessage").showAjaxMessage({html: response.message, type: 'error'});
                }
            }, "json");
            e.preventDefault();
        });
    });
</script>