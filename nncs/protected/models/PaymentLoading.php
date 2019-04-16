<?php

class PaymentLoading extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{payment_load_unload}}';
    }

    public function rules() {
        return array(
            array('sr_no,quantity_price,pament_for', 'required'),
            array('sr_no', 'srIsAvailable'),
            array('sr_no,quantity,quantity_price,price_total,_key', 'safe'),
        );
    }

    public function relations() {
        return array(
                //'items' => array(self::HAS_MANY, 'LoanItem', 'loan_id'),
                //'sumAmount' => array(self::STAT, 'LoanItem', 'loan_id', 'select' => 'SUM(net_amount)'),
        );
    }

    public function srIsAvailable() {
        if (!empty($this->sr_no)) {
            $_obj = ProductIn::model()->exists('sr_no=:sr', array(':sr' => $this->sr_no));
            if (!$_obj) {
                $this->addError('sr_no', Yii::t('strings', 'Sorry, The Sr Number is invalid or dose not exist.'));
            }
        }
    }

}
