<?php

class Delivery extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{delivery}}';
    }

    public function rules() {
        return array(
            //array('delivery_date,delivery_no', 'required'),
            array('delivery_number', 'unique'),
            array('delivery_date,delivery_number', 'safe'),
        );
    }

    public function relations() {
        return array(
            'items' => array(self::HAS_MANY, 'DeliveryItem', 'delivery_id'),
            'sumAmount' => array(self::STAT, 'DeliveryItem', 'delivery_id', 'select' => 'SUM(net_total)'),
            'sumQty' => array(self::STAT, 'DeliveryItem', 'delivery_id', 'select' => 'SUM(quantity)'),
        );
    }

    public function sumTotal() {
        $data = Yii::app()->db->createCommand()->select('SUM(net_total) as total')->from('delivery_items')->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function lastNumber() {
        $_maxLcn = Yii::app()->db->createCommand()
                ->select('max(delivery_number)+1 as dn')
                ->from($this->tableName())
                ->queryRow();

        return !empty($_maxLcn['dn']) ? $_maxLcn['dn'] : 1;
    }

}
