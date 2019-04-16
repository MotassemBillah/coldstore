<?php

class LoanPaymentAdvance extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{loan_payment_adv}}';
    }

    public function rules() {
        return array(
            array('case_no', 'required', 'message' => 'Loan Case Number cannot be blank.'),
            array('case_no', 'unique'),
        );
    }

    public function relations() {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
        );
    }

    public function sumAmountAgent($code) {
        $total = Yii::app()->db->createCommand()
                ->select('SUM(balance) as amount')
                ->from($this->tableName())
                ->where('agent_code=' . $code)
                ->queryRow();
        return !empty($total['amount']) ? $total['amount'] : 0;
    }

    public function sumAmountCustomer($cid) {
        $total = Yii::app()->db->createCommand()
                ->select('SUM(balance) as amount')
                ->from($this->tableName())
                ->where('customer_id=' . $cid)
                ->queryRow();
        return !empty($total['amount']) ? $total['amount'] : 0;
    }

}
