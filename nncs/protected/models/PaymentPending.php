<?php

class PaymentPending extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{payment_pending}}';
    }

    public function rules() {
        return array();
    }

    public function relations() {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'pin' => array(self::BELONGS_TO, 'ProductIn', 'product_in_id'),
        );
    }

    public function getObj($sr) {
        return Payment::model()->find("sr_no=:sr", [":sr" => $sr]);
    }

}
