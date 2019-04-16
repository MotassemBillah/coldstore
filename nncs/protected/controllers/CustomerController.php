<?php

class CustomerController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('customer_list');
        $this->setHeadTitle("Customers");
        $this->setPageTitle("Customer List");
        $this->setCurrentPage(AppUrl::URL_CUSTOMER);
        $this->addJs('views/customer/list.js');

        $_model = new Customer();
        $criteria = new CDbCriteria();
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $criteria->order = "name ASC";

        $_dataset = $_model->findAll($criteria);
        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('index', $this->model);
    }

    public function actionCreate() {
        $this->checkUserAccess('customer_create');
        $this->setHeadTitle("Customers");
        $this->setPageTitle("Create Customers");
        $this->setCurrentPage(AppUrl::URL_CUSTOMER);

        $_model = new Customer();

        if (isset($_POST['Customer'])) {
            $_model->attributes = $_POST['Customer'];
            $_model->name = $_POST['Customer']['name'];
            $_model->father_name = $_POST['Customer']['father_name'];
            $_model->mobile = $_POST['Customer']['mobile'];
            $_model->district = $_POST['Customer']['district'];
            $_model->thana = $_POST['Customer']['thana'];
            $_model->village = $_POST['Customer']['village'];
            $_model->create_date = AppHelper::getDbTimestamp();
            $_model->created_by = Yii::app()->user->id;
            $_model->_key = AppHelper::getUnqiueKey();

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if (!$_model->validate()) {
                    throw new CException(Yii::t("App", CHtml::errorSummary($_model)));
                }
                if (!$_model->save()) {
                    throw new CException(Yii::t("App", "Error while saving data."));
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "New record save successfull.");
                $this->redirect(array(AppUrl::URL_CUSTOMER));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_model;
        $this->render('create', $this->model);
    }

    public function actionEdit($id) {
        $this->checkUserAccess('customer_edit');
        $this->setHeadTitle("Customers");
        $this->setPageTitle("Edit Customer");
        $this->setCurrentPage(AppUrl::URL_CUSTOMER);

        $_model = new Customer();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

        if (isset($_POST['Customer'])) {
            $_data->attributes = $_POST['Customer'];
            $_data->name = $_POST['Customer']['name'];
            $_data->father_name = $_POST['Customer']['father_name'];
            $_data->mobile = $_POST['Customer']['mobile'];
            $_data->district = $_POST['Customer']['district'];
            $_data->thana = $_POST['Customer']['thana'];
            $_data->village = $_POST['Customer']['village'];
            $_data->last_update = AppHelper::getDbTimestamp();
            $_data->update_by = Yii::app()->user->id;

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if (!$_data->validate()) {
                    throw new CException(Yii::t("App", CHtml::errorSummary($_data)));
                }
                if (!$_data->save()) {
                    throw new CException(Yii::t("App", "Error while saving data."));
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "Record update successfull.");
                $this->redirect(array(AppUrl::URL_CUSTOMER));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_data;
        $this->render('create', $this->model);
    }

    public function actionLedger($id) {
        $this->checkUserAccess('customer_loan');
        $this->setHeadTitle("Customers");
        $this->setCurrentPage(AppUrl::URL_CUSTOMER);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new Customer();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));
        $this->setPageTitle("Ledger Of - " . $_data->name);

        $_modelLoanItem = new LoanItem();
        $criteria = new CDbCriteria();
        $criteria->condition = "customer_id={$_data->id}";
        $criteria->order = "sr_no ASC";
        $count = $_modelLoanItem->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_modelLoanItem->findAll($criteria);

        $this->model['model'] = $_data;
        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('ledger', $this->model);
    }

    public function actionLoan($id) {
        $this->checkUserAccess('customer_loan');
        $this->setHeadTitle("Customers");
        $this->setCurrentPage(AppUrl::URL_CUSTOMER);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new Customer();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));
        $this->setPageTitle("Loan List Of - " . $_data->name);

        $criteria = new CDbCriteria();
        $count = $_model->count($criteria);
        $criteria->condition = "customer_id=:cid";
        $criteria->params = [":cid" => $_data->id];
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = LoanItem::model()->findAll($criteria);

        $this->model['model'] = $_data;
        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('loans', $this->model);
    }

    public function actionAdvance_loan_create($id) {
        $this->checkUserAccess('customer_loan_create');
        $this->setHeadTitle("Loan Payment");
        $this->setCurrentPage(AppUrl::URL_LOAN_PAYMENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        //$this->addJs('views/customer/loan_create.js');

        $_modelAdvLoan = new LoanPaymentAdvance();
        $_model = new Customer();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));
        $this->setPageTitle("New Loan For - " . $_data->name);

        if (isset($_POST['submit_adv_loan'])) {
            //AppHelper::pr($_POST);
            $_date = !empty($_POST['pay_date']) ? date('Y-m-d', strtotime($_POST['pay_date'])) : date('Y-m-d');
            $_customerID = $_POST['customerID'];

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                $_modelAdvLoan->case_no = $_POST['LoanPaymentAdvance']['case_no'];
                $_modelAdvLoan->customer_id = $_customerID;
                $_modelAdvLoan->customer_mobile = Customer::model()->findByPk($_customerID)->mobile;
                $_modelAdvLoan->empty_bag = $_POST['LoanPaymentAdvance']['empty_bag'];
                $_modelAdvLoan->empty_bag_price = $_POST['LoanPaymentAdvance']['empty_bag_price'];
                $_modelAdvLoan->empty_bag_price_total = ($_modelAdvLoan->empty_bag * $_modelAdvLoan->empty_bag_price);
                $_modelAdvLoan->carrying_cost = $_POST['LoanPaymentAdvance']['carrying_cost'];
                $_modelAdvLoan->loan_amount = $_POST['LoanPaymentAdvance']['loan_amount'];
                $_modelAdvLoan->total_loan_amount = ($_modelAdvLoan->empty_bag_price_total + $_modelAdvLoan->carrying_cost + $_modelAdvLoan->loan_amount);
                $_modelAdvLoan->debit = $_modelAdvLoan->total_loan_amount;
                $_modelAdvLoan->balance = $_modelAdvLoan->debit;
                $_modelAdvLoan->note = "Loan paid in advance tk {$_modelAdvLoan->total_loan_amount}";
                $_modelAdvLoan->created = $_date;
                $_modelAdvLoan->created_by = Yii::app()->user->id;
                $_modelAdvLoan->_key = AppHelper::getUnqiueKey();
                if (!$_modelAdvLoan->validate()) {
                    throw new CException(Yii::t("App", CHtml::errorSummary($_modelAdvLoan)));
                }
                if (empty($_POST['LoanPaymentAdvance']['empty_bag']) && empty($_POST['LoanPaymentAdvance']['carrying_cost']) && empty($_POST['LoanPaymentAdvance']['loan_amount'])) {
                    throw new CException(Yii::t("strings", "Empty bag or carrying cost or loan amount is required"));
                }
                if (!empty($_POST['LoanPaymentAdvance']['empty_bag'])) {
                    if (empty($_POST['LoanPaymentAdvance']['empty_bag_price'])) {
                        throw new CException(Yii::t("strings", "Empty bag price is required"));
                    }
                }
                if (!$_modelAdvLoan->save()) {
                    throw new CException(Yii::t("App", "Error while saving data."));
                }

                $last_id = Yii::app()->db->getLastInsertId();
                $_modelCashAccount = new CashAccount();
                $_modelCashAccount->adv_loan_payment_id = $last_id;
                $_modelCashAccount->purpose = "Advance loan paid to " . Customer::model()->findByPk($_customerID)->name;
                $_modelCashAccount->credit = $_modelAdvLoan->total_loan_amount;
                $_modelCashAccount->balance = -($_modelCashAccount->credit);
                $_modelCashAccount->type = 'W';
                $_modelCashAccount->created = AppHelper::getDbTimestamp();
                $_modelCashAccount->created_by = Yii::app()->user->id;
                $_modelCashAccount->_key = AppHelper::getUnqiueKey();
                if (!$_modelCashAccount->save()) {
                    throw new CException(Yii::t("App", "Error while saving transaction."));
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "New record save successfull.");
                $this->redirect($this->createUrl(AppUrl::URL_CUSTOMER_LOAN, ['id' => $_data->_key]));
                $this->refresh();
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_data;
        $this->model['loanForm'] = $_modelAdvLoan;
        $this->model['loanSetting'] = LoanSetting::model()->findByPk(1);
        $this->render('advance_loan_create', $this->model);
    }

    /*
     * Ajax search and other responses
     */

    public function actionSearch() {
        $this->is_ajax_request();
        $_limit = Yii::app()->request->getPost('itemCount');
        $_sort = Yii::app()->request->getPost('itemSort');
        $_sortBy = Yii::app()->request->getPost('sort_by');
        $_sortType = Yii::app()->request->getPost('sort_type');
        $_search = Yii::app()->request->getPost('search');

        $_model = new Customer();
        $criteria = new CDbCriteria();
        if (!empty($_search)) {
            $criteria->condition = "name LIKE :match OR mobile LIKE :match";
            $criteria->params = array(':match' => "%$_search%");
        }
        if (!empty($_sort) && $_sort != "ALL") {
            $criteria->addCondition("name LIKE '$_sort%'");
        }
        if (!empty($_sortBy)) {
            $criteria->order = "{$_sortBy} {$_sortType}";
        } else {
            $criteria->order = "name ASC";
        }
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('_list', $this->model);
    }

    public function actionSearch_ledger() {
        $this->is_ajax_request();
        //$_key = Yii::app()->request->getPost('dataKey');
        $_cid = Yii::app()->request->getPost('customer_id');
        $_limit = Yii::app()->request->getPost('itemCount');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $_srno = Yii::app()->request->getPost('srno');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_modelLoanItem = new LoanItem();
        $criteria = new CDbCriteria();
        $criteria->condition = "customer_id={$_cid}";
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('create_date', $dateForm, $dateTo);
        }
        if (!empty($_srno)) {
            $criteria->addCondition("sr_no={$_srno}");
        }
        $criteria->order = "sr_no ASC";
        $count = $_modelLoanItem->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_modelLoanItem->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('_list_ledger', $this->model);
    }

}
