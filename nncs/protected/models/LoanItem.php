<?php

class LoanItem extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{loan_items}}';
    }

    public function rules() {
        return array(
            array('sr_no', 'unique'),
        );
    }

    public function relations() {
        return array(
            'loan' => array(self::BELONGS_TO, 'Loan', 'loan_id'),
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
        );
    }

    public function duplicateEntry() {
        $sql = "SELECT * FROM loan_items a INNER JOIN loan_items b ON a.sr_no = b.sr_no WHERE a.id <> b.id ORDER BY a.sr_no ASC";
        $_dataset = Yii::app()->db
                ->createCommand($sql)
                ->queryAll();
        return $_dataset;
    }

    public function sumQtyCustomer($_customer = null) {
        if (!is_null($_customer)) {
            $_where = "customer_id={$_customer}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(qty) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumQty($agent = null) {
        if (!is_null($agent)) {
            $_where = "agent_code={$agent}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(qty) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumLoanbagQty() {
        $data = Yii::app()->db->createCommand()->select('SUM(loanbag) as total')->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumLoanbagAmount($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(loanbag_cost_total) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumCarrying($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(carrying_cost) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumTotalAgent($agent = null) {
        if (!is_null($agent)) {
            $_where = "agent_code={$agent}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(net_amount) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

    public function sumTotalCustomer($_customer = null) {
        if (!is_null($_customer)) {
            $_where = "customer_id={$_customer}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(net_amount) as total')->where($_where)->from($this->tableName())->queryRow();
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

    public function avgLoan($agent) {
        $loan = $this->sumTotalAgent($agent);
        $qty = $this->sumQty($agent);
        if ($qty == 0) {
            $qty = 1;
        }
        $_avg = ($loan / $qty);
        return !empty($_avg) ? AppHelper::getFloat($_avg) : 0;
    }

    public function avgLoanCustomer($_customer) {
        $loan = $this->sumTotalCustomer($_customer);
        $qty = $this->sumQtyCustomer($_customer);
        if ($qty == 0) {
            $qty = 1;
        }
        $_avg = ($loan / $qty);
        return !empty($_avg) ? AppHelper::getFloat($_avg) : 0;
    }

    /* Search By Date */

    public function sumTotalBetweenDate($date1, $date2) {
        $_date1 = date("Y-m-d", strtotime($date1));
        $_date2 = date("Y-m-d", strtotime($date2));
        $_where = "create_date BETWEEN CAST('{$_date1}' AS DATE) AND CAST('{$_date2}' AS DATE)";
        $data = Yii::app()->db->createCommand()->select('SUM(net_amount) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? AppHelper::getFloat($data['total']) : 0;
    }

}
