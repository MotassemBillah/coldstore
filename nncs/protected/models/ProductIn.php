<?php

class ProductIn extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{product_in}}';
    }

    public function rules() {
        return array(
            array('sr_no,quantity,lot_no', 'required'),
            array('sr_no', 'unique'),
            array('agent_code', 'checkAgent'),
            array('customer_id,sr_no,advance_booking_no,quantity,loan_pack,lot_no', 'safe'),
        );
    }

    public function relations() {
        return array(
            'agent' => array(self::BELONGS_TO, 'Agent', 'agent_code'),
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'deliveries' => array(self::HAS_MANY, 'DeliveryItem', 'sr_no', 'on' => "(delivery_items.sr_no='sr_no')"),
            'payment' => array(self::HAS_ONE, 'CashAccount', 'product_in_payment_id'),
        );
    }

    public function checkAgent() {
        if (!empty($this->agent_code)) {
            $_obj = Agent::model()->exists('code=:code', array(':code' => $this->agent_code));
            if (!$_obj) {
                $this->addError('agent_code', Yii::t('strings', 'Sorry, That agent code number is invalid or dose not exist.'));
            }
        }
    }

    public function getObj($sr) {
        return ProductIn::model()->find("sr_no=:sr", [":sr" => $sr]);
    }

    public function srDue($sr) {
        $criteria = new CDbCriteria();
        $criteria->condition = "sr_no=:sr";
        $criteria->params = [":sr" => $sr];
        $_data = ProductIn::model()->find($criteria);
        return $_data->payment;
    }

    public function totalLoanPackGiven() {
        $data = Yii::app()->db->createCommand()->select('SUM(loan_pack) as bag')->from($this->tableName())->queryRow();
        return !empty($data['bag']) ? $data['bag'] : 0;
    }

    public function day_total_in($startDay, $endDay) {
        $data = Yii::app()->db->createCommand()
                ->select("SUM(quantity) as qty")
                ->from($this->tableName())
                ->where("create_date BETWEEN '$startDay' AND '$endDay'")
                ->queryRow();
        return !empty($data['qty']) ? $data['qty'] : 0;
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

    public function sumEmptyBag($_code = null) {
        if (!is_null($_code)) {
            $_where = "agent_code={$_code}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(loan_pack) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumTotal() {
        $data = Yii::app()->db->createCommand()->select('SUM(quantity) as total')->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function agentStock($_code = null) {
        if (!is_null($_code)) {
            $_where = "agent_code={$_code}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(quantity) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function customerStock($_customer = null) {
        if (!is_null($_customer)) {
            $_where = "customer_id={$_customer}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(quantity) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function customerEmptyBag($_customer = null) {
        if (!is_null($_customer)) {
            $_where = "customer_id={$_customer}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(loan_pack) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumTotalOfAgent() {
        $data = Yii::app()->db->createCommand()->select('SUM(quantity) as total')->where('agent_code <> 0')->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumTotalOfice() {
        $data = Yii::app()->db->createCommand()->select('SUM(quantity) as total')->where('agent_code = 0')->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumCarrying($srno = null) {
        if (!is_null($srno)) {
            $_where = "sr_no={$srno}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(carrying_cost) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public function sumCarryingAgent($_code = null) {
        if (!is_null($_code)) {
            $_where = "agent_code={$_code}";
        } else {
            $_where = "";
        }
        $data = Yii::app()->db->createCommand()->select('SUM(carrying_cost) as total')->where($_where)->from($this->tableName())->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

}
