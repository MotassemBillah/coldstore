<?php

class LoanController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('loan_payment_list');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Loan Payment List");
        $this->setCurrentPage(AppUrl::URL_LOAN);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new LoanItem();
        $criteria = new CDbCriteria();
        $criteria->order = "sr_no ASC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('index', $this->model);
    }

    public function actionPayment() {
        $this->checkUserAccess('loan_payment_list');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Loan Payment List");
        $this->setCurrentPage(AppUrl::URL_LOAN_PAYMENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/loan/payment_list.js');

        $_model = new Loan();
        $criteria = new CDbCriteria();
        if (Yii::app()->user->role != AppConstant::ROLE_SUPERADMIN) {
            $criteria->condition = "created_by = " . Yii::app()->user->id;
        }
        $criteria->order = "case_no DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->model['display_name'] = User::model()->displayname(Yii::app()->user->id);
        $this->render('payment_list', $this->model);
    }

    public function actionPayment_create() {
        $this->checkUserAccess('loan_payment_create');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Create Loan");
        $this->setCurrentPage(AppUrl::URL_LOAN_PAYMENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/loan/payment_create.js');

        $_model = new LoanPayment();

        $this->model['model'] = $_model;
        $this->model['loanSetting'] = LoanSetting::model()->findByPk(1);
        $this->model['loanCaseNumber'] = Loan::model()->getLastCaseNo();
        $this->render('payment_create_new', $this->model);
    }

    public function actionPayment_edit($id) {
        $this->checkUserAccess('loan_payment_edit');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Edit Loan");
        $this->setCurrentPage(AppUrl::URL_LOAN_PAYMENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new Loan();
        $data = $_model->find("LOWER(_key)=?", array(strtolower($id)));
        $loanSetting = LoanSetting::model()->findByPk(1);

        if (isset($_POST['updateLoan'])) {
            $_date = $_POST['pay_date'];
            $data->case_no = $_POST['loan_case_no'];
            $data->taken_person = $_POST['loan_taken_by'];
            $data->created = date("Y-m-d", strtotime($_date));
            $data->modified = AppHelper::getDbTimestamp();
            $data->modified_by = Yii::app()->user->id;

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if (!$data->validate()) {
                    throw new CException(Yii::t("App", CHtml::errorSummary($data)));
                }
                if (!$data->save()) {
                    throw new CException(Yii::t("App", "Error while saving data."));
                }

                $_data_key = $_POST['data_key'];
                if (!empty($_data_key)) {
                    foreach ($_data_key as $_key => $_val) {
                        if (!empty($_data_key[$_key])) {
                            $_srInfo = ProductIn::model()->find('sr_no=:sr', [':sr' => $_POST['sr_no'][$_val]]);
                            $item = LoanItem::model()->findByPk($_val);
                            $item->customer_id = $_srInfo->customer_id;
                            $item->sr_no = $_POST['sr_no'][$_val];
                            $item->type = !empty($_srInfo->type) ? $_srInfo->type : NULL;
                            $item->agent_code = !empty($_srInfo->agent_code) ? $_srInfo->agent_code : 0;
                            $item->qty = $_POST['quantity'][$_val];
                            $item->qty_cost = $_POST['rent'][$_val];
                            $item->qty_cost_total = $_POST['loan_amount'][$_val];
                            $item->net_amount = $item->qty_cost_total;
                            $item->interest_rate = $loanSetting->interest_rate;
                            $item->interest_amount = ($item->interest_rate * $item->net_amount) / 100;
                            $item->total_amount = ($item->net_amount + $item->interest_amount);
                            $item->per_day_interest = AppHelper::getFloat(($item->interest_rate * $item->net_amount) / ($loanSetting->period * 100));
                            $item->min_day = $loanSetting->min_day;
                            $item->min_payable = AppHelper::getFloat($item->per_day_interest * $item->min_day);
                            $item->loan_period = $loanSetting->period;
                            $item->status = AppConstant::ORDER_PENDING;
                            $item->create_date = date("Y-m-d", strtotime($_POST['date'][$_val]));
                            $item->modified = $data->modified;
                            $item->modified_by = Yii::app()->user->id;
                            if (!$item->validate()) {
                                throw new CException(Yii::t("App", CHtml::errorSummary($item)));
                            }
                            if (!$item->save()) {
                                throw new CException(Yii::t("App", "Error while saving loan item."));
                            }

                            $_modelCashAccount = CashAccount::model()->find('loan_payment_id=:lpid', [':lpid' => $item->id]);
                            if (!empty($_modelCashAccount)) {
                                $_modelCashAccount->ledger_head_id = AppConstant::LOAN_HEAD_ID;
                                $_modelCashAccount->pay_date = $item->create_date;
                                $_modelCashAccount->purpose = "Loan paid to " . Customer::model()->findByPk($item->customer_id)->name . " @{$item->qty_cost} tk";
                                $_modelCashAccount->credit = $item->net_amount;
                                $_modelCashAccount->balance = -($_modelCashAccount->credit);
                                $_modelCashAccount->type = 'W';
                                $_modelCashAccount->created = $item->create_date;
                                $_modelCashAccount->modified = $data->modified;
                                $_modelCashAccount->modified_by = Yii::app()->user->id;
                                if (!$_modelCashAccount->save()) {
                                    throw new CException(Yii::t("App", "Error while saving transaction."));
                                }
                            }
                        }
                    }
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "Record update successfull.");
                $this->redirect(array(AppUrl::URL_LOAN_PAYMENT));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $data;
        $this->model['loanSetting'] = $loanSetting;
        $this->render('payment_edit', $this->model);
    }

    public function actionPayment_view($id) {
        $this->checkUserAccess('loan_payment_edit');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Loan Payment View");
        $this->setCurrentPage(AppUrl::URL_LOAN_PAYMENT);

        $_model = new Loan();
        $data = $_model->find("LOWER(_key)=?", array(strtolower($id)));

        if (empty($data)) {
            Yii::app()->user->setFlash("warning", "You are trying to access an invalid url.");
            $this->redirect(Yii::app()->createUrl(AppUrl::URL_LOAN_PAYMENT));
        }

        $this->model['model'] = $data;
        $this->model['loanSetting'] = LoanSetting::model()->findByPk(1);
        $this->render('payment_view', $this->model);
    }

    public function actionSingle_view($id) {
        $this->checkUserAccess('loan_payment_edit');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Loan Payment View");
        $this->setCurrentPage(AppUrl::URL_LOAN_PAYMENT);

        $_model = new LoanItem();
        $data = $_model->findByPk($id);

        if (empty($data)) {
            Yii::app()->user->setFlash("warning", "You are trying to access an invalid url.");
            $this->redirect(Yii::app()->createUrl(AppUrl::URL_LOAN_PAYMENT));
        }

        $this->model['model'] = $data;
        $this->model['product'] = ProductIn::model()->find('sr_no=:sr', [':sr' => $data->sr_no]);
        $this->model['loanSetting'] = LoanSetting::model()->findByPk(1);
        $this->render('single_view', $this->model);
    }

    public function actionSingle_edit($id) {
        $this->checkUserAccess('loan_payment_edit');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Loan Payment Edit");
        $this->setCurrentPage(AppUrl::URL_LOAN_PAYMENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new LoanItem();
        $item = $_model->findByPk($id);
        $loanSetting = LoanSetting ::model()->findByPk(1);

        if (empty($item)) {
            Yii::app()->user->setFlash("warning", "You are trying to access an invalid url.");
            $this->redirect(Yii::app()->createUrl(AppUrl::URL_LOAN_PAYMENT));
        }

        if (isset($_POST['updateLoan'])) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if (empty($_POST['quantity'])) {
                    throw new CException(Yii::t("App", "Quantity required."));
                }

                $item->customer_id = $_POST['customer_id'];
                $item->sr_no = $_POST['sr_no'];
                $item->type = $_POST['type'];
                $item->agent_code = $_POST['agent'];
                $item->qty = $_POST['quantity'];
                $item->qty_cost = $_POST['rent'];
                $item->qty_cost_total = $_POST['loan_amount'];
                $item->loanbag = $_POST['loan_bag'];
                $item->loanbag_cost = $_POST['loan_bag_price'];
                $item->loanbag_cost_total = ($item->loanbag * $item->loanbag_cost);
                $item->carrying_cost = $_POST ['carrying_cost'];
                $item->net_amount = $_POST['loan_amount'];
                $item->interest_rate = $loanSetting->interest_rate;
                $item->interest_amount = ($item->interest_rate * $item->net_amount) / 100;
                $item->total_amount = ($item->net_amount + $item->interest_amount );
                $item->per_day_interest = AppHelper::getFloat(($item->interest_rate * $item->net_amount) / ($loanSetting->period * 100));
                $item->min_day = $loanSetting->min_day;
                $item->min_payable = AppHelper::getFloat($item->per_day_interest * $item->min_day);
                $item->loan_period = $loanSetting->period;
                $item->status = AppConstant::ORDER_PENDING;
                $item->create_date = date('Y-m-d', strtotime($_POST['pay_date']));
                $item->created = date('Y-m-d H:i:s', strtotime($_POST['pay_date']));
                $item->modified = AppHelper::getDbTimestamp();
                $item->modified_by = Yii::app()->user->id;
                if (!$item->validate()) {
                    throw new CException(Yii::t("App", CHtml::errorSummary($item)));
                }
                if (!$item->save()) {
                    throw new CException(Yii::t("App", "Error while saving loan item."));
                }

                $_modelCashAccount = CashAccount::model()->find('loan_payment_id=:lpid', [':lpid' => $item->id]);
                if (!empty($_modelCashAccount)) {
                    $_modelCashAccount->ledger_head_id = AppConstant::LOAN_HEAD_ID;
                    $_modelCashAccount->purpose = "Loan paid to " . Customer::model()->findByPk($item->customer_id)->name . " @{$item->qty_cost} tk";
                    $_modelCashAccount->credit = $item->net_amount;
                    $_modelCashAccount->balance = -($_modelCashAccount->credit);
                    $_modelCashAccount->type = 'W';
                    $_modelCashAccount->modified = $item->modified;
                    $_modelCashAccount->modified_by = $item->modified_by;
                    if (!$_modelCashAccount->save()) {
                        throw new CException(Yii::t("App", "Error while saving transaction."));
                    }
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "Record update successfull.");
                $this->redirect(array(AppUrl::URL_LOAN));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $item;
        $this->model['loanSetting'] = $loanSetting;
        $this->render('single_edit', $this->model);
    }

    public function actionPayment_advance_list() {
        $this->checkUserAccess('loan_payment_create');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Create Loan");
        $this->setCurrentPage(AppUrl::URL_LOAN_PAYMENT_ADVANCE_LIST);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/loan/payment_advance_list.js');

        $_model = new Loan();
        $criteria = new CDbCriteria();
        $criteria->condition = "type = '" . AppConstant::TYPE_ADVANCE . "'";
        $criteria->order = "created DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('advance_list', $this->model);
    }

    public function actionPayment_advance_create() {
        $this->checkUserAccess('loan_payment_create');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Create Loan");
        $this->setCurrentPage(AppUrl::URL_LOAN_PAYMENT_ADVANCE_LIST);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        //$this->addJs('views/loan/payment_create.js');

        $_model = new Loan();
        $_loanSetting = LoanSetting::model()->findByPk(1);

        if (isset($_POST['submit_adv_loan'])) {
            $_customer_type = $_POST['customer_type'];
            $_date = !empty($_POST['pay_date']) ? date('Y-m-d', strtotime($_POST['pay_date'])) : date('Y-m-d');

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if ($_customer_type == "customer") {
                    if (empty($_POST['customer'])) {
                        throw new CException(Yii::t("strings", "You must select a customer."));
                    }
                } else {
                    if (empty($_POST['agent'])) {
                        throw new CException(Yii::t("strings", "You must enter a agent code."));
                    }
                }

                $_model->case_no = $_POST['customer'];
                $_model->type = AppConstant::TYPE_ADVANCE;
                $_model->created = $_date;
                $_model->created_by = Yii::app()->user->id;
                $_model->_key = AppHelper::getUnqiueKey();
                if (!$_model->validate()) {
                    throw new CException(Yii::t("App", CHtml::errorSummary($_model)));
                }
                if (!$_model->save()) {
                    throw new CException(Yii::t("App", "Error while saving data."));
                }

                $loan_id = Yii::app()->db->getLastInsertId();
                $_loanItem = new LoanItem();
                $_loanItem->loan_id = $loan_id;
                $_loanItem->type = AppConstant::TYPE_ADVANCE;
                $_loanItem->agent_code = isset($_POST['agent']) ? $_POST['agent'] : NULL;
                $_loanItem->customer_id = isset($_POST['customer']) ? $_POST['customer'] : NULL;

                if ($_customer_type == "customer") {
                    $_party = Customer::model()->findByPk($_POST['customer'])->name;
                } else {
                    $_party = Agent::model()->find('code=:cd', [':cd' => $_POST['agent']])->name;
                }

                if (empty($_POST['loan_bag']) && empty($_POST['loan_amount'])) {
                    throw new CException(Yii::t("strings", "Loan bag or amount is required"));
                }

                if (!empty($_POST['loan_bag'])) {
                    if (empty($_POST['loan_bag_price'])) {
                        throw new CException(Yii::t("strings", "Loan bag price is required"));
                    }
                    $_loanItem->loanbag = $_POST['loan_bag'];
                    $_loanItem->loanbag_cost = $_POST['loan_bag_price'];
                    $_loanItem->loanbag_cost_total = ($_loanItem->loanbag * $_loanItem->loanbag_cost);
                    $_loanItem->net_amount = $_loanItem->loanbag_cost_total;
                    $_loanItem->total_amount = $_loanItem->net_amount;
                }
                if (!empty($_POST['loan_amount'])) {
                    $_loanItem->net_amount = $_POST['loan_amount'];
                    $_loanItem->total_amount = $_loanItem->net_amount;
                }
                if (!empty($_POST['loan_bag']) && !empty($_POST['loan_amount'])) {
                    $_loanItem->total_amount = $_loanItem->net_amount;
                }
                $_loanItem->status = AppConstant::ORDER_PENDING;
                $_loanItem->created = $_date;
                $_loanItem->created_by = Yii::app()->user->id;
                $_loanItem->_key = AppHelper::getUnqiueKey();
                if (!$_loanItem->save()) {
                    throw new CException(Yii::t("App", "Error while saving data."));
                }

                $last_item_id = Yii::app()->db->getLastInsertId();
                $_modelCashAccount = new CashAccount();
                $_modelCashAccount->loan_payment_id = $last_item_id;
                $_modelCashAccount->purpose = "Advance loan paid to " . $_party;
                $_modelCashAccount->credit = $_loanItem->total_amount;
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
                $this->redirect(array(AppUrl::URL_LOAN_PAYMENT_ADVANCE_LIST));
                $this->refresh();
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_model;
        $this->model['loanSetting'] = $_loanSetting;
        $this->render('payment_advance', $this->model);
    }

    public function actionReceive_list() {
        $this->checkUserAccess('loan_payment_list');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Loan Receive List");
        $this->setCurrentPage(AppUrl::URL_LOAN);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new LoanReceiveItem();
        $criteria = new CDbCriteria();
        $criteria->order = "sr_no ASC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('receive_item_list', $this->model);
    }

    public function actionReceive() {
        $this->checkUserAccess('loan_receive_list');
        $this->setHeadTitle("Loan Receive");
        $this->setPageTitle("Loan Received List");
        $this->setCurrentPage(AppUrl::URL_LOAN_RECEIVE);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/loan/receive_list.js');

        $_model = new LoanReceive();
        $criteria = new CDbCriteria();
        $criteria->order = "receive_date DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('received', $this->model);
    }

    public function actionReceive_create() {
        $this->checkUserAccess('loan_receive_list');
        $this->setHeadTitle("Loan Receive");
        $this->setPageTitle("Loan Receive");
        $this->setCurrentPage(AppUrl::URL_LOAN_RECEIVE);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        //Yii::app()->user->setFlash("warning", "Currently Unavailable.");
        //$this->redirect(Yii::app()->createUrl(AppUrl::URL_LOAN_RECEIVE));

        $this->render('receive_form', $this->model);
    }

    public function actionReceive_view($id) {
        //$this->checkUserAccess('loan_payment_edit');
        $this->setHeadTitle("Loan Receive");
        $this->setPageTitle("Loan Receive View");
        $this->setCurrentPage(AppUrl::URL_LOAN_RECEIVE);

        $_model = new LoanReceive();
        $data = $_model->find("LOWER(_key)=?", array(strtolower($id)));

        if (empty($data)) {
            Yii::app()->user->setFlash("warning", "You are trying to access an invalid url.");
            $this->redirect(Yii::app()->createUrl(AppUrl::URL_LOAN_RECEIVE));
        }

        $this->model['model'] = $data;
        $this->model['loanSetting'] = LoanSetting::model()->findByPk(1);
        $this->render('receive_view', $this->model);
    }

    public function actionReceive_single_view($id) {
        //$this->checkUserAccess('loan_payment_edit');
        $this->setHeadTitle("Loan Receive");
        $this->setPageTitle("Loan Receive View");
        $this->setCurrentPage(AppUrl::URL_LOAN_RECEIVE);

        $_model = new LoanReceiveItem();
        $data = $_model->findByPk($id);

        if (empty($data)) {
            Yii::app()->user->setFlash("warning", "You are trying to access an invalid url.");
            $this->redirect(Yii::app()->createUrl(AppUrl::URL_LOAN_RECEIVE));
        }

        $this->model['model'] = $data;
        $this->model['product'] = ProductIn ::model()->find('sr_no=:sr', [':sr' => $data->sr_no]);
        $this->model['loanSetting'] = LoanSetting ::model()->findByPk(1);
        $this->render('receive_single_view', $this->model);
    }

    public function actionPending() {
        $this->checkUserAccess('loan_payment_list');
        $this->setHeadTitle("Loan Pending");
        $this->setPageTitle("Loan Pending List");
        $this->setCurrentPage(AppUrl::URL_LOAN_PENDING);
        $this->addJs('views/loan/pending_list.js');

        $_model = new LoanPending();
        $criteria = new CDbCriteria();
        $criteria->condition = "status='" . AppConstant::ORDER_PENDING . "'";
        $criteria->order = "id ASC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('pending_list', $this->model);
    }

    public function actionSetting() {
        $this->checkUserAccess('loan_setting');
        $this->setHeadTitle("Loan Setting");
        $this->setPageTitle("Loan Setting");
        $this->setCurrentPage(AppUrl::URL_LOAN_SETTING);

        $model = new LoanSetting();
        $data = $model->findByPk(1);

        $this->model['model'] = $data;
        $this->render('setting', $this->model);
    }

    public function actionUpdate_list() {
        $itemDataset = LoanItem::model()->findAll();
        foreach ($itemDataset as $itemData) {
            $srInfo = ProductIn::model()->find('sr_no=:sr', [':sr' => $itemData->sr_no]);
            $itemData->type = $srInfo->type;
            $itemData->customer_id = $srInfo->customer_id;
            $itemData->create_date = date("Y-m-d", strtotime($itemData->created));
            $itemData->save();
        }
        $this->redirect(array(AppUrl::URL_LOAN));
    }

    public function actionDuplicate() {
        $this->setHeadTitle("Loan");
        $this->setPageTitle("Duplicate Loan");

        $_dataset = LoanItem::model()->duplicateEntry();
        //pr($_dataset);

        $this->model['dataset'] = $_dataset;
        $this->render('duplicate', $this->model);
    }

    /*
     * Ajax search and other responses
     */

    public function actionSearch_list() {
        $this->is_ajax_request();
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

        $_model = new LoanItem();
        $criteria = new CDbCriteria();
        if (!empty($_userID)) {
            $criteria->addCondition("created_by={$_userID}");
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
            $criteria->addCondition("type={$_typeID}");
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('create_date', $dateForm, $dateTo);
        }
        if (!empty($_srno)) {
            $criteria->addCondition("sr_no={$_srno}");
        }
        if (!empty($_agent)) {
            $criteria->addCondition("agent_code={$_agent}");
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

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('_list', $this->model);
    }

    /* Upadate functions */

    public function actionUpdate_all() {
        $_model = new LoanReceiveItem();
        $criteria = new CDbCriteria();
        $criteria->order = "id ASC";
        $_dataset = $_model->findAll($criteria);
        echo count($_dataset) . "<br>";

        $_counter = 0;
        foreach ($_dataset as $_data) {
            $_counter++;
            $_cashPayment = CashAccount::model()->findAll('loan_receive_id=:lrid', [':lrid' => $_data->id]);
            if (!empty($_cashPayment)) {
                foreach ($_cashPayment as $_cash) {
                    if (!$_cash->delete()) {
                        throw new CException(Yii::t('App', "Error while deleting cash record."));
                    }
                }
            }

            $_loan = !empty($_data->loan_amount) ? $_data->loan_amount : '';
            $_interest = !empty($_data->interest_amount) ? $_data->interest_amount : '';

            $_headArr = [
                ['head_id' => AppConstant::HEAD_LOAN, 'amount' => $_loan, 'purpose' => 'Loan receive'],
                ['head_id' => AppConstant::HEAD_INTEREST, 'amount' => $_interest, 'purpose' => 'Interest receive'],
            ];

            foreach ($_headArr as $_key => $_val) {
                $_modelCashAccount = new CashAccount();
                $_modelCashAccount->loan_receive_id = $_data->id;
                $_modelCashAccount->type = 'D';
                $_modelCashAccount->pay_date = $_data->receive_date;
                $_modelCashAccount->ledger_head_id = $_headArr[$_key]['head_id'];
                $_modelCashAccount->purpose = "{$_headArr[$_key]['purpose']} for SR {$_data->sr_no}";
                $_modelCashAccount->by_whom = User::model()->displayname($_data->created_by);
                $_modelCashAccount->debit = $_headArr[$_key]['amount'];
                $_modelCashAccount->balance = $_modelCashAccount->debit;
                $_modelCashAccount->created = $_data->receive_date;
                $_modelCashAccount->created_by = $_data->created_by;
                $_modelCashAccount->_key = $_data->_key . $_key;
                if ($_modelCashAccount->save()) {
                    echo "{$_counter} => Saved <br>";
                } else {
                    echo "{$_counter} => Failed <br>";
                }
            }
        }
        exit;
    }

    public function actionUpdate_view() {
        $_model = new LoanReceiveItem();
        $criteria = new CDbCriteria();
        $criteria->order = "id ASC";
        $_dataset = $_model->findAll($criteria);
        echo count($_dataset);

        $_counter = 0;
        foreach ($_dataset as $_data) {
            $_counter++;
            $_loan = !empty($_data->loan_amount) ? $_data->loan_amount : '';
            $_interest = !empty($_data->interest_amount) ? $_data->interest_amount : '';

            $_headArr = [
                ['head_id' => AppConstant::HEAD_LOAN, 'amount' => $_loan, 'purpose' => 'Loan receive'],
                ['head_id' => AppConstant::HEAD_INTEREST, 'amount' => $_interest, 'purpose' => 'Interest receive'],
            ];

            $_str = '<table border="1" style="margin-bottom:15px">';
            foreach ($_headArr as $_key => $_val) {
                $_str.= '<tr>';
                $_str.="<td style='padding:5px'>SL No = {$_counter}</td>";
                $_str.="<td style='padding:5px'>ID = {$_data->id}</td>";
                $_str.="<td style='padding:5px'>Head ID = {$_headArr[$_key]['head_id']}</td>";
                $_str.="<td style='padding:5px'>Amount Debited = {$_headArr[$_key]['amount']} Tk</td>";
                $_str.="<td style='padding:5px'>Purpose = {$_headArr[$_key]['purpose']}</td>";
                $_str.="</tr>";
            }
            $_str.="<tr>";
            $_str.="<td colspan='3' style='padding:5px'>Total Amount Collection</td>";
            $_str.="<td style='padding:5px;text-align:right;'>{$_data->total_amount} Tk</td>";
            $_str.="<td></td>";
            $_str.="</tr>";
            $_str.="</table>";
            echo $_str;
        }
        exit;
    }

}
