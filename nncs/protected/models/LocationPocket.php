<?php

class LocationPocket extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{location_pocket}}';
    }

    public function rules() {
        return array();
    }

    public function relations() {
        return array(
            'room' => array(self::BELONGS_TO, 'LocationRoom', 'room_id'),
            'floor' => array(self::BELONGS_TO, 'LocationFloor', 'floor_id'),
        );
    }

    public function getList($id = NULL) {
        $criteria = new CDbCriteria();
        if ($id !== NULL) {
            $criteria->condition = "floor_id=:fid";
            $criteria->params = [":fid" => $id];
        }
        $criteria->order = "pocket_no ASC";
        $_dataset = LocationPocket::model()->findAll($criteria);
        return $_dataset;
    }

}
