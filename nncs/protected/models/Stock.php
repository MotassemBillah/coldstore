<?php

class Stock extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{stocks}}';
    }

    public function rules() {
        return array();
    }

    public function relations() {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'product_in' => array(self::BELONGS_TO, 'ProductIn', 'product_in_id'),
            'product_out' => array(self::BELONGS_TO, 'ProductOut', 'product_out_id'),
            'locations' => array(self::HAS_MANY, 'StockLocation', 'stock_id'),
        );
    }

    public function sumIn() {
        $data = Yii::app()->db->createCommand()->select('SUM(qty_in) as total')->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumOut() {
        $data = Yii::app()->db->createCommand()->select('SUM(qty_out) as total')->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumTotal() {
//        $data = Yii::app()->db->createCommand()->select('SUM(qty_total) as total')->from($this->tableName())->queryRow();
//        return !empty($data['total']) ? $data['total'] : 0;
        $totalIn = ProductIn::model()->sumTotal();
        $totalOut = DeliveryItem::model()->sumTotal();
        return ($totalIn - $totalOut);
    }

    public function sumTotalOfAgent() {
        $data = Yii::app()->db->createCommand()->select('SUM(qty_total) as total')->where('agent_code <> 0')->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumTotalOfice() {
        $data = Yii::app()->db->createCommand()->select('SUM(qty_total) as total')->where('agent_code = 0')->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

}
