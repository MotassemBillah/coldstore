<?php

class AccountController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('account_list');
        $this->setHeadTitle("Accounts");
        $this->setPageTitle("Account List");
        $this->setCurrentPage(AppUrl::URL_ACCOUNT);
        $this->addJs('views/account/list.js');

        $_model = new Account();
        $criteria = new CDbCriteria();
        $criteria->order = "account_name ASC";
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
        $this->checkUserAccess('account_create');
        $this->setHeadTitle("Accounts");
        $this->setPageTitle("New Account");
        $this->setCurrentPage(AppUrl::URL_ACCOUNT);

        $_model = new Account();

        if (isset($_POST['Account'])) {
            $_model->attributes = $_POST['Account'];
            $_model->bank_id = $_POST['Account']['bank_id'];
            $_model->account_name = $_POST['Account']['account_name'];
            $_model->account_number = $_POST['Account']['account_number'];
            $_model->account_type = $_POST['Account']['account_type'];
            $_model->address = AppHelper::capFirstWord($_POST['Account']['address']);
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
                $this->redirect(array(AppUrl::URL_ACCOUNT));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_model;
        $this->render('create', $this->model);
    }

    public function actionEdit($id) {
        $this->checkUserAccess('account_edit');
        $this->setHeadTitle("Accounts");
        $this->setPageTitle("Edit Account");
        $this->setCurrentPage(AppUrl::URL_ACCOUNT);

        $_model = new Account();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

        if (isset($_POST['Account'])) {
            $_data->attributes = $_POST['Account'];
            $_data->bank_id = $_POST['Account']['bank_id'];
            $_data->account_name = $_POST['Account']['account_name'];
            $_data->account_number = $_POST['Account']['account_number'];
            $_data->account_type = $_POST['Account']['account_type'];
            $_data->address = AppHelper::capFirstWord($_POST['Account']['address']);
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
                Yii::app()->user->setFlash("success", "Record update successfull!");
                $this->redirect(array(AppUrl::URL_ACCOUNT));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_data;
        $this->render('create', $this->model);
    }

    public function actionBalance($id) {
        $this->checkUserAccess('account_balance');
        $this->setHeadTitle("Account");
        $this->setPageTitle("Account Balance");
        $this->setCurrentPage(AppUrl::URL_ACCOUNT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/account/balance.js');

        $_model = new AccountBalance();
        $criteria = new CDbCriteria();
        $criteria->condition = "account_id=$id";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['account'] = Account::model()->findByPk($id);
        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('balance', $this->model);
    }

    /* Search and other ajax calls */

    public function actionSearch() {
        $this->is_ajax_request();
        $_limit = Yii::app()->request->getPost('itemCount');
        $_q = Yii::app()->request->getPost('q');

        $_model = new Account();
        $criteria = new CDbCriteria();
        if (!empty($_q)) {
            $criteria->condition = "account_name like '%" . $_q . "%'";
        }
        $criteria->order = "account_name ASC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('_list', $this->model);
    }

    public function actionSearch_balance() {
        $accountID = Yii::app()->request->getPost('accountID');
        $_limit = Yii::app()->request->getPost('itemCount');
        $_category = Yii::app()->request->getPost('type');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new AccountBalance();
        $criteria = new CDbCriteria();
        $criteria->condition = "account_id=:accid";
        $criteria->params = array(":accid" => $accountID);
        if ($_category !== "All") {
            $criteria->addCondition("category='$_category'");
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('last_transaction_time', $dateForm, $dateTo);
        }
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $accBalance = '';
        if ($_category == AppConstant::CASH_IN) {
            $accBalance = AppObject::sumCashIn($accountID);
        } else if ($_category == AppConstant::CASH_OUT) {
            $accBalance = AppObject::sumCashOut($accountID);
        } else {
            $accBalance = AppObject::sumCashBalance($accountID);
        }

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->model['account'] = Account::model()->findByPk($accountID);
        $this->model['totalAmount'] = $accBalance;
        $this->renderPartial('_balance_list', $this->model);
    }

    public function actionDeleteall() {
        $this->is_ajax_request();
        $response = array();
        $_data = $_POST['data'];
        $_model = new Account();

        if (isset($_data)) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < count($_data); $i++) {
                    $_obj = $_model->with('balance')->findByPk($_data[$i]);

                    if (!empty($_obj->balance)) {
                        foreach ($_obj->balance as $balance) {
                            if (!$balance->delete()) {
                                throw new CException(Yii::t("App", "Error while deleting account balance data."));
                            }
                        }
                    }

                    if (!$_obj->delete()) {
                        throw new CException(Yii::t("App", "Error while deleting data."));
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

    /* Protected function for check balance */

    protected function checkBalance($accountID, $amount) {
        $balance = AppObject::sumCashBalance($accountID);
        if ($amount > $balance) {
            throw new CException(Yii::t("App", "Not enough balance to withdraw."));
        }
    }

}
