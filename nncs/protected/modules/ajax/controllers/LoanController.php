<?php

class LoanController extends AppController {

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
        $_customerID = Yii::app()->request->getPost('customer');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $invoiceNO = Yii::app()->request->getPost('q');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new ProductIn();
        $criteria = new CDbCriteria();
        if (!empty($_customerID)) {
            $criteria->addCondition("customer_id={$_customerID}");
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('create_date', $dateForm, $dateTo);
        }
        if (!empty($invoiceNO)) {
            $criteria->addCondition("sr_no like '%{$invoiceNO}%'");
        }
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $criteria->order = "id DESC";
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('index', $this->model);
    }

    public function actionList() {
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
        $this->renderPartial('list', $this->model);
    }

    public function actionPayment() {
        $_limit = Yii::app()->request->getPost('itemCount');
        $_userID = Yii::app()->request->getPost('user');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");
        $_srno = Yii::app()->request->getPost('srno');
        $_agent = Yii::app()->request->getPost('agent');

        $_model = new Loan();
        $criteria = new CDbCriteria();
        if (Yii::app()->user->role != AppConstant::ROLE_SUPERADMIN) {
            $criteria->addCondition("created_by =" . Yii::app()->user->id);
        }
        if (!empty($_userID)) {
            $criteria->addCondition("created_by={$_userID}");
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('created', $dateForm, $dateTo);
        }
        $_modelItem = new LoanItem();
        $_itemCriteria = new CDbCriteria();
        if (!empty($_srno)) {
            $_itemCriteria->addCondition("sr_no={$_srno}");
            $_itemDataset = $_modelItem->findAll($_itemCriteria);

            if (!empty($_itemDataset) && count($_itemDataset) > 0) {
                foreach ($_itemDataset as $itemdata) {
                    $loanID[] = $itemdata->loan_id;
                }
            } else {
                $loanID[] = '';
            }
            $criteria->addInCondition("id", $loanID);
        }
        if (!empty($_agent)) {
            $_itemCriteria->addCondition("agent_code={$_agent}");
            $_itemDataset = $_modelItem->findAll($_itemCriteria);

            if (!empty($_itemDataset) && count($_itemDataset) > 0) {
                foreach ($_itemDataset as $itemdata) {
                    $loanID[] = $itemdata->loan_id;
                }
            } else {
                $loanID[] = '';
            }
            $criteria->addInCondition("id", $loanID);
        }
        $criteria->order = "case_no DESC";
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
        $this->renderPartial('payment_list', $this->model);
    }

    public function actionPayment_advance_list() {
        $_limit = Yii::app()->request->getPost('itemCount');
        $_customerID = Yii::app()->request->getPost('customer');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $invoiceNO = Yii::app()->request->getPost('q');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new LoanPaymentAdvance();
        $criteria = new CDbCriteria();
        if (!empty($invoiceNO)) {
            $criteria->condition = "sr_no LIKE :match OR customer_mobile LIKE :match";
            $criteria->params = [":match" => "%$invoiceNO%"];
        }
        if (!empty($_customerID)) {
            $criteria->addCondition("customer_id =" . $_customerID);
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('created', $dateForm, $dateTo);
        }
        $criteria->order = "created DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('advance_list', $this->model);
    }

    public function actionCheck() {
        $_customerID = Yii::app()->request->getPost('customer_id');
        $invoiceNO = Yii::app()->request->getPost('sr_no');

        $_model = new LoanPending();
        $criteria = new CDbCriteria();
        if (!empty($_customerID)) {
            $criteria->addCondition("customer_id =" . $_customerID);
        }
        if (!empty($invoiceNO)) {
            $criteria->addCondition("sr_no=" . $invoiceNO);
        }
        $_dataset = $_model->findAll($criteria);
        AppHelper::pr($_dataset);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('index', $this->model);
    }

    public function actionForm() {
        $_mobile = Yii::app()->request->getPost('mobile_number');
        $_srno = Yii::app()->request->getPost('sr_number');
        $_agent = Yii::app()->request->getPost('agent_code');
        $_customerName = Yii::app()->request->getPost('customer_name');
        $_fatherName = Yii::app()->request->getPost('father_name');

        $_model = new ProductIn();
        $criteria = new CDbCriteria();
        if (!empty($_mobile)) {
            $criteria->addCondition("customer_mobile =" . $_mobile);
        }
        if (!empty($_srno)) {
            $criteria->addCondition("sr_no =" . $_srno);
        }
        if (!empty($_agent)) {
            $criteria->addCondition("agent_code =" . $_agent);
        }
        if (!empty($_customerName)) {
            $_customerModel = new Customer();
            $_cmCriteria = new CDbCriteria();
            $_cmCriteria->addCondition("name LIKE '%" . $_customerName . "%'");
            if (!empty($_fatherName)) {
                $_cmCriteria->addCondition("father_name LIKE '%" . $_fatherName . "%'");
            }
            $_customerDataset = $_customerModel->findAll($_cmCriteria);
            foreach ($_customerDataset as $cmdata) {
                $cmID[] = $cmdata->id;
            }
            $criteria->addInCondition("customer_id", $cmID);
        }
        $criteria->order = "sr_no ASC";
        if (!empty($_mobile) OR !empty($_srno) OR !empty($_agent) OR !empty($_customerName)) {
            $_dataset = $_model->findAll($criteria);
        } else {
            $_dataset = null;
        }

        $this->model['dataset'] = $_dataset;
        $this->model['loanSetting'] = LoanSetting::model()->findByPk(1);
        $this->model['loanCaseNumber'] = Loan::model()->getLastCaseNo();
        $this->renderPartial('_form', $this->model);
    }

    public function actionCreate() {
        $response = [];
        $loanSetting = LoanSetting::model()->findByPk(1);
        $_pay_date = Yii::app()->request->getPost('pay_date');
        $postedData = Yii::app()->request->getPost('data');
        $_type = Yii::app()->request->getPost('type');
        $_agent = Yii::app()->request->getPost('agent');
        $_customer = Yii::app()->request->getPost('customer_id');
        $_srno = Yii::app()->request->getPost('sr_no');
        $_qty = Yii::app()->request->getPost('quantity');
        $_rent = Yii::app()->request->getPost('rent');

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            $_model = new Loan();
            $_model->case_no = Yii::app()->request->getPost('loan_case_no');
            $_model->type = AppConstant::TYPE_REGULAR;
            $_model->created = !empty($_pay_date) ? date('Y-m-d H:i:s', strtotime($_pay_date)) : AppHelper::getDbTimestamp();
            $_model->created_by = Yii::app()->user->id;
            $_model->_key = AppHelper::getUnqiueKey();
            if (!$_model->validate()) {
                throw new CException(Yii::t("App", CHtml::errorSummary($_model)));
            }
            if (!$_model->save()) {
                throw new CException(Yii::t("App", "Error while saving data."));
            }
            $loan_id = Yii::app()->db->getLastInsertId();

            if (!empty($postedData)) {
                foreach ($postedData as $key => $val) {
                    if (!empty($postedData[$key])) {
                        $_modelItem = new LoanItem();
                        $_modelItem->loan_id = $loan_id;
                        $_modelItem->type = $_type[$val];
                        $_modelItem->agent_code = $_agent[$val];
                        $_modelItem->customer_id = $_customer[$val];
                        $_modelItem->sr_no = $_srno[$val];
                        $_modelItem->qty = $_qty[$val];
                        $_modelItem->qty_cost = $_rent[$val];
                        $_modelItem->qty_cost_total = ($_modelItem->qty * $_modelItem->qty_cost);
                        $_modelItem->net_amount = $_modelItem->qty_cost_total;
                        $_modelItem->interest_rate = $loanSetting->interest_rate;
                        $_modelItem->interest_amount = ($_modelItem->interest_rate * $_modelItem->net_amount) / 100;
                        $_modelItem->total_amount = ($_modelItem->net_amount + $_modelItem->interest_amount);
                        $_modelItem->per_day_interest = AppHelper::getFloat(($_modelItem->interest_rate * $_modelItem->net_amount) / ($loanSetting->period * 100));
                        $_modelItem->min_day = $loanSetting->min_day;
                        $_modelItem->min_payable = AppHelper::getFloat($_modelItem->per_day_interest * $_modelItem->min_day);
                        $_modelItem->loan_period = $loanSetting->period;
                        $_modelItem->status = AppConstant::ORDER_PENDING;
                        $_modelItem->create_date = !empty($_pay_date) ? date('Y-m-d', strtotime($_pay_date)) : AppHelper::getDbDate();
                        $_modelItem->created = $_modelItem->create_date;
                        $_modelItem->created_by = Yii::app()->user->id;
                        $_modelItem->_key = AppHelper::getUnqiueKey();
                        if (!$_modelItem->save()) {
                            throw new CException(Yii::t("App", "Error while saving items."));
                        }

                        /*
                          $_modelAdvLoan = new LoanPaymentAdvance();
                          $_modelAdvLoan->customer_id = $_customer[$val];
                          $_modelAdvLoan->customer_mobile = Customer::model()->findByPk($_modelItem->customer_id)->mobile;
                          $_modelAdvLoan->credit = $_modelItem->net_amount;
                          $_modelAdvLoan->balance = -($_modelAdvLoan->credit);
                          $_modelAdvLoan->note = "Loan amount tk {$_modelItem->net_amount} credited for " . Customer::model()->findByPk($_modelItem->customer_id)->name;
                          $_modelAdvLoan->created = AppHelper::getDbDate();
                          $_modelAdvLoan->created_by = Yii::app()->user->id;
                          $_modelAdvLoan->_key = AppHelper::getUnqiueKey();
                          if (!$_modelAdvLoan->save()) {
                          throw new CException(Yii::t("App", "Error while saving transaction {advance loan for customer}."));
                          }

                          $_modelAdvLoanA = new LoanPaymentAdvance();
                          $_modelAdvLoanA->agent_code = $_modelItem->agent_code;
                          $_modelAdvLoanA->credit = $_modelItem->net_amount;
                          $_modelAdvLoanA->balance = -($_modelAdvLoanA->credit);
                          $_modelAdvLoanA->note = "Loan amount tk {$_modelItem->net_amount} credited for " . Agent::model()->find('code=:code', [':code' => $_modelItem->agent_code])->name;
                          $_modelAdvLoanA->created = AppHelper::getDbDate();
                          $_modelAdvLoanA->created_by = Yii::app()->user->id;
                          $_modelAdvLoanA->_key = AppHelper::getUnqiueKey();
                          if (!$_modelAdvLoanA->save()) {
                          throw new CException(Yii::t("App", "Error while saving transaction {advance loan for agent}."));
                          }
                         */

                        $loan_item_id = Yii::app()->db->getLastInsertId();
                        $_modelCashAccount = new CashAccount();
                        $_modelCashAccount->loan_payment_id = $loan_item_id;
                        $_modelCashAccount->pay_date = $_modelItem->create_date;
                        $_modelCashAccount->purpose = "Loan paid to " . Customer::model()->findByPk($_modelItem->customer_id)->name . " @{$_modelItem->qty_cost} tk";
                        $_modelCashAccount->credit = $_modelItem->net_amount;
                        $_modelCashAccount->balance = -($_modelCashAccount->credit);
                        $_modelCashAccount->type = 'W';
                        $_modelCashAccount->created = $_modelItem->create_date;
                        $_modelCashAccount->created_by = Yii::app()->user->id;
                        $_modelCashAccount->_key = AppHelper::getUnqiueKey();
                        if (!$_modelCashAccount->save()) {
                            throw new CException(Yii::t("App", "Error while saving transaction."));
                        }
                    }
                }
            } else {
                throw new CException(Yii::t("App", "Please select at least one sr number."));
            }

            $_transaction->commit();
            $response['success'] = true;
            $response['message'] = "Record update successfull!";
        } catch (CException $e) {
            $response['success'] = false;
            $_transaction->rollback();
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        return json_encode($response);
    }

    public function actionCreate_new() {
        $response = [];
        $loanSetting = LoanSetting::model()->findByPk(1);
        $_loan_case_no = Yii::app()->request->getPost('loan_case_no');
        $_pay_date = Yii::app()->request->getPost('pay_date');
        $_loan_taken_by = Yii::app()->request->getPost('loan_taken_by');
        $_srno = Yii::app()->request->getPost('sr_no');

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            $_model = new Loan();
            $_model->case_no = $_loan_case_no;
            $_model->type = AppConstant::TYPE_REGULAR;
            $_model->qty_price = $_POST['cost_per_qty'];
            $_model->total_loan_amount = $_POST['total_loan_given'];
            $_model->taken_person = !empty($_loan_taken_by) ? $_loan_taken_by : NULL;
            $_model->created = !empty($_pay_date) ? date('Y-m-d H:i:s', strtotime($_pay_date)) : AppHelper::getDbTimestamp();
            $_model->created_by = Yii::app()->user->id;
            $_model->_key = AppHelper::getUnqiueKey();
            if (!$_model->validate()) {
                throw new CException(Yii::t("App", CHtml::errorSummary($_model)));
            }
            if (!$_model->save()) {
                throw new CException(Yii::t("App", "Error while saving data."));
            }
            $loan_id = Yii::app()->db->getLastInsertId();

            if (!empty($_srno)) {
                for ($ik = 0; $ik < count($_srno); $ik++) {
                    if (!empty($_srno[$ik])) {
                        $_srInfo = ProductIn::model()->find("sr_no=:sr", [":sr" => $_srno[$ik]]);
                        $_modelItem = new LoanItem();
                        $_modelItem->loan_id = $loan_id;
                        $_modelItem->type = !empty($_srInfo->type) ? $_srInfo->type : NULL;
                        $_modelItem->agent_code = !empty($_srInfo->agent_code) ? $_srInfo->agent_code : NULL;
                        $_modelItem->customer_id = !empty($_srInfo->customer_id) ? $_srInfo->customer_id : NULL;
                        $_modelItem->sr_no = $_srno[$ik];
                        if (!empty($_POST['quantity'][$ik])) {
                            $_modelItem->qty = $_POST['quantity'][$ik];
                        } else {
                            throw new CException(Yii::t("App", "Quantity required for sr number <b>{$_srno[$ik]}</b>"));
                        }
                        $_modelItem->qty_cost = $_POST['rent'][$ik];
                        $_modelItem->qty_cost_total = ($_modelItem->qty * $_modelItem->qty_cost);
                        $_modelItem->net_amount = $_POST['loan_amount'][$ik];
                        $_modelItem->interest_rate = $loanSetting->interest_rate;
                        $_modelItem->interest_amount = ($_modelItem->interest_rate * $_modelItem->net_amount ) / 100;
                        $_modelItem->total_amount = ($_modelItem->net_amount + $_modelItem->interest_amount);
                        $_modelItem->per_day_interest = AppHelper::getFloat(($_modelItem->interest_rate * $_modelItem->net_amount) / ($loanSetting->period * 100));
                        $_modelItem->min_day = $loanSetting->min_day;
                        $_modelItem->min_payable = AppHelper::getFloat($_modelItem->per_day_interest * $_modelItem->min_day);
                        $_modelItem->loan_period = $loanSetting->period;
                        $_modelItem->status = AppConstant::ORDER_PENDING;
                        $_modelItem->create_date = !empty($_pay_date) ? date('Y-m-d', strtotime($_pay_date)) : AppHelper::getDbDate();
                        $_modelItem->created = $_model->created;
                        $_modelItem->created_by = Yii::app()->user->id;
                        $_modelItem->_key = AppHelper ::getUnqiueKey();
                        if (!$_modelItem->validate()) {
                            throw new CException(Yii::t("App", CHtml:: errorSummary($_modelItem)));
                        }
                        if (!$_modelItem->save()) {
                            throw new CException(Yii::t("App", "Error while saving items."));
                        }

                        $loan_item_id = Yii::app()->db->getLastInsertId();
                        $_modelCashAccount = new CashAccount();
                        $_modelCashAccount->loan_payment_id = $loan_item_id;
                        $_modelCashAccount->ledger_head_id = AppConstant::LOAN_HEAD_ID;
                        $_modelCashAccount->pay_date = $_modelItem->create_date;
                        $_modelCashAccount->purpose = "Loan paid to " . Customer::model()->findByPk($_modelItem->customer_id)->name . " @{$_modelItem->qty_cost} tk";
                        $_modelCashAccount->credit = $_modelItem->net_amount;
                        $_modelCashAccount->balance = -($_modelCashAccount->credit);
                        $_modelCashAccount->type = 'W';
                        $_modelCashAccount->created = $_modelItem->create_date;
                        $_modelCashAccount->created_by = Yii::app()->user->id;
                        $_modelCashAccount->_key = AppHelper::getUnqiueKey();
                        if (!$_modelCashAccount->save()) {
                            throw new CException(Yii::t("App", "Error while saving transaction."));
                        }
                    }
                }
            } else {
                throw new CException(Yii::t("App", "No sr number found to process loan."));
            }

            $_transaction->commit();
            $response['success'] = true;
            $response['message'] = "Record update successfull!";
        } catch (CException $e) {
            $response['success'] = false;
            $_transaction->rollback();
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        return json_encode($response);
    }

    public function actionReceive() {
        $_limit = Yii::app()->request->getPost('itemCount');
        $_userID = Yii::app()->request->getPost('user');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $_srno = Yii::app()->request->getPost('srno');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");
        //$_sortType = Yii::app()->request->getPost('sort_type');
        //$_sortBy = Yii::app()->request->getPost('sort_by');

        $_model = new LoanReceive();
        $criteria = new CDbCriteria();
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('receive_date', $dateForm, $dateTo);
        }
        if (!empty($_srno)) {
            $criteria->addCondition("sr_no={$_srno}");
        }
        if (!empty($_userID)) {
            $criteria->addCondition("created_by={$_userID}");
        }
//        if (!empty($_sortBy)) {
//            $criteria->order = "{$_sortBy} {$_sortType}";
//        } else {
//            $criteria->order = "receive_date DESC";
//        }
        $criteria->order = "receive_date DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('received', $this->model);
    }

    public function actionReceive_list() {
        $_limit = Yii::app()->request->getPost('itemCount');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $_customer = Yii::app()->request->getPost('customer');
        $_srno = Yii::app()->request->getPost('srno');
        $_agent = Yii::app()->request->getPost('agent');
        $_userID = Yii::app()->request->getPost('user');
        $_sortType = Yii::app()->request->getPost('sort_type');
        $_sortBy = Yii::app()->request->getPost('sort_by');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new LoanReceiveItem();
        $criteria = new CDbCriteria();
        if (!empty($_userID)) {
            $criteria->addCondition("created_by={$_userID}");
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('receive_date', $dateForm, $dateTo);
        }
        if (!empty($_customer)) {
            $_cmodel = new Customer();
            $_ccriteria = new CDbCriteria();
            $_ccriteria->addCondition("name LIKE '%" . trim($_customer) . "%'");
            $_cdata = $_cmodel->findAll($_ccriteria);
            foreach ($_cdata as $_cd) {
                $_cid[] = $_cd->id;
            }
            $criteria->addInCondition("customer_id", $_cid);
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
        $this->renderPartial('receive_item_list', $this->model);
    }

    public function actionReceive_form() {
        $_data = Yii::app()->request->getParam('data');
        $ids = [];
        $_model = new LoanPending();
        $criteria = new CDbCriteria();
        for ($i = 0; $i < count($_data); $i++) {
            $ids[] = $_data[$i];
        }
        $criteria->addInCondition('id', $ids);
        $_dataset = $_model->findAll($criteria);
        $this->model['dataset'] = $_dataset;
        $this->renderPartial('_form_receive', $this->model);
    }

    public function actionReceive_save() {
        $response = [];
        $_date = $_POST['pay_date'];
        $posted_srno = Yii::app()->request->getPost('sr_no');

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            if (empty($posted_srno[0])) {
                throw new CException(Yii::t("App", "SR Number Required."));
            }

            $receiveModel = new LoanReceive();
            $receiveModel->receive_number = LoanReceive::model()->lastNumber();
            $receiveModel->receive_date = date('Y-m-d', strtotime($_date));
            $receiveModel->created = AppHelper::getDbTimestamp();
            $receiveModel->created_by = Yii::app()->user->id;
            $receiveModel->_key = AppHelper::getUnqiueKey();
            if (!$receiveModel->validate()) {
                throw new CException(Yii::t("App", CHtml::errorSummary($receiveModel)));
            }
            if (!$receiveModel->save()) {
                throw new CException(Yii::t("App", "Error while saving loan receive."));
            }

            $receive_id = Yii::app()->db->getLastInsertId();

            if (!empty($posted_srno)) {
                foreach ($posted_srno as $key => $val) {
                    if (!empty($posted_srno[$key])) {
                        if (empty($_POST['quantity'][$key])) {
                            throw new CException(Yii::t("App", "Quantity required for sr number {$posted_srno[$key]}."));
                        }

                        $srinfo = ProductIn::model()->find('sr_no=:sr', [':sr' => $val]);
                        if (empty($srinfo)) {
                            throw new CException(Yii::t("App", "SR Number not found or Invalid."));
                        }

                        $receiveItem = new LoanReceiveItem();
                        $receiveItem->receive_id = $receive_id;
                        $receiveItem->customer_id = $srinfo->customer_id;
                        $receiveItem->sr_no = $val;
                        $receiveItem->agent_code = $srinfo->agent_code;
                        $receiveItem->lot_no = $srinfo->lot_no;
                        $receiveItem->qty = $_POST['quantity'][$key];
                        $receiveItem->cost_per_qty = $_POST['per_bag_loan'][$key];
                        $receiveItem->loan_amount = $_POST['loan_amount'][$key];
                        $receiveItem->loan_days = $_POST['day'][$key];
                        $receiveItem->interest_amount = $_POST['interest'][$key];
                        $receiveItem->net_amount = $_POST['loan_total'][$key];
                        $receiveItem->receive_date = $receiveModel->receive_date;
                        $receiveItem->created = AppHelper::getDbTimestamp();
                        $receiveItem->created_by = Yii::app()->user->id;
                        $receiveItem->_key = AppHelper::getUnqiueKey() . $key;
                        if (!$receiveItem->save()) {
                            throw new CException(Yii::t("App", "Error while saving loan receive item."));
                        }

                        $receiveItemId = Yii::app()->db->getLastInsertId();
                        $_modelCashAccount = new CashAccount();
                        $_modelCashAccount->loan_receive_id = $receiveItemId;
                        $_modelCashAccount->type = 'D';
                        $_modelCashAccount->pay_date = $receiveItem->receive_date;
                        $_modelCashAccount->ledger_head_id = AppConstant::LOAN_HEAD_ID;
                        $_modelCashAccount->purpose = "Loan receive for sr {$receiveItem->sr_no}";
                        $_modelCashAccount->by_whom = User::model()->displayname(Yii::app()->user->id);
                        $_modelCashAccount->debit = $receiveItem->net_amount;
                        $_modelCashAccount->balance = $_modelCashAccount->debit;
                        $_modelCashAccount->created = $receiveItem->receive_date;
                        $_modelCashAccount->created_by = Yii::app()->user->id;
                        $_modelCashAccount->_key = AppHelper::getUnqiueKey() . $key;
                        if (!$_modelCashAccount->save()) {
                            throw new CException(Yii::t("App", "Error while saving transaction."));
                        }
                    }
                }
            } else {
                throw new CException(Yii::t("App", "At least one sr number required."));
            }

            $_transaction->commit();
            $response['success'] = true;
            $response['message'] = "New record save successfull.";
        } catch (CException $e) {
            $response['success'] = false;
            $_transaction->rollback();
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        return json_encode($response);
    }

    public function actionReceive_single() {
        $response = [];
        $_date = $_POST['delivery_date'];
        $_srno = $_POST['srno'];
        $srinfo = ProductIn::model()->find('sr_no=:sr', [':sr' => $_srno]);

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            if (empty($_srno)) {
                throw new CException(Yii::t("App", "SR Number Required."));
            }

            if (empty($srinfo)) {
                throw new CException(Yii::t("App", "SR number not found or invalid."));
            }

            $delivery = new Delivery();
            $delivery->sr_no = $_srno;
            $delivery->delivery_number = $_POST['receipt_no'];
            $delivery->person = $_POST['delivery_person'];
            $delivery->delivery_date = date('Y-m-d', strtotime($_date));
            $delivery->created = AppHelper::getDbTimestamp();
            $delivery->created_by = Yii::app()->user->id;
            $delivery->_key = AppHelper::getUnqiueKey();
            if (!$delivery->validate()) {
                throw new CException(Yii::t("App", CHtml::errorSummary($delivery)));
            }
            if (!$delivery->save()) {
                throw new CException(Yii::t("App", "Error while saving delivery."));
            }

            $delivery_id = Yii::app()->db->getLastInsertId();
            $deliveryItem = new DeliveryItem ();
            $deliveryItem->delivery_id = $delivery_id;
            $deliveryItem->delivery_number = $delivery->delivery_number;
            $deliveryItem->customer_id = $srinfo->customer_id;
            $deliveryItem->sr_no = $_srno;
            $deliveryItem->type = $srinfo->type;
            $deliveryItem->agent_code = $srinfo->agent_code;
            $deliveryItem->lot_no = $srinfo->lot_no;
            //$deliveryItem->loan_bag = $_POST['Delivery']['empty_bag'];
            //$deliveryItem->loan_bag_price = $data['Delivery']['empty_bag_price'];
            //$deliveryItem->loan_bag_price_total = $data['Delivery']['empty_bag_amount'];
            //$deliveryItem->carrying = $data['Delivery']['carrying'];
            //$deliveryItem->quantity = $data['Delivery']['quantity'];
            //$deliveryItem->rent = $data['Delivery']['rent'];
            //$deliveryItem->rent_total = $data['Delivery']['rent_total'];
            //$deliveryItem->fan_charge = $data['Delivery']['fan_charge'];
            //$deliveryItem->fan_charge_qty = $data['Delivery']['fan_charge_qty'];
            //$deliveryItem->fan_charge_total = $data['Delivery']['fan_charge_total'];
            //$deliveryItem->delivery_total = ($deliveryItem->loan_bag_price_total + $deliveryItem->carrying + $deliveryItem->rent_total + $deliveryItem->fan_charge_total);
            //$deliveryItem->discount = $data['Delivery']['discount'];
            //$deliveryItem->net_total = $data['Delivery']['net_amount'];
            //$deliveryItem->cur_qty = (AppObject::currentStock($deliveryItem->sr_no) - $deliveryItem->quantity);
            $deliveryItem->delivery_date = $delivery->delivery_date;
            $deliveryItem->created = AppHelper::getDbTimestamp();
            $deliveryItem->created_by = Yii::app()->user->id;
            $deliveryItem->_key = AppHelper::getUnqiueKey();
            if (!$deliveryItem->save()) {
                throw new CException(Yii::t("App", "Error while saving delivery item."));
            }

            $deliveryItemId = Yii::app()->db->getLastInsertId();
            $_modelCashAccountD = new CashAccount();
            $_modelCashAccountD->product_out_payment_id = $deliveryItemId;
            $_modelCashAccountD->type = 'D';
            $_modelCashAccountD->pay_date = $deliveryItem->delivery_date;
            $_modelCashAccountD->ledger_head_id = AppConstant::DELIVERY_HEAD_ID;
            $_modelCashAccountD->purpose = "Delivery for SR {$deliveryItem->sr_no}";
            $_modelCashAccountD->by_whom = User::model()->displayname(Yii::app()->user->id);
            $_modelCashAccountD->debit = NULL;
            $_modelCashAccountD->balance = $_modelCashAccountD->debit;
            $_modelCashAccountD->created = $deliveryItem->delivery_date;
            $_modelCashAccountD->created_by = Yii::app()->user->id;
            $_modelCashAccountD->_key = AppHelper::getUnqiueKey();
            if (!$_modelCashAccountD->save()) {
                throw new CException(Yii::t("App", "Error while saving transaction."));
            }

            $receiveModel = new LoanReceive();
            $receiveModel->sr_no = $_srno;
            $receiveModel->receive_number = $_POST['receipt_no'];
            $receiveModel->receive_date = date('Y-m-d', strtotime($_date));
            $receiveModel->received_by = $_POST['delivery_person'];
            $receiveModel->created = AppHelper::getDbTimestamp();
            $receiveModel->created_by = Yii::app()->user->id;
            $receiveModel->_key = AppHelper::getUnqiueKey();
            if (!$receiveModel->validate()) {
                throw new CException(Yii::t("App", CHtml::errorSummary($receiveModel)));
            }
            if (!$receiveModel->save()) {
                throw new CException(Yii::t("App", "Error while saving loan receive."));
            }

            $receive_id = Yii::app()->db->getLastInsertId();
            $receiveItem = new LoanReceiveItem();
            $receiveItem->receive_id = $receive_id;
            $receiveItem->delivery_number = $receiveModel->receive_number;
            $receiveItem->customer_id = $srinfo->customer_id;
            $receiveItem->sr_no = $_srno;
            //$receiveItem->qty = $_POST['quantity'][$key];
            $receiveItem->lot_no = $srinfo->lot_no;
            $receiveItem->agent_code = $srinfo->agent_code;
            //$receiveItem->cost_per_qty = $_POST['LoanReceived']['per_bag_loan'];
            $receiveItem->loan_amount = $_POST['LoanReceived']['amount'];
            $receiveItem->interest_amount = $_POST['LoanReceived']['interest'];
            $receiveItem->loan_days = $_POST['LoanReceived']['day'];
            $receiveItem->total_amount = $_POST['LoanReceived']['total'];
            $receiveItem->discount = $_POST['LoanReceived']['discount'];
            $receiveItem->net_amount = $_POST['LoanReceived']['net_amount'];
            $receiveItem->receive_date = $receiveModel->receive_date;
            $receiveItem->created = AppHelper::getDbTimestamp();
            $receiveItem->created_by = Yii::app()->user->id;
            $receiveItem->_key = AppHelper::getUnqiueKey();
            if (!$receiveItem->save()) {
                throw new CException(Yii::t("App", "Error while saving loan receive item."));
            }

            $receiveItemId = Yii::app()->db->getLastInsertId();
            $_modelCashAccount = new CashAccount();
            $_modelCashAccount->loan_receive_id = $receiveItemId;
            $_modelCashAccount->ledger_head_id = AppConstant::LOAN_HEAD_ID;
            $_modelCashAccount->pay_date = $receiveItem->receive_date;
            $_modelCashAccount->type = 'D';
            $_modelCashAccount->purpose = "Loan receive for sr {$receiveItem->sr_no}";
            $_modelCashAccount->by_whom = User::model()->displayname(Yii::app()->user->id);
            $_modelCashAccount->debit = $receiveItem->net_amount;
            $_modelCashAccount->balance = $_modelCashAccount->debit;
            $_modelCashAccount->created = $receiveItem->receive_date;
            $_modelCashAccount->created_by = Yii::app()->user->id;
            $_modelCashAccount->_key = AppHelper::getUnqiueKey();
            if (!$_modelCashAccount->save()) {
                throw new CException(Yii::t("App", "Error while saving transaction."));
            }

            $_transaction->commit();
            $response['success'] = true;
            $response['message'] = "New record save successfull.";
        } catch (CException $e) {
            $response['success'] = false;
            $_transaction->rollback();
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        return json_encode($response);
    }

    public function actionUpdate_setting() {
        $response = [];
        $loanSetting = LoanSetting::model()->findByPk(1);
        $loanSetting->interest_rate = Yii::app()->request->getPost('interest_rate');
        $loanSetting->period = Yii::app()->request->getPost('period');
        $loanSetting->min_day = Yii::app()->request->getPost('min_day');
        $loanSetting->empty_bag_price = Yii::app()->request->getPost('empty_bag_price');
        $loanSetting->max_loan_per_qty = Yii::app()->request->getPost('max_loan_per_qty');
        $loanSetting->max_rent_per_qty = Yii::app()->request->getPost('max_rent_per_qty');
        $loanSetting->fan_charge = Yii::app()->request->getPost('fan_charge');

        $_transaction = Yii::app()->db->beginTransaction();

        try {
            if (!$loanSetting->save()) {
                throw new CException(Yii::t('App', "Error while saving data."));
            }

            $_transaction->commit();
            $response['success'] = true;
            $response['message'] = "Record update successfull!";
        } catch (CException $e) {
            $_transaction->rollback();
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        } echo json_encode($response);
        return json_encode($response);
    }

    public function actionDeleteall_payment() {
        $response = array();
        $_data = $_POST['data'];
        $_model = new Loan();

        if (isset($_data)) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < count($_data); $i++) {
                    $_obj = $_model->with('items')->findByPk($_data[$i]);

                    if (!empty($_obj->items)) {
                        foreach ($_obj->items as $item) {
                            $cash_payment = CashAccount::model()->find('loan_payment_id=:lpayid', [':lpayid' => $item->id]);
                            if (!empty($cash_payment)) {
                                if (!$cash_payment->delete()) {
                                    throw new CException(Yii::t('App', "Error while deleting cash payment record."));
                                }
                            }
                            if (!$item->delete()) {
                                throw new CException(Yii::t('App', "Error while deleting loan pending record."));
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
                $response['success'] = false;
                $_transaction->rollback();
                $response['message'] = $e->getMessage();
            }
        } else {
            $response['success'] = false;
            $response['message'] = "No record found to delete!";
        }

        echo json_encode($response);
        return json_encode($response);
    }

    public function actionDeleteall_advance_loan() {
        $response = array();
        $_data = $_POST['data'];
        $_model = new LoanPaymentAdvance();

        if (isset($_data)) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < count($_data); $i++) {
                    $_obj = $_model->findByPk($_data[$i]);

                    $_cash_account = CashAccount::model()->find('adv_loan_payment_id=:alpid', [':alpid' => $_obj->id]);
                    if (!empty($_cash_account)) {
                        if (!$_cash_account->delete()) {
                            throw new CException(Yii::t("App", "Error while deleting transaction."));
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
                $response['success'] = false;
                $_transaction->rollback();
                $response ['message'] = $e->getMessage();
            }
        } else {
            $response['success'] = false;
            $response['message'] = "No record found to delete!";
        }

        echo json_encode($response);
        return json_encode($response);
    }

    public function actionDeleteall_receive() {
        $response = array();
        $_model = new LoanReceive();

        if (isset($_POST['data'])) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < count($_POST['data']); $i++) {
                    $_obj = $_model->findByPk($_POST['data'][$i]);

                    if (!empty($_obj->items)) {
                        foreach ($_obj->items as $item) {
                            $cash_payment = CashAccount::model()->find('loan_receive_id=:lrid', [':lrid' => $item->id]);
                            if (!empty($cash_payment)) {
                                if (!$cash_payment->delete()) {
                                    throw new CException(Yii::t('App', "Error while deleting cash payment record."));
                                }
                            }

                            if (!$item->delete()) {
                                throw new CException(Yii::t('App', "Error while deleting item."));
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
                $response['success'] = false;
                $_transaction->rollback();
                $response['message'] = $e->getMessage();
            }
        } else {
            $response['success'] = false;
            $response['message'] = "No record found to delete!";
        }

        echo json_encode($response);
        return json_encode($response);
    }

    public function actionAdd_item_form($loan_id) {
        $loanModel = Loan::model()->findByPk($loan_id);
        $this->model['loanSetting'] = LoanSetting::model()->findByPk(1);

        $_loanInfo['id'] = $loanModel->id;
        $_loanInfo['case'] = $loanModel->case_no;

        $this->model['loanInfo'] = $_loanInfo;
        $this->renderPartial('add_loan_item', $this->model);
    }

    public function actionAdd_item() {
        $response = array();
        $_loan = Loan::model()->findByPk($_POST['loanID']);
        $loanSetting = LoanSetting::model()->findByPk(1);

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            if (empty($_POST['sr_info'])) {
                throw new CException(Yii::t("App", "Sr number required."));
            }

            $itemExists = LoanItem::model()->exists('sr_no=:sr', [':sr' => $_POST['sr_info']]);
            if ($itemExists) {
                throw new CException(Yii::t("App", "This SR Number already exists in loan."));
            }

            if (empty($_POST['quantity'])) {
                throw new CException(Yii::t("App", "Quantity required for sr number <b>{$_POST['sr_info']}.</b>"));
            }
            $_srData = ProductIn::model()->find('sr_no=:sr', [':sr' => $_POST['sr_info']]);

            $_modelItem = new LoanItem();
            $_modelItem->loan_id = $_POST['loanID'];
            $_modelItem->type = $_srData->type;
            if (!empty($_srData->agent_code)) {
                $_modelItem->agent_code = $_srData->agent_code;
            }
            $_modelItem->customer_id = $_srData->customer_id;
            $_modelItem->sr_no = $_POST['sr_info'];
            $_modelItem->qty = $_POST['quantity'];
            $_modelItem->qty_cost = $_POST['rent'];
            $_modelItem->qty_cost_total = ($_modelItem->qty * $_modelItem->qty_cost);
            /* if (!empty($_loanbag[$ik])) {
              $_modelItem->loanbag = $_loanbag[$ik];
              $_modelItem->loanbag_cost = $_loan_bag_price[$ik];
              $_modelItem->loanbag_cost_total = ($_modelItem->loanbag * $_modelItem->loanbag_cost);
              } else {
              $_modelItem->loanbag = NULL;
              $_modelItem->loanbag_cost = NULL;
              $_modelItem->loanbag_cost_total = NULL;
              }
              if (!empty($_carrying_cost[$ik])) {
              $_modelItem->carrying_cost = $_carrying_cost[$ik];
              } else {
              $_modelItem->carrying_cost = NULL;
              } */
            $_modelItem->net_amount = $_modelItem->qty_cost_total;
            $_modelItem->interest_rate = $loanSetting->interest_rate;
            $_modelItem->interest_amount = ($_modelItem->interest_rate * $_modelItem->net_amount ) / 100;
            $_modelItem->total_amount = ($_modelItem->net_amount + $_modelItem->interest_amount);
            $_modelItem->per_day_interest = AppHelper::getFloat(($_modelItem->interest_rate * $_modelItem->net_amount) / ($loanSetting->period * 100));
            $_modelItem->min_day = $loanSetting->min_day;
            $_modelItem->min_payable = AppHelper::getFloat($_modelItem->per_day_interest * $_modelItem->min_day);
            $_modelItem->loan_period = $loanSetting->period;
            $_modelItem->status = AppConstant ::ORDER_PENDING;
            $_modelItem->create_date = date("Y-m-d", strtotime($_loan->created));
            $_modelItem->created = $_loan->created;
            $_modelItem->created_by = Yii::app()->user->id;
            $_modelItem->_key = AppHelper ::getUnqiueKey();
            if (!$_modelItem->validate()) {
                throw new CException(Yii::t("App", CHtml:: errorSummary($_modelItem)));
            }
            if (!$_modelItem->save()) {
                throw new CException(Yii::t("App", "Error while saving items."));
            }

            $loan_item_id = Yii::app()->db->getLastInsertId();
            $_modelCashAccount = new CashAccount();
            $_modelCashAccount->loan_payment_id = $loan_item_id;
            $_modelCashAccount->ledger_head_id = AppConstant::LOAN_HEAD_ID;
            $_modelCashAccount->purpose = "Loan paid to " . Customer ::model()->findByPk($_modelItem->customer_id)->name . " @{$_modelItem->qty_cost} tk";
            $_modelCashAccount->credit = $_modelItem->net_amount;
            $_modelCashAccount->balance = -($_modelCashAccount->credit);
            $_modelCashAccount->type = 'W';
            $_modelCashAccount->created = $_loan->created;
            $_modelCashAccount->created_by = Yii::app()->user->id;
            $_modelCashAccount->_key = AppHelper::getUnqiueKey();

            if (!$_modelCashAccount->save()) {
                throw new CException(Yii::t("App", "Error while saving transaction."));
            }

            $_transaction->commit();
            $response['success'] = true;
            $response['message'] = "Records deleted successfully!";
        } catch (CException $e) {
            $response['success'] = false;
            $_transaction->rollback();
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        return json_encode($response);
    }

    public function actionRemove_item($id) {
        $response = array();
        $_model = LoanItem::model()->findByPk($id);
        $_transaction = Yii::app()->db->beginTransaction();
        try {
            $cash_payment = CashAccount::model()->find('loan_payment_id=:lpayid', [':lpayid' => $id]);
            if (!empty($cash_payment)) {
                if (!$cash_payment->delete()) {
                    throw new CException(Yii::t('App', "Error while deleting cash payment record."));
                }
            }

            if (!$_model->delete()) {
                throw new CException(Yii::t('App', "Error while deleting removing item record."));
            }

            $_transaction->commit();
            $response['success'] = true;

            $response['message'] = "Records deleted successfully!";
        } catch (CException $e) {
            $response['success'] = false;
            $_transaction->rollback();
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        return json_encode($response);
    }

}
