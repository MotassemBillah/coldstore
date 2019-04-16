<?php

class Expense extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{expenses}}';
    }

    public function rules() {
        return array(
            array('ledger_head_id', 'required', 'message' => 'You must select a ledger head'),
            array('by_whom,purpose,amount', 'required'),
        );
    }

    public function relations() {
        return array(
                //'head' => array(self::BELONGS_TO, 'LedgerHead', 'ledger_head_id'),
        );
    }

    public function sumTotal() {
        $data = Yii::app()->db->createCommand()->select('SUM(amount) as total')->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

}
