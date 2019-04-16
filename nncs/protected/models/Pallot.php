<?php

class Pallot extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{pallot}}';
    }

    public function rules() {
        return array(
            array('pallot_number', 'required'),
                //array('pallot_number', 'unique'),
        );
    }

    public function relations() {
        return array(
            'items' => array(self::HAS_MANY, 'PallotItem', 'pallot_id'),
            'sumQty' => array(self::STAT, 'PallotItem', 'pallot_id', 'select' => 'SUM(quantity)'),
        );
    }

    public function lastNumber($srno = null) {
        $_maxNum = Yii::app()->db->createCommand()
                ->select("max(pallot_number)+1 as num")
                ->where("sr_no={$srno}")
                ->from($this->tableName())
                ->queryRow();

        return !empty($_maxNum['num']) ? $_maxNum['num'] : 1;
    }

}
