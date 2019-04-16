<?php

class AgentController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('agent_list');
        $this->setHeadTitle("Agents");
        $this->setPageTitle("Agent List");
        $this->setCurrentPage(AppUrl::URL_AGENT);
        $this->addJs('views/agent/list.js');

        $_model = new Agent();
        $criteria = new CDbCriteria();
        $criteria->order = "code,name ASC";
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
        $this->checkUserAccess('agent_create');
        $this->setHeadTitle("Agents");
        $this->setPageTitle("Create Agent");
        $this->setCurrentPage(AppUrl::URL_AGENT);

        $_model = new Agent();

        if (isset($_POST['Agent'])) {
            $_model->attributes = $_POST['Agent'];
            $_model->name = ucwords($_POST['Agent']['name']);
            $_model->father_name = ucwords($_POST['Agent']['father_name']);
            $_model->zila = $_POST['Agent']['zila'];
            $_model->upozila = $_POST['Agent']['upozila'];
            $_model->village = $_POST['Agent']['village'];
            $_model->post = $_POST['Agent']['post'];
            $_model->mobile = $_POST['Agent']['mobile'];
            $_model->code = $_POST['Agent']['code'];
            $_model->created = AppHelper::getDbTimestamp();
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
                $this->redirect(array(AppUrl::URL_AGENT));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_model;
        $this->render('form', $this->model);
    }

    public function actionEdit($id) {
        $this->checkUserAccess('agent_edit');
        $this->setHeadTitle("Agents");
        $this->setPageTitle("Edit Agent");
        $this->setCurrentPage(AppUrl::URL_AGENT);

        $_model = new Agent();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

        if (isset($_POST['Agent'])) {
            $_data->attributes = $_POST['Agent'];
            $_data->name = ucwords($_POST['Agent']['name']);
            $_data->father_name = ucwords($_POST['Agent']['father_name']);
            $_data->zila = $_POST['Agent']['zila'];
            $_data->upozila = $_POST['Agent']['upozila'];
            $_data->post = $_POST['Agent']['post'];
            $_data->village = $_POST['Agent']['village'];
            $_data->mobile = $_POST['Agent']['mobile'];
            $_data->code = $_POST['Agent']['code'];
            $_data->modified = AppHelper::getDbTimestamp();
            $_data->modified_by = Yii::app()->user->id;

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
                $this->redirect(array(AppUrl::URL_AGENT));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_data;
        $this->render('form', $this->model);
    }

    public function actionLedger($id) {
        $this->checkUserAccess('agent_loan');
        $this->setHeadTitle("Agent");
        $this->setCurrentPage(AppUrl::URL_AGENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new Agent();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));
        $this->setPageTitle("Ledger For - " . $_data->name);

        $criteria = new CDbCriteria();
        $criteria->condition = "agent_code=:agcd";
        $criteria->params = [":agcd" => $_data->code];
        $criteria->order = "sr_no ASC";
        $count = LoanItem::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = LoanItem::model()->findAll($criteria);

        $this->model['model'] = $_data;
        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('ledger', $this->model);
    }

    public function actionLoan($id) {
        $this->checkUserAccess('agent_loan');
        $this->setHeadTitle("Agent");
        $this->setCurrentPage(AppUrl::URL_AGENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new Agent();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));
        $this->setPageTitle("Advance Loans For - " . $_data->name);

        $criteria = new CDbCriteria();
        $count = $_model->count($criteria);
        $criteria->condition = "agent_code=:agcd";
        $criteria->params = [":agcd" => $_data->code];
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = LoanPaymentAdvance::model()->findAll($criteria);

        $this->model['model'] = $_data;
        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('loans', $this->model);
    }

    public function actionAdvance_loan_create($id) {
        $this->checkUserAccess('agent_loan_create');
        $this->setHeadTitle("Loan Payment");
        $this->setCurrentPage(AppUrl::URL_AGENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_modelAdvLoan = new LoanPaymentAdvance();
        $_model = new Agent();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));
        $this->setPageTitle("New Loan For - " . $_data->name);

        if (isset($_POST['submit_adv_loan'])) {
            $_date = !empty($_POST['pay_date']) ? date('Y-m-d', strtotime($_POST['pay_date'])) : date('Y-m-d');
            $_agent = $_POST['agent_code'];

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                $_modelAdvLoan->case_no = $_POST['LoanPaymentAdvance']['case_no'];
                $_modelAdvLoan->agent_code = $_agent;
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
                $_modelCashAccount->purpose = "Advance loan paid to " . Agent::model()->find('code=:code', [':code' => $_agent])->name;
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
                $this->redirect($this->createUrl(AppUrl::URL_AGENT_LOAN, ['id' => $_data->_key]));
                $this->refresh();
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_data;
        $this->model['loanForm'] = $_modelAdvLoan;
        $this->render('advance_loan_create', $this->model);
    }

    public function actionLoan_edit($id, $lid) {
        $this->checkUserAccess('agent_loan_edit');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Edit Loan");
        $this->setCurrentPage(AppUrl::URL_AGENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new Agent();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));
        $_modelAdvLoan = new LoanPaymentAdvance();
        $_dataAdvLoan = $_modelAdvLoan->find('LOWER(_key) = ?', array(strtolower($lid)));

        if (isset($_POST['submit_adv_loan'])) {
            $_date = !empty($_POST['pay_date']) ? date('Y-m-d', strtotime($_POST['pay_date'])) : date('Y-m-d');
            $_agent = $_POST['agent_code'];

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                $_dataAdvLoan->case_no = $_POST['LoanPaymentAdvance']['case_no'];
                $_dataAdvLoan->agent_code = $_agent;
                $_dataAdvLoan->empty_bag = $_POST['LoanPaymentAdvance']['empty_bag'];
                $_dataAdvLoan->empty_bag_price = $_POST['LoanPaymentAdvance']['empty_bag_price'];
                $_dataAdvLoan->empty_bag_price_total = ($_dataAdvLoan->empty_bag * $_dataAdvLoan->empty_bag_price);
                $_dataAdvLoan->carrying_cost = $_POST['LoanPaymentAdvance']['carrying_cost'];
                $_dataAdvLoan->loan_amount = $_POST['LoanPaymentAdvance']['loan_amount'];
                $_dataAdvLoan->total_loan_amount = ($_dataAdvLoan->empty_bag_price_total + $_dataAdvLoan->carrying_cost + $_dataAdvLoan->loan_amount);
                $_dataAdvLoan->debit = $_dataAdvLoan->total_loan_amount;
                $_dataAdvLoan->balance = $_dataAdvLoan->debit;
                $_dataAdvLoan->note = "Loan paid in advance tk {$_dataAdvLoan->total_loan_amount}";
                $_dataAdvLoan->created = $_date;
                $_dataAdvLoan->modified_by = AppHelper::getDbTimestamp();
                $_dataAdvLoan->modified_by = Yii::app()->user->id;
                if (!$_dataAdvLoan->validate()) {
                    throw new CException(Yii::t("App", CHtml::errorSummary($_dataAdvLoan)));
                }
                if (empty($_POST['LoanPaymentAdvance']['empty_bag']) && empty($_POST['LoanPaymentAdvance']['carrying_cost']) && empty($_POST['LoanPaymentAdvance']['loan_amount'])) {
                    throw new CException(Yii::t("strings", "Empty bag or carrying cost or loan amount is required"));
                }
                if (!empty($_POST['LoanPaymentAdvance']['empty_bag'])) {
                    if (empty($_POST['LoanPaymentAdvance']['empty_bag_price'])) {
                        throw new CException(Yii::t("strings", "Empty bag price is required"));
                    }
                }
                if (!$_dataAdvLoan->save()) {
                    throw new CException(Yii::t("App", "Error while saving data."));
                }

                $_modelCashAccount = CashAccount::model()->find('adv_loan_payment_id=:alpid', [':alpid' => $_dataAdvLoan->id]);
                if (!empty($_modelCashAccount)) {
                    $_modelCashAccount->credit = $_dataAdvLoan->total_loan_amount;
                    $_modelCashAccount->balance = -($_modelCashAccount->credit);
                    $_modelCashAccount->modified = AppHelper::getDbTimestamp();
                    $_modelCashAccount->modified_by = Yii::app()->user->id;
                    if (!$_modelCashAccount->save()) {
                        throw new CException(Yii::t("App", "Error while saving transaction."));
                    }
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "New record save successfull.");
                $this->redirect($this->createUrl(AppUrl::URL_AGENT_LOAN, ['id' => $_data->_key]));
                $this->refresh();
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_data;
        $this->model['loanForm'] = $_dataAdvLoan;
        $this->render('advance_loan_create', $this->model);
    }

    /* Ajax calls */

    public function actionSearch() {
        $this->is_ajax_request();
        $_limit = Yii::app()->request->getPost('itemCount');
        $_sortType = Yii::app()->request->getPost('sort_type');
        $_sortBy = Yii::app()->request->getPost('sort_by');
        $_search = Yii::app()->request->getPost('search');
        $_agcode = Yii::app()->request->getPost('agcode');

        $_model = new Agent();
        $criteria = new CDbCriteria();
        if (!empty($_search)) {
            $criteria->condition = "name LIKE :match OR father_name LIKE :match OR mobile LIKE :match";
            $criteria->params = array(':match' => "%$_search%");
        }
        if (!empty($_agcode)) {
            $criteria->addCondition("code={$_agcode}");
        }
        if (!empty($_sortBy)) {
            $criteria->order = "{$_sortBy} {$_sortType}";
        } else {
            $criteria->order = "code ASC";
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

    public function actionSearch_ledger($id) {
        $this->is_ajax_request();
        $_key = Yii::app()->request->getPost('dataKey');
        $_cid = Yii::app()->request->getPost('code_no');
        $_limit = Yii::app()->request->getPost('itemCount');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $_srno = Yii::app()->request->getPost('srno');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        //$_model = new Agent();
        //$_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

        $_modelLoanItem = new LoanItem();
        $criteria = new CDbCriteria();
        $criteria->condition = "agent_code={$_cid}";
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

        //$this->model['model'] = $_data;
        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('_list_ledger', $this->model);
    }

    public function actionSearch_loan() {
        $this->is_ajax_request();
        $_cid = Yii::app()->request->getPost('code_no');
        $_limit = Yii::app()->request->getPost('itemCount');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new LoanItem();
        $criteria = new CDbCriteria();
        $criteria->condition = "agent_code=:agcd";
        $criteria->params = array(':agcd' => $_cid);
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('created', $dateForm, $dateTo);
        }
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('_list_loan', $this->model);
    }

    public function actionSearch_advance_loan() {
        $this->is_ajax_request();
        $_cid = Yii::app()->request->getPost('code_no');
        $_limit = Yii::app()->request->getPost('itemCount');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new LoanPaymentAdvance();
        $criteria = new CDbCriteria();
        $criteria->condition = "agent_code=:agcd";
        $criteria->params = array(':agcd' => $_cid);
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('created', $dateForm, $dateTo);
        }
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('_list_adv_loan', $this->model);
    }

    public function actionDeleteall() {
        $this->is_ajax_request();
        $response = array();
        $_data = $_POST['data'];
        $_model = new Agent();

        if (isset($_data)) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < count($_data); $i++) {
                    $_obj = $_model->findByPk($_data[$i]);

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

    public function actionDeleteall_advance_loan() {
        $this->is_ajax_request();
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

}
