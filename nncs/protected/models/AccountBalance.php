<?php

class AccountBalance extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{account_balance}}';
    }

    public function rules() {
        return array(
            array('amount', 'required'),
        );
    }

    public function relations() {
        return array(
            'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
        );
    }

    public function categoryList() {
        return array(
            'Cash In' => 'Cash In',
            'Cash Out' => 'Cash Out',
        );
    }

}
