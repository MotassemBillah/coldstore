<?php

class StockLocation extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{stock_location}}';
    }

    public function rules() {
        return array();
    }

    public function relations() {
        return array(
            'stock' => array(self::BELONGS_TO, 'Stock', 'stock_id'),
        );
    }

}
