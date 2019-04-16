<?php

class Product_inController extends AppController {

    public function beginRequest() {
        if (Yii::app()->request->isAjaxRequest) {
            return true;
        }
        return false;
    }

    public function beforeAction($action) {
        $this->actionAuthorized();
        $this->is_ajax_request();
        return true;
    }

    public function actionIndex() {
        $_limit = Yii::app()->request->getPost('itemCount');
        $_userID = Yii::app()->request->getPost('user');
        $_typeID = Yii::app()->request->getPost('type');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
		$_customer_name = Yii::app()->request->getPost('customer_name');
        $_srno = Yii::app()->request->getPost('srno');
        $_agent = Yii::app()->request->getPost('agent');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");
        $_sortType = Yii::app()->request->getPost('sort_type');
        $_sortBy = Yii::app()->request->getPost('sort_by');
        $_officeCode = Yii::app()->request->getPost('office_code');

        $_model = new ProductIn();
        $criteria = new CDbCriteria();
        if (!empty($_userID)) {
            $criteria->addCondition("created_by=" . $_userID);
            $this->model['userStock'] = AppObject::stockOfUser($_userID);
        } else {
            $this->model['userStock'] = AppObject::stockOfUser(Yii::app()->user->id);
        }
        if (!empty($_customer_name)) {
            $_cmodel = new Customer();
            $_ccriteria = new CDbCriteria();
            $_ccriteria->addCondition("name LIKE '%" . trim($_customer_name) . "%'");
            $_cdata = $_cmodel->findAll($_ccriteria);
            foreach ($_cdata as $_cd) {
                $_cid[] = $_cd->id;
            }
            $criteria->addInCondition("customer_id", $_cid);
        }
        if (!empty($_typeID)) {
            $criteria->addCondition("type=" . $_typeID);
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('create_date', $dateForm, $dateTo);
        }
        if (!empty($_srno)) {
            $criteria->addCondition("sr_no={$_srno}");
        }
        if (!empty($_agent)) {
            $criteria->addCondition("agent_code={$_agent}");
            $this->model['agentTotal'] = AppObject::stockOfAgent($_agent);
        } else {
            $this->model['agentTotal'] = '';
        }
        $this->model['officeStock'] = '';
        if (isset($_officeCode)) {
            $criteria->addCondition("agent_code IS NULL OR agent_code=0");
            $this->model['officeStock'] = AppObject::stockOfice($_agent);
        }
        if (!empty($_sortBy)) {
            $criteria->order = "{$_sortBy} {$_sortType}";
        } else {
            $criteria->order = "sr_no ASC";
        }
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        if (!empty($_userID)) {
            $display_name = User::model()->displayname($_userID);
        } else {
            $display_name = User::model()->displayname(Yii::app()->user->id);
        }

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->model['display_name'] = $display_name;
        $this->renderPartial('index', $this->model);
    }

    public function actionCreate() {
        $_transaction = Yii::app()->db->beginTransaction();
        try {
            $_customer = new Customer();
            $_customer->attributes = $_POST['Customer'];
            $_customer->name = $_POST['Customer']['name'];
            $_customer->father_name = $_POST['Customer']['father_name'];
            $_customer->district = $_POST['Customer']['district'];
            $_customer->thana = isset($_POST['Customer']['thana']) ? $_POST['Customer']['thana'] : '';
            $_customer->village = $_POST['Customer']['village'];
            $_customer->mobile = $_POST['Customer']['mobile'];
            $_customer->create_date = AppHelper::getDbTimestamp();
            $_customer->created_by = Yii::app()->user->id;
            $_customer->_key = AppHelper::getUnqiueKey();
            if (!$_customer->validate()) {
                throw new CException(Yii::t("App", CHtml::errorSummary($_customer)));
            }
            if (!$_customer->save()) {
                throw new CException(Yii::t("App", "Error while saving customer data."));
            }
            $customerID = Yii::app()->db->getLastInsertId();

            $srno = $_POST['ProductIn']['sr_no'];
            $lotno = $_POST['ProductIn']['lot_no'];
            $_date = $_POST['ProductIn']['create_date'];
            $_model = new ProductIn();
            $_model->attributes = $_POST['ProductIn'];
            $_model->customer_id = $customerID;
            $_model->customer_mobile = Customer::model()->findByPk($customerID)->mobile;
            $_model->sr_no = !empty($srno) ? $srno : AppHelper::random_number(4);
            $_model->advance_booking_no = $_POST['ProductIn']['advance_booking_no'];
            $_model->agent_code = !empty($_POST['ProductIn']['agent_code']) ? $_POST['ProductIn']['agent_code'] : 0;
            $_model->type = $_POST['ProductIn']['type'];
            $_model->quantity = $_POST['ProductIn']['quantity'];
            $_model->loan_pack = $_POST['ProductIn']['loan_pack'];
            $_model->lot_no = !empty($lotno) ? $lotno : "{$_model->sr_no}/{$_model->quantity}";
            $_model->create_date = !empty($_date) ? date('Y-m-d', strtotime($_date)) : AppHelper::getDbDate();
            $_model->created_by = Yii::app()->user->id;
            $_model->_key = AppHelper::getUnqiueKey();
            if (!$_model->validate()) {
                throw new CException(Yii::t("App", CHtml::errorSummary($_model)));
            }
            if (!$_model->save()) {
                throw new CException(Yii::t("App", "Error while saving data."));
            }

            $last_id = Yii::app()->db->getLastInsertId();
            $stock = new Stock();
            $stock->customer_id = $customerID;
            $stock->customer_mobile = Customer::model()->findByPk($customerID)->mobile;
            $stock->product_in_id = $last_id;
            $stock->sr_no = $_model->sr_no;
            $stock->agent_code = $_model->agent_code;
            $stock->type = AppConstant::STOK_TYPE_IN;
            $stock->lp_given = $_model->loan_pack;
            $stock->lp_due = $stock->lp_given;
            $stock->qty_in = $_model->quantity;
            $stock->qty_total = $stock->qty_in;
            $stock->create_date = $_model->create_date;
            $stock->created_by = $_model->created_by;
            $stock->_key = AppHelper::getUnqiueKey();
            if (!$stock->save()) {
                throw new CException(Yii::t("App", "Error while saving stock data."));
            }

            $payment = new PaymentIn();
            $payment->pin_id = $last_id;
            $payment->payment_type = AppConstant::TYPE_DUE_PAYMENT;
            $payment->pay_date = AppHelper::getDbDate();
            $payment->carrying_cost = $_POST['PaymentIn']['carrying_cost'];
            $payment->net_amount = $payment->carrying_cost;
            $payment->status = AppConstant::ORDER_PENDING;
            $payment->created = $_model->create_date;
            $payment->created_by = Yii::app()->user->id;
            $payment->_key = AppHelper::getUnqiueKey();
            if (!$payment->save()) {
                throw new CException(Yii::t("App", "Error while saving payment data."));
            }

            $last_payment_id = Yii::app()->db->getLastInsertId();
            $_modelCashAccount = new CashAccount();
            $_modelCashAccount->product_in_payment_id = $last_payment_id;
            $_modelCashAccount->purpose = 'Cash paid to ' . Customer::model()->findByPk($customerID)->name;
            $_modelCashAccount->credit = !empty($payment->net_amount) ? $payment->net_amount : NULL;
            $_modelCashAccount->balance = !empty($payment->net_amount) ? -($_modelCashAccount->credit) : NULL;
            $_modelCashAccount->type = 'W';
            $_modelCashAccount->created = AppHelper::getDbTimestamp();
            $_modelCashAccount->created_by = Yii::app()->user->id;
            $_modelCashAccount->_key = AppHelper::getUnqiueKey();
            if (!$_modelCashAccount->save()) {
                throw new CException(Yii::t("App", "Error while saving transaction."));
            }

            $_transaction->commit();
            $this->resp['success'] = true;
            $this->resp['message'] = "New record save successfull.";
            $this->resp['skey'] = $_model->_key;
        } catch (CException $e) {
            $_transaction->rollback();
            $this->resp['success'] = false;
            $this->resp['message'] = $e->getMessage();
        }

        echo json_encode($this->resp);
        return json_encode($this->resp);
    }

    public function actionUpdate_payment() {
        $paymentID = Yii::app()->request->getPost('paymentID');
        $advance_amount = Yii::app()->request->getPost('advance_amount');
        $carrying_cost = Yii::app()->request->getPost('carrying_cost');
        $labor_cost = Yii::app()->request->getPost('labor_cost');
        $other_cost = Yii::app()->request->getPost('other_cost');
        $total_cost = Yii::app()->request->getPost('total_cost');

        $model = new PaymentIn();
        $payment = $model->findByPk($paymentID);

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            $payment->carrying_cost = !empty($carrying_cost) ? AppHelper::getFloat($carrying_cost) : NULL;
            $payment->labor_cost = !empty($labor_cost) ? AppHelper::getFloat($labor_cost) : NULL;
            $payment->other_cost = !empty($other_cost) ? AppHelper::getFloat($other_cost) : NULL;
            $payment->advance_amount = !empty($advance_amount) ? AppHelper::getFloat($advance_amount) : NULL;
            $payment->net_amount = !empty($total_cost) ? AppHelper::getFloat($total_cost) : NULL;
            $payment->due_amount = $payment->net_amount;
            $payment->modified = AppHelper::getDbTimestamp();
            $payment->modified_by = Yii::app()->user->id;
            if (!$payment->save()) {
                throw new CException(Yii::t("App", "Error while updating payment info."));
            }

            $_transaction->commit();
            $this->resp['success'] = true;
            $this->resp['message'] = "Payment save successfull.";
        } catch (CException $e) {
            $_transaction->rollback();
            $this->resp['success'] = false;
            $this->resp['message'] = $e->getMessage();
        }

        echo json_encode($this->resp);
        return json_encode($this->resp);
    }

    public function actionDeleteall() {
        $_model = new ProductIn();
        $response = array();
        $_data = $_POST['data'];

        if (isset($_data)) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < count($_data); $i++) {
                    $_obj = $_model->findByPk($_data[$i]);

                    if (!empty($_obj->payment)) {
                        if (!$_obj->payment->delete()) {
                            throw new CException(Yii::t('App', "Error while deleting payment record"));
                        }
                    }

                    $deliveries = ProductOut::model()->findall("sr_no=:srno", [':srno' => $_obj->sr_no]);
                    if (!empty($deliveries)) {
                        foreach ($deliveries as $delivery) {
                            if (!$delivery->delete()) {
                                throw new CException(Yii::t("App", "Error while deleting delivery data."));
                            }
                        }
                    }

                    $stocks = Stock::model()->findall("sr_no=:srno", [':srno' => $_obj->sr_no]);
                    if (!empty($stocks)) {
                        foreach ($stocks as $stock) {
                            if (!empty($stock->locations)) {
                                foreach ($stock->locations as $location) {
                                    if (!$location->delete()) {
                                        throw new CException(Yii::t("App", "Error while deleting stock locations."));
                                    }
                                }
                            }

                            if (!$stock->delete()) {
                                throw new CException(Yii::t("App", "Error while deleting stock."));
                            }
                        }
                    }

                    $customerPayments = CustomerPayment::model()->findall("sr_no=:srno", [':srno' => $_obj->sr_no]);
                    if (!empty($customerPayments)) {
                        foreach ($customerPayments as $payments) {
                            if (!$payments->delete()) {
                                throw new CException(Yii::t("App", "Error while deleting customer payments data."));
                            }
                        }
                    }

                    if (!$_obj->delete()) {
                        throw new CException(Yii::t('App', "Error while deleting record"));
                    }
                }

                $_transaction->commit();
                $response['success'] = true;
                $response['message'] = "Records deleted successfully!";
            } catch (CException $e) {
                $_transaction->rollback();
                $response['success'] = false;
                $response['message'] = $e->getMessage();
            }
        } else {
            $response['success'] = false;
            $response['message'] = "No record found to delete!";
        }

        echo json_encode($response);
        return json_encode($response);
    }

    public function actionSearch() {
        $_mobileNo = Yii::app()->request->getParam('mobile');
        $_srNo = Yii::app()->request->getParam('sr');
        $_agent = Yii::app()->request->getParam('agent');

        $_model = new ProductIn();
        $criteria = new CDbCriteria();
        if (!empty($_mobileNo)) {
            $criteria->addCondition("customer_mobile =" . $_mobileNo);
            $this->model['customerMobileNumber'] = $_mobileNo;
        } else {
            $this->model['customerMobileNumber'] = NULL;
        }
        if (!empty($_srNo)) {
            $criteria->addCondition("sr_no ='" . $_srNo . "'");
        }
        if (!empty($_agent)) {
            $criteria->addCondition("agent_code =" . $_agent);
        }
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['dsrNumber'] = time();
        $this->renderPartial('search', $this->model);
    }

    /*
     * protected functions for
     * delete related model data
     */

    protected function delete_stock($_object) {
        if (!empty($_object->stocks)) {
            foreach ($_object->stocks as $stock) {
                if (!$stock->delete()) {
                    throw new CException(Yii::t("App", "Error while deleting stocks."));
                }
            }
        }
    }

}
