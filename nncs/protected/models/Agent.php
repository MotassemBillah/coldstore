<?php

class Agent extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{agents}}';
    }

    public function rules() {
        return array(
            array('name,father_name,mobile,code', 'required'),
            array('code,mobile', 'unique'),
            array('code', 'numerical', 'integerOnly' => true),
            array('mobile', 'numerical', 'integerOnly' => true),
        );
    }

    public function relations() {
        return array(
            'loans' => array(self::HAS_MANY, 'LoanItem', 'agent_code'),
            'sumQty' => array(self::STAT, 'ProductIn', 'agent_code', 'select' => 'SUM(quantity)'),
        );
    }

    public function getList() {
        $criteria = new CDbCriteria();
        $criteria->order = "name ASC";
        $_dataset = Agent::model()->findAll($criteria);
        return $_dataset;
    }

    public function getName($code) {
        $_data = Agent::model()->find('code=:code', [':code' => $code]);
        return !empty($_data->name) ? $_data->name : '';
    }

    public function schemaInfo() {
        $exclude = ['id', 'address', 'created', 'created_by', 'modified', 'modified_by', '_key'];
        $schemaInfo = Yii::app()->db->schema->getTable($this->tableName());

        foreach ($exclude as $k => $v) {
            unset($schemaInfo->columns[$v]);
        }

        return $schemaInfo;
    }

}
