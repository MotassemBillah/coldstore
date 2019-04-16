<?php

class ReceiveController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
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
        $this->render('index', $this->model);
    }

    public function actionCreate() {
        $this->checkUserAccess('loan_receive_create');
        $this->setHeadTitle("Loan Receive");
        $this->setPageTitle("Loan Receive");
        $this->setCurrentPage(AppUrl::URL_LOAN_RECEIVE);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

//        $this->render('receive_form', $this->model);
        $this->render('create', $this->model);
    }

    public function actionEdit($id) {
        $this->checkUserAccess('loan_receive_edit');
        $this->setHeadTitle("Loan Receive");
        $this->setPageTitle("Update Loan Receive");
        $this->setCurrentPage(AppUrl::URL_LOAN_RECEIVE);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new LoanReceive();
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
        $this->checkUserAccess('loan_receive_view');
        $this->setHeadTitle("Loan Receive");
        $this->setPageTitle("Loan Receive View");
        $this->setCurrentPage(AppUrl::URL_LOAN_RECEIVE);

        $_model = new LoanReceive();
        $data = $_model->find("LOWER(_key)=?", array(strtolower($id)));

        if (empty($data)) {
            Yii::app()->user->setFlash("warning", "You are trying to access an invalid url.");
            $this->redirect(Yii::app()->createUrl(AppUrl::URL_LOAN_PAYMENT));
        }

        $this->model['model'] = $data;
        $this->model['loanSetting'] = LoanSetting::model()->findByPk(1);
        $this->render('detail', $this->model);
    }

    public function actionSingle_edit($id) {
        $this->checkUserAccess('loan_receive_edit');
        $this->setHeadTitle("Loan Receive");
        $this->setPageTitle("Update Loan Receive");
        $this->setCurrentPage(AppUrl::URL_LOAN_RECEIVE);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new LoanItem();
        $item = $_model->findByPk($id);
        $loanSetting = LoanSetting ::model()->findByPk(1);

        if (empty($item)) {
            Yii::app()->user->setFlash("warning", "You are trying to access an invalid url.");
            $this->redirect(Yii::app()->createUrl(AppUrl::URL_LOAN_RECEIVE));
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
        $this->checkUserAccess('loan_receive_edit');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Update Loan Receive");
        $this->setCurrentPage(AppUrl::URL_LOAN_RECEIVE);

        $_model = new LoanItem();
        $data = $_model->findByPk($id);

        if (empty($data)) {
            Yii::app()->user->setFlash("warning", "You are trying to access an invalid url.");
            $this->redirect(Yii::app()->createUrl(AppUrl::URL_LOAN_RECEIVE));
        }

        $this->model['model'] = $data;
        $this->model['product'] = ProductIn ::model()->find('sr_no=:sr', [':sr' => $data->sr_no]);
        $this->model['loanSetting'] = LoanSetting ::model()->findByPk(1);
        $this->render('single_view', $this->model);
    }

}
