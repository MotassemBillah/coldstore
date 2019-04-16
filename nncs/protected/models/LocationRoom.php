<?php

class LocationRoom extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{location_room}}';
    }

    public function rules() {
        return array();
    }

    public function relations() {
        return array(
            'floors' => array(self::HAS_MANY, 'LocationFloor', 'room_id'),
            'pockets' => array(self::HAS_MANY, 'LocationPocket', 'room_id'),
        );
    }

    public function getList() {
        $criteria = new CDbCriteria();
        $criteria->order = "name ASC";
        $_dataset = LocationRoom::model()->findAll($criteria);
        return $_dataset;
    }

}
