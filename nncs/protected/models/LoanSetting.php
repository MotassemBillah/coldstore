<?php

class LoanSetting extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{loan_setting}}';
    }

    public function rules() {
        return array(
            array('interest_rate,period', 'required'),
            array('min_day', 'required', 'message' => 'Please mension minimum day count for loan.'),
        );
    }

}
