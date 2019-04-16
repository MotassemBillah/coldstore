<?php

class LoanReceiveItem extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{loan_receive_items}}';
    }

    public function relations() {
        return array(
            'reff' => array(self::BELONGS_TO, 'LoanReceive', 'receive_id'),
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
        );
    }

    public function sumQty($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(qty) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumLoan($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(loan_amount) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumInterest($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(interest_amount) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumTotalAmount($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(total_amount) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumDiscount($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(discount) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumTotal($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(net_amount) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    /* Date wise sum count functions */

    public function sumLoanByDate($date = null) {
        $_date = date('Y-m-d', strtotime($date));
        $_where = "receive_date='{$_date}'";
        $data = Yii::app()->db->createCommand()->select('SUM(loan_amount) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumInterestByDate($date = null) {
        $_date = date('Y-m-d', strtotime($date));
        $_where = "receive_date='{$_date}'";
        $data = Yii::app()->db->createCommand()->select('SUM(interest_amount) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function loanByDate($srno, $date) {
        $_date = date('Y-m-d', strtotime($date));
        $_where = "sr_no={$srno} AND receive_date='{$_date}'";
        $data = Yii::app()->db->createCommand()->select('loan_amount as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function interestByDate($srno, $date) {
        $_date = date('Y-m-d', strtotime($date));
        $_where = "sr_no={$srno} AND receive_date='{$_date}'";
        $data = Yii::app()->db->createCommand()->select('interest_amount as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    /* Search By Date */

    public function sumTotalBetweenDate($date1, $date2) {
        $_date1 = date("Y-m-d", strtotime($date1));
        $_date2 = date("Y-m-d", strtotime($date2));
        $_where = "receive_date BETWEEN CAST('{$_date1}' AS DATE) AND CAST('{$_date2}' AS DATE)";
        $data = Yii::app()->db->createCommand()->select('SUM(loan_amount) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    /* Search By Delivery Number */

    public function loanByDelivery($num) {
        $_where = "delivery_number={$num}";
        $data = Yii::app()->db->createCommand()->select('loan_amount as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function interestByDelivery($num) {
        $_where = "delivery_number={$num}";
        $data = Yii::app()->db->createCommand()->select('interest_amount as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

}
