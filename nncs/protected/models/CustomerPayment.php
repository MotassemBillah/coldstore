<?php

class CustomerPayment extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{customer_payments}}';
    }

    public function rules() {
        return array(
            array('sr_no', 'checkSrNo', 'on' => 'payment'),
        );
    }

    public function relations() {
        return array(
            'pout' => array(self::BELONGS_TO, 'ProductOut', 'product_out_id'),
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
        );
    }

    public function checkSrNo() {
        if ($this->scenario == 'payment') {
            $_obj = ProductIn::model()->exists('sr_no=:sr_no AND customer_id=:cid', array(':sr_no' => $this->sr_no, ':cid' => $this->customer_id));
            if (!$_obj) {
                $this->addError('sr_no', Yii::t('strings', 'Sorry, That SR number is invalid or not exist.'));
            }
        }
    }

    public function typeList() {
        return array(
            'Due Payment' => 'Due Payment',
            'Delivery Payment' => 'Delivery Payment',
            'Loan Payment' => 'Loan Payment',
        );
    }

}
