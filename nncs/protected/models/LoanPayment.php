<?php

class LoanPayment extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{loan_payment}}';
    }

    public function rules() {
        return array(
                //array('sr_no', 'unique'),
        );
    }

    public function relations() {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
                //'customer' => array(self::BELONGS_TO, 'LoanPending', 'lp_id'),
        );
    }

    public function getObj($option = array()) {
        if (!is_array($option)) {
            throw new CException(Yii::t("App", "Conditions must be supplied as array"));
        }

        $criteria = new CDbCriteria();
        foreach ($option as $key => $val) {
            $criteria->condition = "$key = :$key ";
            $criteria->params = array(":$key" => $val);
        }
        return $this->find($criteria);
    }

}
