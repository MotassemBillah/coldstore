<?php

class CustomerController extends AppController {

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
        $this->renderPartial('index', $this->model);
    }

    public function actionLoan() {
        $_cid = Yii::app()->request->getPost('customer_id');
        $_limit = Yii::app()->request->getPost('itemCount');
        $_sort = Yii::app()->request->getPost('loanType');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new LoanItem();
        $criteria = new CDbCriteria();
        $criteria->condition = "customer_id=:cid";
        $criteria->params = array(':cid' => $_cid);
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('created', $dateForm, $dateTo);
        }
        //$criteria->order = "case_no DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('loan_list', $this->model);
    }

    public function actionLoan_adv_list() {
        $_cid = Yii::app()->request->getPost('customer_id');
        $_limit = Yii::app()->request->getPost('itemCount');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new LoanPaymentAdvance();
        $criteria = new CDbCriteria();
        $criteria->condition = "customer_id=:cid";
        $criteria->params = array(':cid' => $_cid);
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
        $this->renderPartial('adv_loan_list', $this->model);
    }

    public function actionPayment() {
        $_limit = Yii::app()->request->getPost('itemCount');
        $customerID = Yii::app()->request->getPost('customer');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");
        $_srno = Yii::app()->request->getPost('srno');
        $_customer_name = Yii::app()->request->getPost('customer_name');

        $_model = new CustomerPayment();
        $criteria = new CDbCriteria();
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
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('created', $dateForm, $dateTo);
        }
        if (!empty($_srno)) {
            $criteria->addCondition("sr_no={$_srno}");
        }
        $criteria->order = "created DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('payment', $this->model);
    }

    public function actionDeleteall() {
        $_model = new Customer();
        $response = array();
        $_data = $_POST['data'];

        if (isset($_data)) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < count($_data); $i++) {
                    $_obj = $_model->with('entries', 'deliveries', 'payments', 'stocks')->findByPk($_data[$i]);

                    if (!empty($_obj->entries)) {
                        foreach ($_obj->entries as $pin) {
                            if (!$pin->delete()) {
                                throw new CException(Yii::t('App', "Error while deleting record {product entry}"));
                            }
                        }
                    }

                    if (!empty($_obj->deliveries)) {
                        foreach ($_obj->deliveries as $pout) {
                            if (!$pout->delete()) {
                                throw new CException(Yii::t('App', "Error while deleting record {product delivery}"));
                            }
                        }
                    }

                    if (!empty($_obj->stocks)) {
                        foreach ($_obj->stocks as $stock) {
                            if (!$stock->delete()) {
                                throw new CException(Yii::t('App', "Error while deleting record {stocks}"));
                            }
                        }
                    }

                    if (!empty($_obj->payments)) {
                        foreach ($_obj->payments as $payment) {
                            if (!$payment->delete()) {
                                throw new CException(Yii::t('App', "Error while deleting record {payments}"));
                            }
                        }
                    }

                    if (!empty($_obj->loans)) {
                        foreach ($_obj->loans as $loan) {
                            if (!$loan->delete()) {
                                throw new CException(Yii::t('App', "Error while deleting record {loans}"));
                            }
                        }
                    }

                    if (!empty($_obj->loans_pending)) {
                        foreach ($_obj->loans_pending as $loan_pending) {
                            if (!$loan_pending->delete()) {
                                throw new CException(Yii::t('App', "Error while deleting record {loan pending}"));
                            }
                        }
                    }

                    if (!empty($_obj->loans_receives)) {
                        foreach ($_obj->loans_receives as $loan_receive) {
                            if (!$loan_receive->delete()) {
                                throw new CException(Yii::t('App', "Error while deleting record {loan receive}"));
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

    public function actionDeleteall_payment() {
        $_model = new CustomerPayment();
        $response = array();
        $_data = $_POST['data'];

        if (isset($_data)) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < count($_data); $i++) {
                    $_obj = $_model->findByPk($_data[$i]);

                    $modelBalanceSheet = new Balancesheet();
                    $balanceSheet = $modelBalanceSheet->find("customer_payment_id=:customer_payment_id", array(":customer_payment_id" => $_obj->id));
                    if (!empty($balanceSheet)) {
                        if (!$balanceSheet->delete()) {
                            throw new CException(Yii::t("App", "Error while saving leger record."));
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

    public function actionLoan_form() {
        $_customerID = Yii::app()->request->getPost('cid');
        $_srno = Yii::app()->request->getPost('sr_number');
        $_agent = Yii::app()->request->getPost('agent_code');
        //AppHelper::pr($_POST);

        $_model = new ProductIn();
        $criteria = new CDbCriteria();
        $criteria->condition = "customer_id={$_customerID}";
        if (!empty($_srno)) {
            $criteria->addCondition("sr_no =" . $_srno);
        }
        if (!empty($_agent)) {
            $criteria->addCondition("agent_code =" . $_agent);
        }
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->renderPartial('loan_form', $this->model);
    }

    /* Search Customer By Name */

    public function actionSearch() {
        $_search = Yii::app()->request->getPost('customer_mo');
        $retVal = array();
        $_model = new Customer();
        $criteria = new CDbCriteria();
        $criteria->select = "id, name, father_name, village";
        $criteria->condition = "name LIKE :match OR mobile LIKE :match";
        $criteria->params = array(':match' => "%$_search%");
        $criteria->order = "name ASC";
        $_dataset = $_model->findAll($criteria);

        $retVal['html'] = "";

        if (!empty($_dataset)) {
            $retVal['success'] = true;
            foreach ($_dataset as $_data) {
                $retVal['html'] .= "<option value='$_data->id'>{$_data->name} (F:{$_data->father_name} V:{$_data->village})</option>";
            }
        } else {
            $retVal['success'] = false;
            $retVal['html'] = '';
        }

        echo json_encode($retVal);
        return json_encode($retVal);
    }

}
