<?php

class Loan extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{loan}}';
    }

    public function rules() {
        return array(
            array('case_no', 'required', 'message' => 'Loan Case Number cannot be blank.'),
            array('case_no', 'unique'),
        );
    }

    public function relations() {
        return array(
            'items' => array(self::HAS_MANY, 'LoanItem', 'loan_id'),
            'sumAmount' => array(self::STAT, 'LoanItem', 'loan_id', 'select' => 'SUM(net_amount)'),
        );
    }

    public function sumTotal() {
        $data = Yii::app()->db->createCommand()->select('SUM(net_amount) as total')->from('loan_items')->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function getLastCaseNo() {
        $_maxLcn = Yii::app()->db->createCommand()
                ->select('max(case_no)+1 as lcn')
                ->from($this->tableName())
                ->queryRow();

        return !empty($_maxLcn['lcn']) ? $_maxLcn['lcn'] : 1;
    }

}
