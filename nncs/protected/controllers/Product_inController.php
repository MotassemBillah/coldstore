<?php

class Product_inController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('entry_list');
        $this->setHeadTitle("Entry");
        $this->setPageTitle("Entry List");
        $this->setCurrentPage(AppUrl::URL_PRODUCT_IN);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/product/in.js');

        $_model = new ProductIn();
        $criteria = new CDbCriteria();
        $criteria->order = "sr_no ASC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->model['display_name'] = User::model()->displayname(Yii::app()->user->id);
        $this->model['userStock'] = AppObject::stockOfUser(Yii::app()->user->id);
        $this->render('index', $this->model);
    }

    public function actionCreate() {
        $this->checkUserAccess('entry_create');
        $this->setHeadTitle("Entry");
        $this->setPageTitle("New Entry");
        $this->setCurrentPage(AppUrl::URL_PRODUCT_IN);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/product/in_create.js');

        $_agent = new Agent();
        $_customer = new Customer();
        $_model = new ProductIn();

        $this->model['agent'] = $_agent;
        $this->model['customer'] = $_customer;
        $this->model['model'] = $_model;
        $this->model['srno'] = AppHelper::random_number(8);
        $this->render('_form', $this->model);
    }

    public function actionEdit($id) {
        $this->checkUserAccess('entry_edit');
        $this->setHeadTitle("Entry");
        $this->setPageTitle("Edit Entry");
        $this->setCurrentPage(AppUrl::URL_PRODUCT_IN);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new ProductIn();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

        if (isset($_POST['ProductIn'])) {
            $_answer = $_POST['answer'];

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if ($_answer == AppConstant::YES) {
                    $customerModel = new Customer();
                    $customerModel->name = $_POST['Customer']['name'];
                    $customerModel->father_name = $_POST['Customer']['father_name'];
                    $customerModel->mobile = $_POST['Customer']['mobile'];
                    $customerModel->district = $_POST['Customer']['district'];
                    $customerModel->thana = $_POST['Customer']['thana'];
                    $customerModel->village = $_POST['Customer']['village'];
                    $customerModel->create_date = AppHelper::getDbTimestamp();
                    $customerModel->created_by = Yii::app()->user->id;
                    $customerModel->_key = AppHelper::getUnqiueKey();
                    if (!$customerModel->validate()) {
                        throw new CException(Yii::t("App", CHtml::errorSummary($customerModel)));
                    }
                    if (!$customerModel->save()) {
                        throw new CException(Yii::t("App", "Error while saving customer data."));
                    }
                    $customerID = Yii::app()->db->getLastInsertId();
                } else {
                    $customerID = $_POST['Customer']['id'];
                    $customer = Customer::model()->findByPk($customerID);
                    $customer->name = $_POST['Customer']['name'];
                    $customer->father_name = $_POST['Customer']['father_name'];
                    $customer->mobile = $_POST['Customer']['mobile'];
                    $customer->district = $_POST['Customer']['district'];
                    $customer->thana = $_POST['Customer']['thana'];
                    $customer->village = $_POST['Customer']['village'];
                    $customer->last_update = AppHelper::getDbTimestamp();
                    $customer->update_by = Yii::app()->user->id;
                    if (!$customer->save()) {
                        throw new CException(Yii::t("App", "Error while updating customer data."));
                    }
                }

                $srno = $_POST['ProductIn']['sr_no'];
                $lotno = $_POST['ProductIn']['lot_no'];
                $_data->attributes = $_POST['ProductIn'];
                $_data->answer = $_answer;
                $_data->type = $_POST['ProductIn']['type'];
                $_data->customer_id = $customerID;
                $_data->customer_mobile = ($_answer == AppConstant::YES) ? $customerModel->mobile : $customer->mobile;
                $_data->sr_no = !empty($srno) ? $srno : AppHelper::random_number(4);
                $_data->quantity = $_POST['ProductIn']['quantity'];
                $_data->lot_no = !empty($lotno) ? $lotno : "{$_data->sr_no}/{$_data->quantity}";
                $_data->agent_code = !empty($_POST['ProductIn']['agent_code']) ? $_POST['ProductIn']['agent_code'] : 0;
                $_data->loan_pack = $_POST['ProductIn']['loan_pack'];
                $_data->carrying_cost = $_POST['ProductIn']['carrying_cost'];
                $_data->advance_booking_no = $_POST['ProductIn']['advance_booking_no'];
                $_data->create_date = date('Y-m-d', strtotime($_POST['ProductIn']['create_date']));
                $_data->modified = AppHelper::getDbTimestamp();
                $_data->modified_by = Yii::app()->user->id;
                if (!$_data->validate()) {
                    throw new CException(Yii::t("App", CHtml::errorSummary($_data)));
                }
                if (!$_data->save()) {
                    throw new CException(Yii::t("App", "Error while saving data."));
                }

                $_modelCashAccount = new CashAccount();
                $_dataCash = CashAccount::model()->find('product_in_payment_id=:pinpid', array(':pinpid' => $_data->id));
                if (!empty($_dataCash)) {
                    $_dataCash->ledger_head_id = AppConstant::HEAD_CARRYING;
                    $_dataCash->by_whom = User::model()->displayname(Yii::app()->user->id);
                    $_dataCash->credit = $_data->carrying_cost;
                    $_dataCash->balance = -($_dataCash->credit);
                    $_dataCash->modified = AppHelper::getDbTimestamp();
                    $_dataCash->modified_by = Yii::app()->user->id;
                    if (!$_dataCash->save()) {
                        throw new CException(Yii::t("App", "Error while saving transaction."));
                    }
                } else {
                    $_modelCashAccount->product_in_payment_id = $_data->id;
                    $_modelCashAccount->ledger_head_id = AppConstant::HEAD_CARRYING;
                    $_modelCashAccount->pay_date = $_data->create_date;
                    $_modelCashAccount->purpose = 'Carrying paid to ' . Customer::model()->findByPk($_data->customer_id)->name;
                    $_modelCashAccount->by_whom = User::model()->displayname(Yii::app()->user->id);
                    $_modelCashAccount->credit = $_data->carrying_cost;
                    $_modelCashAccount->balance = -($_modelCashAccount->credit);
                    $_modelCashAccount->type = 'W';
                    $_modelCashAccount->created = $_data->create_date;
                    $_modelCashAccount->created_by = Yii::app()->user->id;
                    $_modelCashAccount->_key = AppHelper::getUnqiueKey();
                    if (!$_modelCashAccount->save()) {
                        throw new CException(Yii::t("App", "Error while saving transaction."));
                    }
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "Record update successfull.");
                $this->redirect(array(AppUrl::URL_PRODUCT_IN));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $_agent = Agent::model()->find('code=:code', [':code' => $_data->agent_code]);
        $_customer = Customer::model()->findByPk($_data->customer_id);

        $this->model['agent'] = !empty($_agent) ? $_agent : '';
        $this->model['customer'] = $_customer;
        $this->model['model'] = $_data;
        $this->render('_form', $this->model);
    }

    public function actionView($id) {
        $this->checkUserAccess('entry_view');
        $this->setHeadTitle("Entry");
        $this->setPageTitle("Entry View");
        $this->setCurrentPage(AppUrl::URL_PRODUCT_IN);

        $_model = new ProductIn();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

        if (empty($_data)) {
            Yii::app()->user->setFlash("warning", "You are trying to access an invalid url.");
            $this->redirect(Yii::app()->createUrl(AppUrl::URL_PRODUCT_IN));
        }

        $_modelOut = new ProductOut();
        $_dataset = $_modelOut->findAll('LOWER(sr_no) = ?', array(strtolower($_data->sr_no)));

        $_modelStockLocation = new StockLocation();
        $criteria = new CDbCriteria();
        $criteria->order = "id DESC";
        $criteria->limit = 1;
        $stock_location = $_modelStockLocation->find('stock_srno=:srno', [':srno' => $_data->sr_no]);

        $this->model['model'] = $_data;
        $this->model['agent'] = !empty($_data->agent_code) ? Agent::model()->find('code=:code', [':code' => $_data->agent_code]) : '';
        $this->model['customer'] = Customer::model()->findByPk($_data->customer_id);
        $this->model['dataset'] = $_dataset;
        $this->model['location'] = $stock_location;
        $this->render('view', $this->model);
    }

    public function actionInvoice($id) {
        $this->checkUserAccess('entry_invoice');
        $this->setHeadTitle("Entry");
        $this->setPageTitle("Entry Invoice");
        $this->setCurrentPage(AppUrl::URL_PRODUCT_IN);

        $_model = new ProductIn();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

        $this->model['model'] = $_data;
        $this->model['customer'] = Customer::model()->findByPk($_data->customer_id);
        $this->render('invoice', $this->model);
    }

    public function actionDuplicate() {
        $this->checkUserAccess('entry_list');
        $this->setHeadTitle("Entry");
        $this->setPageTitle("Entry List");
        $this->setCurrentPage(AppUrl::URL_PRODUCT_IN);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/product/in.js');

        $sql = "SELECT * FROM product_in a INNER JOIN product_in b ON a.customer_id = b.customer_id WHERE a.id <> b.id ORDER BY a.sr_no ASC";
        $count_query = "select count(*) FROM product_in t1 INNER JOIN product_in t2  ON t1.customer_id = t2.customer_id WHERE t1.id <> t2.id";
        $item_count = Yii::app()->db->createCommand($count_query)->queryScalar();

        $dataProvider = new CSqlDataProvider($sql, array(
            'keyField' => 'id',
            'totalItemCount' => $item_count,
            'pagination' => array(
                'pageSize' => $this->page_size,
            ),
        ));

        $_dataset = $dataProvider->getData();

        $this->model['dataProvider'] = $dataProvider;
        $this->model['dataset'] = $_dataset;
        $this->render('duplicate', $this->model);
    }

    /*
     * Ajax search and other responses
     */

    public function actionSave() {
        $this->is_ajax_request();
        $_transaction = Yii::app()->db->beginTransaction();
        try {
            $_customer = new Customer();
            $_customer->attributes = $_POST['Customer'];
            $_customer->name = $_POST['Customer']['name'];
            $_customer->father_name = $_POST['Customer']['father_name'];
            $_customer->district = $_POST['Customer']['district'];
            $_customer->thana = isset($_POST['Customer']['thana']) ? $_POST['Customer']['thana'] : '';
            $_customer->village = $_POST['Customer']['village'];
            $_customer->mobile = $_POST['Customer']['mobile'];
            $_customer->create_date = AppHelper::getDbTimestamp();
            $_customer->created_by = Yii::app()->user->id;
            $_customer->_key = AppHelper::getUnqiueKey();
            if (!$_customer->validate()) {
                throw new CException(Yii::t("App", CHtml::errorSummary($_customer)));
            }
            if (!$_customer->save()) {
                throw new CException(Yii::t("App", "Error while saving customer data."));
            }
            $customerID = Yii::app()->db->getLastInsertId();

            $srno = $_POST['ProductIn']['sr_no'];
            $lotno = $_POST['ProductIn']['lot_no'];
            $_date = $_POST['ProductIn']['create_date'];
            $_model = new ProductIn();
            $_model->attributes = $_POST['ProductIn'];
            $_model->type = $_POST['ProductIn']['type'];
            $_model->customer_id = $customerID;
            $_model->customer_mobile = Customer::model()->findByPk($customerID)->mobile;
            $_model->sr_no = !empty($srno) ? $srno : AppHelper::random_number(4);
            $_model->quantity = $_POST['ProductIn']['quantity'];
            $_model->lot_no = !empty($lotno) ? $lotno : "{$_model->sr_no}/{$_model->quantity}";
            $_model->agent_code = !empty($_POST['ProductIn']['agent_code']) ? $_POST['ProductIn']['agent_code'] : 0;
            $_model->loan_pack = $_POST['ProductIn']['loan_pack'];
            $_model->carrying_cost = $_POST['ProductIn']['carrying_cost'];
            $_model->advance_booking_no = $_POST['ProductIn']['advance_booking_no'];
            $_model->create_date = !empty($_date) ? date('Y-m-d', strtotime($_date)) : AppHelper::getDbDate();
            $_model->created_by = Yii::app()->user->id;
            $_model->_key = AppHelper::getUnqiueKey();
            if (!$_model->validate()) {
                throw new CException(Yii::t("App", CHtml::errorSummary($_model)));
            }
            if (!$_model->save()) {
                throw new CException(Yii::t("App", "Error while saving data."));
            }

            $productInId = Yii::app()->db->getLastInsertId();
            $_modelCashAccount = new CashAccount();
            $_modelCashAccount->product_in_payment_id = $productInId;
            $_modelCashAccount->ledger_head_id = AppConstant::HEAD_CARRYING;
            $_modelCashAccount->pay_date = $_model->create_date;
            $_modelCashAccount->purpose = 'Carrying paid to ' . Customer::model()->findByPk($customerID)->name;
            $_modelCashAccount->by_whom = User::model()->displayname(Yii::app()->user->id);
            $_modelCashAccount->credit = !empty($_model->carrying_cost) ? $_model->carrying_cost : NULL;
            $_modelCashAccount->balance = !empty($_modelCashAccount->credit) ? -($_modelCashAccount->credit) : NULL;
            $_modelCashAccount->type = 'W';
            $_modelCashAccount->created = $_model->create_date;
            $_modelCashAccount->created_by = Yii::app()->user->id;
            $_modelCashAccount->_key = AppHelper::getUnqiueKey();
            if (!$_modelCashAccount->save()) {
                throw new CException(Yii::t("App", "Error while saving transaction."));
            }

            $_transaction->commit();
            $this->resp['success'] = true;
            $this->resp['message'] = "New record save successfull.";
            $this->resp['skey'] = $_model->_key;
        } catch (CException $e) {
            $_transaction->rollback();
            $this->resp['success'] = false;
            $this->resp['message'] = $e->getMessage();
        }

        echo json_encode($this->resp);
        return json_encode($this->resp);
    }

    public function actionSearch() {
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
        $_officeCode = Yii::app()->request->getPost('office_code');

        $_model = new ProductIn();
        $criteria = new CDbCriteria();
        if (!empty($_userID)) {
            $criteria->addCondition("created_by=" . $_userID);
            $this->model['userStock'] = AppObject::stockOfUser($_userID);
        } else {
            $this->model['userStock'] = AppObject::stockOfUser(Yii::app()->user->id);
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
            $criteria->addCondition("type=" . $_typeID);
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('create_date', $dateForm, $dateTo);
        }
        if (!empty($_srno)) {
            $criteria->addCondition("sr_no={$_srno}");
        }
        if (!empty($_agent)) {
            $criteria->addCondition("agent_code={$_agent}");
            $this->model['agentTotal'] = $_model->agentStock($_agent);
        } else {
            $this->model['agentTotal'] = '';
        }
        $this->model['officeStock'] = '';
        if (isset($_officeCode)) {
            $criteria->addCondition("agent_code IS NULL OR agent_code=0");
            $this->model['officeStock'] = $_model->sumTotalOfice();
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

        if (!empty($_userID)) {
            $display_name = User::model()->displayname($_userID);
        } else {
            $display_name = User::model()->displayname(Yii::app()->user->id);
        }

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->model['display_name'] = $display_name;
        $this->renderPartial('_list', $this->model);
    }

    public function actionDeleteall() {
        $this->is_ajax_request();
        $response = array();
        $_data = $_POST['data'];
        $_model = new ProductIn();

        if (isset($_data)) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {

                for ($i = 0; $i < count($_data); $i++) {
                    $_obj = $_model->findByPk($_data[$i]);

                    if (!empty($_obj->payment)) {
                        if (!$_obj->payment->delete()) {
                            throw new CException(Yii::t('App', "Error while deleting carrying payment record."));
                        }
                    }

                    $loanItem = LoanItem::model()->find("sr_no=:srno", [':srno' => $_obj->sr_no]);
                    if (!empty($loanItem)) {
                        $loan = Loan::model()->findByPk($loanItem->loan_id);

                        $_cashDataLoanItem = CashAccount::model()->find("loan_payment_id=:lpid", [":lpid" => $loanItem->id]);
                        if (!empty($_cashDataLoanItem)) {
                            if (!$_cashDataLoanItem->delete()) {
                                throw new CException(Yii::t('App', "Error while deleting loan payment cash record."));
                            }
                        }

                        if (!$loanItem->delete()) {
                            throw new CException(Yii::t("App", "Error while deleting loan item."));
                        }

                        if (!$loan->delete()) {
                            throw new CException(Yii::t("App", "Error while deleting loan."));
                        }
                    }

                    $loanReceives = LoanReceive::model()->findall("sr_no=:srno", [':srno' => $_obj->sr_no]);
                    if (!empty($loanReceives)) {
                        foreach ($loanReceives as $loanReceive) {
                            if (!empty($loanReceive->items)) {
                                foreach ($loanReceive->items as $lritem) {
                                    $_cashDatalr = CashAccount::model()->find("loan_receive_id=:lrid", [":lrid" => $lritem->id]);
                                    if (!empty($_cashDatalr)) {
                                        if (!$_cashDatalr->delete()) {
                                            throw new CException(Yii::t('App', "Error while deleting loan received cash record."));
                                        }
                                    }

                                    if (!$lritem->delete()) {
                                        throw new CException(Yii::t("App", "Error while deleting loan receive item/s."));
                                    }
                                }
                            }

                            if (!$loanReceive->delete()) {
                                throw new CException(Yii::t("App", "Error while deleting loan receive."));
                            }
                        }
                    }

                    $deliveries = Delivery::model()->findall("sr_no=:srno", [':srno' => $_obj->sr_no]);
                    if (!empty($deliveries)) {
                        foreach ($deliveries as $delivery) {
                            if (!empty($delivery->items)) {
                                foreach ($delivery->items as $ditem) {
                                    $_cashData = CashAccount::model()->find("product_out_payment_id=:popid", [":popid" => $ditem->id]);
                                    if (!empty($_cashData)) {
                                        if (!$_cashData->delete()) {
                                            throw new CException(Yii::t('App', "Error while deleting delivery cash record."));
                                        }
                                    }

                                    if (!$ditem->delete()) {
                                        throw new CException(Yii::t("App", "Error while deleting delivery item/s."));
                                    }
                                }
                            }

                            if (!$delivery->delete()) {
                                throw new CException(Yii::t("App", "Error while deleting delivery."));
                            }
                        }
                    }

                    if (!$_obj->delete()) {
                        throw new CException(Yii::t('App', "Error while deleting record."));
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
