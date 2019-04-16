<?php

class Customer_paymentController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('customer_payment');
        $this->setHeadTitle("Customer Payments");
        $this->setPageTitle("Customer Payments");
        $this->setCurrentPage(AppUrl::URL_CUSTOMER_PAYMENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new CustomerPayment();
        $criteria = new CDbCriteria();
        $criteria->order = "created DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('index', $this->model);
    }

    public function actionCreate() {
        $this->checkUserAccess("payment_create");
        $this->setHeadTitle("Payments");
        $this->setPageTitle("Create Payment");
        $this->setCurrentPage(AppUrl::URL_CUSTOMER_PAYMENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/payment.js');

        $_model = new CustomerPayment('payment');

        if (isset($_POST['CustomerPayment'])) {
            $payDate = $_POST['CustomerPayment']['pay_date'];
            $payment_type = $_POST['CustomerPayment']['payment_type'];
            //$due_pay_id = $_POST['due_payment_id'];
            $due_amount = $_POST['due_amount'];
            $_model->attributes = $_POST['CustomerPayment'];
            $_model->customer_id = $_POST['CustomerPayment']['customer_id'];
            $_model->sr_no = $_POST['CustomerPayment']['sr_no'];
            $_model->pay_date = date("Y-m-d", strtotime($payDate));
            $_model->payment_type = $payment_type;
            $_model->loan_bag = $_POST['CustomerPayment']['loan_bag'];
            $_model->loan_bag_cost = $_POST['CustomerPayment']['loan_bag_cost'];
            $_model->loan_bag_amount = ($_model->loan_bag * $_model->loan_bag_cost);
            $_model->delivered_qty = $_POST['CustomerPayment']['delivered_qty'];
            $_model->delivered_cost = $_POST['CustomerPayment']['delivered_cost'];
            $_model->delivered_cost_amount = ($_model->delivered_qty * $_model->delivered_cost);
//            $_model->carrying_cost = $_POST['CustomerPayment']['carrying_cost'];
//            $_model->labor_cost = $_POST['CustomerPayment']['labor_cost'];
//            $_model->other_cost = $_POST['CustomerPayment']['other_cost'];
            $_model->due_paid = $_POST['CustomerPayment']['due_paid'];
            $_model->net_amount = $_POST['CustomerPayment']['net_amount'];
            $_model->paid_amount = $_model->net_amount;
            if ($_model->paid_amount < $due_amount) {
                $_model->due_amount = $due_amount - $_model->paid_amount;
            }
            $_model->created = AppHelper::getDbTimestamp();
            $_model->created_by = Yii::app()->user->id;
            $_model->_key = AppHelper::getUnqiueKey();

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if (empty($payDate)) {
                    throw new CException(Yii::t("App", "You must select a payment date."));
                }

                /* if ($payment_type == AppConstant::TYPE_DUE_PAYMENT) {
                  if (!empty($due_pay_id)) {
                  $payment = Payment::model()->findByPk($due_pay_id);

                  if ($_model->paid_amount >= $due_amount) {
                  $payment->paid_amount = $payment->paid_amount + $_model->paid_amount;
                  $payment->due_amount = NULL;
                  $payment->status = AppConstant::ORDER_PAID;
                  if (!$payment->save()) {
                  throw new CException(Yii::t("App", "Error while updating payment data."));
                  }
                  } else {
                  $payment->paid_amount = $_model->paid_amount;
                  $payment->due_amount = $_model->due_amount;
                  if (!$payment->save()) {
                  throw new CException(Yii::t("App", "Error while updating payment data."));
                  }
                  }
                  }
                  } */

                if (!$_model->validate()) {
                    throw new CException(Yii::t("App", CHtml::errorSummary($_model)));
                }
                if (!$_model->save()) {
                    throw new CException(Yii::t("App", "Error while saving data."));
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "New record save successfull.");
                $this->redirect($this->createUrl(AppUrl::URL_CUSTOMER_PAYMENT));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_model;
        $this->render('create', $this->model);
    }

    public function actionCreate_delivery() {
        $this->checkUserAccess("payment_create");
        $this->setHeadTitle("Payments");
        $this->setPageTitle("Create Payment");
        $this->setCurrentPage(AppUrl::URL_CUSTOMER_PAYMENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/payment.js');

        $_model = new CustomerPayment('payment');

        if (isset($_POST['CustomerPayment'])) {
            $payDate = $_POST['CustomerPayment']['pay_date'];
            $payment_type = $_POST['CustomerPayment']['payment_type'];
            //$due_pay_id = $_POST['due_payment_id'];
            $due_amount = $_POST['due_amount'];
            $_model->attributes = $_POST['CustomerPayment'];
            $_model->customer_id = $_POST['CustomerPayment']['customer_id'];
            $_model->sr_no = $_POST['CustomerPayment']['sr_no'];
            $_model->pay_date = date("Y-m-d", strtotime($payDate));
            $_model->payment_type = $payment_type;
            $_model->loan_bag = $_POST['CustomerPayment']['loan_bag'];
            $_model->loan_bag_cost = $_POST['CustomerPayment']['loan_bag_cost'];
            $_model->loan_bag_amount = ($_model->loan_bag * $_model->loan_bag_cost);
            $_model->delivered_qty = $_POST['CustomerPayment']['delivered_qty'];
            $_model->delivered_cost = $_POST['CustomerPayment']['delivered_cost'];
            $_model->delivered_cost_amount = ($_model->delivered_qty * $_model->delivered_cost);
//            $_model->carrying_cost = $_POST['CustomerPayment']['carrying_cost'];
//            $_model->labor_cost = $_POST['CustomerPayment']['labor_cost'];
//            $_model->other_cost = $_POST['CustomerPayment']['other_cost'];
            $_model->due_paid = $_POST['CustomerPayment']['due_paid'];
            $_model->net_amount = $_POST['CustomerPayment']['net_amount'];
            $_model->paid_amount = $_model->net_amount;
            if ($_model->paid_amount < $due_amount) {
                $_model->due_amount = $due_amount - $_model->paid_amount;
            }
            $_model->created = AppHelper::getDbTimestamp();
            $_model->created_by = Yii::app()->user->id;
            $_model->_key = AppHelper::getUnqiueKey();

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if (empty($payDate)) {
                    throw new CException(Yii::t("App", "You must select a payment date."));
                }

                /* if ($payment_type == AppConstant::TYPE_DUE_PAYMENT) {
                  if (!empty($due_pay_id)) {
                  $payment = Payment::model()->findByPk($due_pay_id);

                  if ($_model->paid_amount >= $due_amount) {
                  $payment->paid_amount = $payment->paid_amount + $_model->paid_amount;
                  $payment->due_amount = NULL;
                  $payment->status = AppConstant::ORDER_PAID;
                  if (!$payment->save()) {
                  throw new CException(Yii::t("App", "Error while updating payment data."));
                  }
                  } else {
                  $payment->paid_amount = $_model->paid_amount;
                  $payment->due_amount = $_model->due_amount;
                  if (!$payment->save()) {
                  throw new CException(Yii::t("App", "Error while updating payment data."));
                  }
                  }
                  }
                  } */

                if (!$_model->validate()) {
                    throw new CException(Yii::t("App", CHtml::errorSummary($_model)));
                }
                if (!$_model->save()) {
                    throw new CException(Yii::t("App", "Error while saving data."));
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "New record save successfull.");
                $this->redirect($this->createUrl(AppUrl::URL_CUSTOMER_PAYMENT));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_model;
        $this->render('create', $this->model);
    }

    public function actionCreate_due() {
        $this->checkUserAccess("payment_create");
        $this->setHeadTitle("Payments");
        $this->setPageTitle("Create Payment");
        $this->setCurrentPage(AppUrl::URL_CUSTOMER_PAYMENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        //$this->addJs('views/payment.js');

        $_model = new CustomerPayment('payment');

        if (isset($_POST['CustomerPayment'])) {
            $payDate = $_POST['CustomerPayment']['pay_date'];
            $payment_type = $_POST['CustomerPayment']['payment_type'];
            //$due_pay_id = $_POST['due_payment_id'];
            $due_amount = $_POST['due_amount'];
            $_model->attributes = $_POST['CustomerPayment'];
            $_model->customer_id = $_POST['CustomerPayment']['customer_id'];
            $_model->sr_no = $_POST['CustomerPayment']['sr_no'];
            $_model->pay_date = date("Y-m-d", strtotime($payDate));
            $_model->payment_type = $payment_type;
            $_model->loan_bag = $_POST['CustomerPayment']['loan_bag'];
            $_model->loan_bag_cost = $_POST['CustomerPayment']['loan_bag_cost'];
            $_model->loan_bag_amount = ($_model->loan_bag * $_model->loan_bag_cost);
            $_model->delivered_qty = $_POST['CustomerPayment']['delivered_qty'];
            $_model->delivered_cost = $_POST['CustomerPayment']['delivered_cost'];
            $_model->delivered_cost_amount = ($_model->delivered_qty * $_model->delivered_cost);
//            $_model->carrying_cost = $_POST['CustomerPayment']['carrying_cost'];
//            $_model->labor_cost = $_POST['CustomerPayment']['labor_cost'];
//            $_model->other_cost = $_POST['CustomerPayment']['other_cost'];
            $_model->due_paid = $_POST['CustomerPayment']['due_paid'];
            $_model->net_amount = $_POST['CustomerPayment']['net_amount'];
            $_model->paid_amount = $_model->net_amount;
            if ($_model->paid_amount < $due_amount) {
                $_model->due_amount = $due_amount - $_model->paid_amount;
            }
            $_model->created = AppHelper::getDbTimestamp();
            $_model->created_by = Yii::app()->user->id;
            $_model->_key = AppHelper::getUnqiueKey();

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if (empty($payDate)) {
                    throw new CException(Yii::t("App", "You must select a payment date."));
                }

                /* if ($payment_type == AppConstant::TYPE_DUE_PAYMENT) {
                  if (!empty($due_pay_id)) {
                  $payment = Payment::model()->findByPk($due_pay_id);

                  if ($_model->paid_amount >= $due_amount) {
                  $payment->paid_amount = $payment->paid_amount + $_model->paid_amount;
                  $payment->due_amount = NULL;
                  $payment->status = AppConstant::ORDER_PAID;
                  if (!$payment->save()) {
                  throw new CException(Yii::t("App", "Error while updating payment data."));
                  }
                  } else {
                  $payment->paid_amount = $_model->paid_amount;
                  $payment->due_amount = $_model->due_amount;
                  if (!$payment->save()) {
                  throw new CException(Yii::t("App", "Error while updating payment data."));
                  }
                  }
                  }
                  } */

                if (!$_model->validate()) {
                    throw new CException(Yii::t("App", CHtml::errorSummary($_model)));
                }
                if (!$_model->save()) {
                    throw new CException(Yii::t("App", "Error while saving data."));
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "New record save successfull.");
                $this->redirect($this->createUrl(AppUrl::URL_CUSTOMER_PAYMENT));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_model;
        $this->render('form_due', $this->model);
    }

    public function actionEdit($id) {
        //$this->checkUserAccess("payment_create");
        $this->setHeadTitle("Payments");
        $this->setPageTitle("Edit Payment");
        $this->setCurrentPage(AppUrl::URL_CUSTOMER_PAYMENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/payment.js');

        $_model = new CustomerPayment('payment');
        $_data = $_model->find("LOWER(_key)=?", array(strtolower($id)));

        if (isset($_POST['CustomerPayment'])) {
            $payDate = $_POST['CustomerPayment']['pay_date'];
            $payment_type = $_POST['CustomerPayment']['payment_type'];
            //$due_pay_id = $_POST['due_payment_id'];
            //$due_amount = $_POST['due_amount'];
            $_data->attributes = $_POST['CustomerPayment'];
            $_data->customer_id = $_POST['CustomerPayment']['customer_id'];
            $_data->sr_no = $_POST['CustomerPayment']['sr_no'];
            $_data->pay_date = date("Y-m-d", strtotime($payDate));
            $_data->payment_type = $payment_type;
            $_data->loan_bag = $_POST['CustomerPayment']['loan_bag'];
            $_data->loan_bag_cost = $_POST['CustomerPayment']['loan_bag_cost'];
            $_data->loan_bag_amount = ($_data->loan_bag * $_data->loan_bag_cost);
            $_data->delivered_qty = $_POST['CustomerPayment']['delivered_qty'];
            $_data->delivered_cost = $_POST['CustomerPayment']['delivered_cost'];
            $_data->delivered_cost_amount = ($_data->delivered_qty * $_data->delivered_cost);
            $_data->due_paid = $_POST['CustomerPayment']['due_paid'];
            $_data->net_amount = $_POST['CustomerPayment']['net_amount'];
            $_data->paid_amount = $_data->net_amount;
//            if ($_model->paid_amount < $due_amount) {
//                $_model->due_amount = $due_amount - $_model->paid_amount;
//            }
            $_data->modified = AppHelper::getDbTimestamp();
            $_data->modified_by = Yii::app()->user->id;

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if (empty($payDate)) {
                    throw new CException(Yii::t("App", "You must select a payment date."));
                }

                /* if ($payment_type == AppConstant::TYPE_DUE_PAYMENT) {
                  if (!empty($due_pay_id)) {
                  $payment = Payment::model()->findByPk($due_pay_id);

                  if ($_model->paid_amount >= $due_amount) {
                  $payment->paid_amount = $payment->paid_amount + $_model->paid_amount;
                  $payment->due_amount = NULL;
                  $payment->status = AppConstant::ORDER_PAID;
                  if (!$payment->save()) {
                  throw new CException(Yii::t("App", "Error while updating payment data."));
                  }
                  } else {
                  $payment->paid_amount = $_model->paid_amount;
                  $payment->due_amount = $_model->due_amount;
                  if (!$payment->save()) {
                  throw new CException(Yii::t("App", "Error while updating payment data."));
                  }
                  }
                  }
                  } */

                if (!$_data->validate()) {
                    throw new CException(Yii::t("App", CHtml::errorSummary($_data)));
                }
                if (!$_data->save()) {
                    throw new CException(Yii::t("App", "Error while saving data."));
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "New record save successfull.");
                $this->redirect($this->createUrl(AppUrl::URL_CUSTOMER_PAYMENT));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_data;
        $this->render('create', $this->model);
    }

}
