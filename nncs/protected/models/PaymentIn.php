<?php

class PaymentIn extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{payment_in}}';
    }

    public function rules() {
        return array();
    }

    public function relations() {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'pin' => array(self::BELONGS_TO, 'ProductIn', 'pin_id'),
        );
    }

}
