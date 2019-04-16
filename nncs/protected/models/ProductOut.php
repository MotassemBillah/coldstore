<?php

class ProductOut extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{product_out}}';
    }

    public function rules() {
        return array(
            array('sr_no,delivery_sr_no,lot_no,quantity', 'required'),
            array('sr_no', 'checkSrNo', 'on' => 'update'),
            array('delivery_sr_no', 'unique'),
            array('agent_code', 'checkAgent'),
            array('customer_id,sr_no,delivery_sr_no,quantity,loan_pack,lot_no,advance_booking_no', 'safe'),
        );
    }

    public function relations() {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'customer_payment' => array(self::HAS_ONE, 'CustomerPayment', 'pout_id'),
            'payment' => array(self::HAS_ONE, 'PaymentOut', 'pout_id'),
            'entry' => array(self::BELONGS_TO, 'ProductIn', 'sr_no'),
            'stock' => array(self::HAS_ONE, 'Stock', 'product_out_id'),
        );
    }

    public function checkAgent() {
        if (!empty($this->agent_code)) {
            $_obj = Agent::model()->exists('code=:code', array(':code' => $this->agent_code));
            if (!$_obj) {
                $this->addError('agent_code', Yii::t('strings', 'Sorry, That agent code number is invalid or not exist.'));
            }
        }
    }

    public function checkSrNo() {
        if ($this->scenario == 'update') {
            $_obj = ProductIn::model()->exists('sr_no=:sr_no AND customer_id=:cid', array(':sr_no' => $this->sr_no, ':cid' => $this->customer_id));
            if (!$_obj) {
                $this->addError('sr_no', Yii::t('strings', 'Sorry, That SR number for this customer is invalid or not exist.'));
            }
        }
    }

}
