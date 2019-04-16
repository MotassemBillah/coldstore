<?php

class ProductType extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{product_type}}';
    }

    public function rules() {
        return array(
            array('name', 'required'),
        );
    }

    public function relations() {
        return array(
            'sumAmount' => array(self::STAT, 'ProductIn', 'type', 'select' => 'SUM(quantity)'),
        );
    }

    public function getList() {
        $criteria = new CDbCriteria();
        $criteria->order = "name ASC";
        $_dataset = ProductType::model()->findAll($criteria);
        return $_dataset;
    }

}
