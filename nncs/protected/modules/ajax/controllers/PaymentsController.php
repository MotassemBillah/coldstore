<?php

class PaymentsController extends AppController {

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
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
		$_customer_name = Yii::app()->request->getPost('customer_name');
        $_srno = Yii::app()->request->getPost('srno');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new ProductIn();
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
            $criteria->addBetweenCondition('create_date', $dateForm, $dateTo);
        }
        if (!empty($_srno)) {
            $criteria->addCondition("sr_no={$_srno}");
        }
        $criteria->order = "create_date DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('index', $this->model);
    }

    public function actionList_loading() {
        $_limit = Yii::app()->request->getPost('itemCount');
        $_userID = Yii::app()->request->getPost('user');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $_search = Yii::app()->request->getPost('q');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new PaymentLoading();
        $criteria = new CDbCriteria();
        $criteria->condition = "type='Loading'";
        if (Yii::app()->user->role != AppConstant::ROLE_SUPERADMIN) {
            $criteria->addCondition("created_by =" . Yii::app()->user->id);
        }
        if (!empty($_userID)) {
            $criteria->addCondition("created_by =" . $_userID);
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('created', $dateForm, $dateTo);
        }
        if (!empty($_search)) {
            $criteria->addCondition("sr_no =" . $_search);
        }
        $criteria->order = "id DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('loading_list', $this->model);
    }

    public function actionList_unloading() {
        $_limit = Yii::app()->request->getPost('itemCount');
        $_userID = Yii::app()->request->getPost('user');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $_search = Yii::app()->request->getPost('q');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new PaymentLoading();
        $criteria = new CDbCriteria();
        $criteria->condition = "type='Unloading'";
        if (Yii::app()->user->role != AppConstant::ROLE_SUPERADMIN) {
            $criteria->addCondition("created_by =" . Yii::app()->user->id);
        }
        if (!empty($_userID)) {
            $criteria->addCondition("created_by =" . $_userID);
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('created', $dateForm, $dateTo);
        }
        if (!empty($_search)) {
            $criteria->addCondition("sr_no =" . $_search);
        }
        $criteria->order = "id DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('loading_list', $this->model);
    }

    public function actionList_pallot() {
        $_limit = Yii::app()->request->getPost('itemCount');
        $_userID = Yii::app()->request->getPost('user');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $_search = Yii::app()->request->getPost('q');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new PaymentLoading();
        $criteria = new CDbCriteria();
        $criteria->condition = "type='Pallot'";
        if (Yii::app()->user->role != AppConstant::ROLE_SUPERADMIN) {
            $criteria->addCondition("created_by =" . Yii::app()->user->id);
        }
        if (!empty($_userID)) {
            $criteria->addCondition("created_by =" . $_userID);
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('created', $dateForm, $dateTo);
        }
        if (!empty($_search)) {
            $criteria->addCondition("sr_no =" . $_search);
        }
        $criteria->order = "id DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('pallot_list', $this->model);
    }

    public function actionDeleteall() {
        $_model = new Payment();
        $response = array();
        $_data = $_POST['data'];

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

    public function actionDeleteall_loading() {
        $_model = new PaymentLoading();
        $response = array();
        $_data = $_POST['data'];

        if (isset($_data)) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < count($_data); $i++) {
                    $_obj = $_model->findByPk($_data[$i]);

                    $_cash_account = CashAccount::model()->find('payment_load_unload_id=:lupid', [':lupid' => $_obj->id]);
                    if (!empty($_cash_account)) {
                        if (!$_cash_account->delete()) {
                            throw new CException(Yii::t('App', "Error while deleting transaction"));
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

    public function actionGet_info() {
        $_payment_type = $_POST['CustomerPayment']['payment_type'];
        $_customer = $_POST['CustomerPayment']['customer_id'];
        $_srno = $_POST['CustomerPayment']['sr_no'];

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            if ($_payment_type == AppConstant::TYPE_DUE_PAYMENT) {
                $_model = new Payment();
            } else if ($_payment_type == AppConstant::TYPE_DELIVERY_PAYMENT) {
                $_model = new ProductOut();
            } else {
                $_model = new CustomerPayment();
            }

            $criteria = new CDbCriteria();
            $criteria->condition = "customer_id=:cid";
            $criteria->params = array(":cid" => $_customer);
            $criteria->addCondition("sr_no='" . $_srno . "'");

            if ($_payment_type !== AppConstant::TYPE_DELIVERY_PAYMENT) {
                $criteria->addCondition("payment_type='" . AppConstant::TYPE_DUE_PAYMENT . "'");
                $criteria->addCondition("status='" . AppConstant::ORDER_PENDING . "'");
            }
            //$_data = $this->_get_data($_model, $_customer, $_srno);
            $_data = $_model->find($criteria);
            if (empty($_data)) {
                throw new CException(Yii::t("App", "No data found!."));
            }

            if ($_payment_type == AppConstant::TYPE_DUE_PAYMENT) {
                $this->resp['pay_info_id'] = $_data->id;
                $this->resp['due_cost'] = $_data->due_amount;
            } else if ($_payment_type == AppConstant::TYPE_DELIVERY_PAYMENT) {
                $this->resp['lpack'] = $_data->loan_pack;
                $this->resp['delv_qty'] = $_data->quantity;
            }

            $this->resp['success'] = true;
            $this->resp['message'] = "Successfull.";
        } catch (CException $e) {
            $_transaction->rollback();
            $this->resp['success'] = false;
            $this->resp['message'] = $e->getMessage();
        }

        echo json_encode($this->resp);
        return json_encode($this->resp);
    }

    protected function _get_data($_model, $_customer, $_srno) {
        $criteria = new CDbCriteria();
        $criteria->condition = "customer_id=:cid";
        $criteria->params = array(":cid" => $_customer);
        $criteria->addCondition("sr_no='" . $_srno . "'");
        $criteria->addCondition("payment_type='" . AppConstant::TYPE_DUE_PAYMENT . "'");
        $criteria->addCondition("status='" . AppConstant::ORDER_PENDING . "'");
        $_obj = $_model->find($criteria);
        return $_obj;
    }

}
