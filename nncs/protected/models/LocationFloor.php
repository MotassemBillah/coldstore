<?php

class LocationFloor extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{location_floor}}';
    }

    public function rules() {
        return array(
                //array('floor_no,room_no', 'required'),
        );
    }

    public function relations() {
        return array(
            'pockets' => array(self::HAS_MANY, 'LocationPocket', 'floor_id'),
        );
    }

    public function getList($id = NULL) {
        $criteria = new CDbCriteria();
        if ($id !== NULL) {
            $criteria->condition = "room_id=:rid";
            $criteria->params = [":rid" => $id];
        }
        $criteria->order = "name ASC";
        $_dataset = LocationFloor::model()->findAll($criteria);
        return $_dataset;
    }

}
