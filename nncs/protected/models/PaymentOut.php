<?php

class PaymentOut extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{payment_out}}';
    }

    public function rules() {
        return array();
    }

    public function relations() {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'pout' => array(self::BELONGS_TO, 'ProductOut', 'pout_id'),
        );
    }

}
