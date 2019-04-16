<?php

class Payment extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{payments}}';
    }

    public function rules() {
        return array(
            array('check_no', 'unique')
        );
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

    public function checkSrNo() {
        if ($this->scenario == 'payment') {
            $_obj = ProductIn::model()->exists('sr_no=:sr_no AND customer_id=:cid', array(':sr_no' => $this->sr_no, ':cid' => $this->customer_id));
            if (!$_obj) {
                $this->addError('sr_no', Yii::t('strings', 'Sorry, That SR number is invalid or not exist.'));
            }
        }
    }

}
