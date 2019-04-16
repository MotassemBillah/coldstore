<?php

class DeliveryItem extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{delivery_items}}';
    }

    public function rules() {
        return array();
    }

    public function relations() {
        return array(
            'reff' => array(self::BELONGS_TO, 'Delivery', 'delivery_id'),
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
        );
    }

    public function sumLoanBag($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(loan_bag) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumLoanBagAmount($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(loan_bag_price_total) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumCarrying($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(carrying) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumQty($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(quantity) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumQtyOfAgent($_code = null) {
        if (!is_null($_code)) {
            $_where = "agent_code={$_code}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(quantity) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumQtyOfType($type = null) {
        if (!is_null($type)) {
            $_where = "type={$type}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(quantity) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumRent() {
        $data = Yii::app()->db->createCommand()->select('SUM(rent_total) as total')->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumFanCharge() {
        $data = Yii::app()->db->createCommand()->select('SUM(fan_charge_total) as total')->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumDeliveryTotal() {
        $data = Yii::app()->db->createCommand()->select('SUM(delivery_total) as total')->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumDiscount() {
        $data = Yii::app()->db->createCommand()->select('SUM(discount) as total')->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumTotal() {
        $data = Yii::app()->db->createCommand()->select('SUM(net_total) as total')->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumTotalAgent($_code = null) {
        if (!is_null($_code)) {
            $_where = "agent_code={$_code}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(net_total) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    /* Date wise sum count functions */

    public function sumLoanBagByDate($date = null) {
        $_date = date('Y-m-d', strtotime($date));
        $_where = "delivery_date='{$_date}'";
        $data = Yii::app()->db->createCommand()->select('SUM(loan_bag) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumLoanBagAmountByDate($date = null) {
        $_date = date('Y-m-d', strtotime($date));
        $_where = "delivery_date='{$_date}'";
        $data = Yii::app()->db->createCommand()->select('SUM(loan_bag_price_total) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumCarryingByDate($date = null) {
        $_date = date('Y-m-d', strtotime($date));
        $_where = "delivery_date='{$_date}'";
        $data = Yii::app()->db->createCommand()->select('SUM(carrying) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumQtyByDate($date = null) {
        $_date = date('Y-m-d', strtotime($date));
        $_where = "delivery_date='{$_date}'";
        $data = Yii::app()->db->createCommand()->select('SUM(quantity) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumRentByDate($date) {
        $_date = date('Y-m-d', strtotime($date));
        $_where = "delivery_date='{$_date}'";
        $data = Yii::app()->db->createCommand()->select('SUM(rent_total) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumFanChargeByDate($date = null) {
        $_date = date('Y-m-d', strtotime($date));
        $_where = "delivery_date='{$_date}'";
        $data = Yii::app()->db->createCommand()->select('SUM(fan_charge_total) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumTotalByDate($date = null) {
        $_date = date('Y-m-d', strtotime($date));
        $_where = "delivery_date='{$_date}'";
        $data = Yii::app()->db->createCommand()->select('SUM(net_total) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

}
