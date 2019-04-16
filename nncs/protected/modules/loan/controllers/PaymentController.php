<?php

class PaymentController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
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
        $this->render('index', $this->model);
    }

    public function actionCreate() {
        $this->checkUserAccess('loan_payment_create');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Create Loan");
        $this->setCurrentPage(AppUrl::URL_LOAN_PAYMENT_CREATE);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/loan/payment_create.js');

        $_model = new LoanPayment();

        $this->model['model'] = $_model;
        $this->model['loanSetting'] = LoanSetting::model()->findByPk(1);
        $this->model['loanCaseNumber'] = Loan::model()->getLastCaseNo();
        $this->render('create_new', $this->model);
    }

    public function actionEdit($id) {
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
            $data->advance = $_POST['advance'];
            $data->cash = $_POST['cash'];
            $data->loan_bag_price = $_POST['loan_bag_price_single'];
            $data->loan_bag_qty = $_POST['loan_bag_qty_total'];
            $data->loan_bag_price_total = $_POST['loan_bag_price_total'];
            $data->carrying_total = $_POST['carrying_total'];
            $data->qty_total = $_POST['qty_total'];
            $data->qty_price = $_POST['cost_per_qty'];
            $data->total_loan_amount = $_POST['total_loan_given'];
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
                            $item = LoanItem::model()->findByPk($_val);
                            //$item->customer_id = $_POST['customer_id'][$_val];
                            $item->customer_id = ProductIn::model()->find('sr_no=:sr', [':sr' => $_POST['sr_no'][$_val]])->customer_id;
                            $item->sr_no = $_POST['sr_no'][$_val];
                            $item->type = $_POST['type'][$_val];
                            $item->agent_code = $_POST['agent'][$_val];
                            $item->qty = $_POST['quantity'][$_val];
                            $item->qty_cost = $_POST['rent'][$_val];
                            $item->qty_cost_total = $_POST['loan_amount'][$_val];
                            $item->loanbag = $_POST['loan_bag'][$_val];
                            $item->loanbag_cost = $_POST['loan_bag_price'][$_val];
                            $item->loanbag_cost_total = ($item->loanbag * $item->loanbag_cost);
                            $item->carrying_cost = $_POST['carrying_cost'][$_val];
                            $item->net_amount = $_POST['loan_amount'][$_val];
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
                                throw new CException(Yii:: t("App", CHtml::errorSummary($item)));
                            }
                            if (!$item->save()) {
                                throw new CException(Yii::t("App", "Error while saving loan item."));
                            }
                            $sumQty[] = $item->qty;

                            $_modelCashAccount = CashAccount::model()->find('loan_payment_id=:lpid', [':lpid' => $item->id]);
                            if (!empty($_modelCashAccount)) {
                                $_modelCashAccount->ledger_head_id = AppConstant::LOAN_HEAD_ID;
                                $_modelCashAccount->purpose = "Loan paid to " . Customer::model()->findByPk($item->customer_id)->name . " @{$item->qty_cost} tk";
                                $_modelCashAccount->credit = $item->net_amount;
                                $_modelCashAccount->balance = -($_modelCashAccount->credit);
                                $_modelCashAccount->type = 'W';
                                $_modelCashAccount->modified = $data->modified;
                                $_modelCashAccount->modified_by = Yii::app()->user->id;
                                if (!$_modelCashAccount->save()) {
                                    throw new CException(Yii::t("App", "Error while saving transaction."));
                                }
                            }
                        }
                    }
                }

                $item->loan->qty_total = array_sum($sumQty);
                $item->loan->save();

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "Record update successfull.");
                $this->redirect(array(AppUrl::URL_LOAN_PAYMENT));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $data;
        $this->model['loan_setting'] = $loanSetting;
        $this->render('edit', $this->model);
    }

    public function actionView($id) {
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
        $this->render('view', $this->model);
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
                    throw new CException(Yii:: t("App", "Quantity required."));
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
                $item->interest_amount = ($item->interest_rate * $item->net_amount ) / 100;
                $item->total_amount = ($item->net_amount + $item->interest_amount );
                $item->per_day_interest = AppHelper::getFloat(($item->interest_rate * $item->net_amount ) / ($loanSetting->period * 100));
                $item->min_day = $loanSetting->min_day;
                $item->min_payable = AppHelper::getFloat($item->per_day_interest * $item->min_day);
                $item->loan_period = $loanSetting->period;
                $item->status = AppConstant::ORDER_PENDING;
                $item->create_date = date('Y-m-d', strtotime($_POST['pay_date']));
                $item->created = date('Y-m-d H:i:s', strtotime($_POST['pay_date']));
                $item->modified = AppHelper::getDbTimestamp();
                $item->modified_by = Yii::app()->user->id;
                if (!$item->validate()) {
                    throw new CException(Yii:: t("App", CHtml::errorSummary($item)));
                }
                if (!$item->save()) {
                    throw new CException(Yii:: t("App", "Error while saving loan item."));
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
                        throw new CException(Yii:: t("App", "Error while saving transaction."));
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

    public function actionSingle_view($id) {
        $this->checkUserAccess('loan_payment_edit');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Loan Payment Invoice");
        $this->setCurrentPage(AppUrl::URL_LOAN_PAYMENT);

        $_model = new LoanItem();
        $data = $_model->findByPk($id);

        if (empty($data)) {
            Yii::app()->user->setFlash("warning", "You are trying to access an invalid url.");
            $this->redirect(Yii::app()->createUrl(AppUrl::URL_LOAN_PAYMENT));
        }

        $this->model['model'] = $data;
        $this->model['product'] = ProductIn ::model()->find('sr_no=:sr', [':sr' => $data->sr_no]);
        $this->model['loanSetting'] = LoanSetting ::model()->findByPk(1);
        $this->render('single_view', $this->model);
    }

    public function actionAdvance_list() {
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

    public function actionAdvance_create() {
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
                    throw new CException(Yii:: t("App", CHtml::errorSummary($_model)));
                }
                if (!$_model->save()) {
                    throw new CException(Yii:: t("App", "Error while saving data."));
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
                    throw new CException(Yii:: t("App", "Error while saving data."));
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
                    throw new CException(Yii:: t("App", "Error while saving transaction."));
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
        $this->render('advance_create', $this->model);
    }

}
