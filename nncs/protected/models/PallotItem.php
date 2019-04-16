<?php

class PallotItem extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{pallot_items}}';
    }

    public function rules() {
        return array(
            array('sr_no,room,floor,pocket,quantity', 'required'),
            array('sr_no,room,floor,pocket,quantity', 'safe'),
        );
    }

    public function relations() {
        return array(
            'pallot' => array(self::BELONGS_TO, 'Pallot', 'pallot_id'),
        );
    }

    public function sumTotal($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(quantity) as qty')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['qty']) ? $data['qty'] : "";
    }

}
