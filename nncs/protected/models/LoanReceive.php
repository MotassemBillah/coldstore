<?php

class LoanReceive extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{loan_receive}}';
    }

    public function rules() {
        return array(
            array('receive_number', 'unique'),
        );
    }

    public function relations() {
        return array(
            'items' => array(self::HAS_MANY, 'LoanReceiveItem', 'receive_id'),
            'sumQty' => array(self::STAT, 'LoanReceiveItem', 'receive_id', 'select' => 'SUM(qty)'),
            'sumLoan' => array(self::STAT, 'LoanReceiveItem', 'receive_id', 'select' => 'SUM(loan_amount)'),
            'sumInterest' => array(self::STAT, 'LoanReceiveItem', 'receive_id', 'select' => 'SUM(interest_amount)'),
            'sumAmount' => array(self::STAT, 'LoanReceiveItem', 'receive_id', 'select' => 'SUM(net_amount)'),
        );
    }

    public function sumTotal($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(total_loan_amount) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : "";
    }

    public function lastNumber() {
        $_maxLcn = Yii::app()->db->createCommand()
                ->select('max(id)+1 as rn')
                ->from($this->tableName())
                ->queryRow();

        return !empty($_maxLcn['rn']) ? $_maxLcn['rn'] : 1;
    }

}
