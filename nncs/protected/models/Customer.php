<?php

class Customer extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{customers}}';
    }

    public function rules() {
        return array(
            array('name', 'required'),
            array('mobile', 'unique'),
            array('mobile', 'numerical', 'integerOnly' => true),
        );
    }

    public function relations() {
        return array(
            'entries' => array(self::HAS_MANY, 'ProductIn', 'customer_id'),
            'deliveries' => array(self::HAS_MANY, 'ProductOut', 'customer_id'),
            'payments' => array(self::HAS_MANY, 'CustomerPayment', 'customer_id'),
            'stocks' => array(self::HAS_MANY, 'Stock', 'customer_id'),
            'loans' => array(self::HAS_MANY, 'CustomerLoan', 'customer_id'),
            'loans_pending' => array(self::HAS_MANY, 'LoanPending', 'customer_id'),
            'loans_receives' => array(self::HAS_MANY, 'LoanReceived', 'customer_id'),
        );
    }

    public function getList() {
        $criteria = new CDbCriteria();
        $criteria->order = "name ASC";
        $_dataset = Customer::model()->findAll($criteria);
        return $_dataset;
    }

    public function schemaInfo() {
        $exclude = ['id', 'type', 'create_date', 'created_by', 'last_update', 'update_by', '_key'];
        $schemaInfo = Yii::app()->db->schema->getTable($this->tableName());

        foreach ($exclude as $k => $v) {
            unset($schemaInfo->columns[$v]);
        }

        return $schemaInfo;
    }

}
