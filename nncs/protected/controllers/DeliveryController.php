<?php

class DeliveryController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('delivery_list');
        $this->setHeadTitle("Delivery");
        $this->setPageTitle("Delivery Report");
        $this->setCurrentPage(AppUrl::URL_DELIVERY);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new DeliveryItem();
        $criteria = new CDbCriteria();
        $criteria->order = "delivery_date ASC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('report_detail', $this->model);
    }

    public function actionList() {
        $this->checkUserAccess('delivery_list');
        $this->setHeadTitle("Delivery");
        $this->setPageTitle("Delivery List");
        $this->setCurrentPage(AppUrl::URL_DELIVERY);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/product/delivery.js');

        $_model = new Delivery();
        $criteria = new CDbCriteria();
        $criteria->order = "delivery_date DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('index', $this->model);
    }

    public function actionItem_list() {
        $this->checkUserAccess('delivery_list');
        $this->setHeadTitle("Delivery");
        $this->setPageTitle("Delivery List");
        $this->setCurrentPage(AppUrl::URL_DELIVERY);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new DeliveryItem();
        $criteria = new CDbCriteria();
        $criteria->order = "delivery_date DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('item_list', $this->model);
    }

    public function actionCreate() {
        $this->checkUserAccess('delivery_create');
        $this->setHeadTitle("Delivery");
        $this->setPageTitle("New Delivery");
        $this->setCurrentPage(AppUrl::URL_DELIVERY);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new Delivery();

        $this->model['model'] = $_model;
        $this->render('_form', $this->model);
    }

    public function actionEdit($id) {
        $this->checkUserAccess('delivery_edit');
        $this->setHeadTitle("Delivery");
        $this->setPageTitle("Edit Delivery");
        $this->setCurrentPage(AppUrl::URL_DELIVERY);

        $_model = new ProductOut('update');
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

        if (isset($_POST['ProductOut'])) {
            $srno = $_POST['ProductOut']['sr_no'];
            $delivery_sr_no = $_POST['ProductOut']['delivery_sr_no'];
            $lotno = $_POST['ProductOut']['lot_no'];
            $_data->attributes = $_POST['ProductOut'];
            $_data->customer_id = $_POST['ProductOut']['customer_id'];
            $_data->customer_mobile = Customer::model()->findByPk($_data->customer_id)->mobile;
            $_data->sr_no = !empty($srno) ? $srno : AppHelper::random_number(4);
            $_data->delivery_sr_no = !empty($delivery_sr_no) ? $delivery_sr_no : AppHelper::random_number(4);
            $_data->quantity = $_POST['ProductOut']['quantity'];
            $_data->loan_pack = $_POST['ProductOut']['loan_pack'];
            $_data->lot_no = !empty($lotno) ? $lotno : "{$_data->sr_no}/{$_data->quantity}";
            $_data->agent_code = $_POST['ProductOut']['agent_code'];
            $_data->advance_booking_no = $_POST['ProductOut']['advance_booking_no'];
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

                /* $_modelCashAccount = new CashAccount();
                  $_dataCash = CashAccount::model()->find('product_out_payment_id=:popid', array(':popid' => $_data->id));
                  if (!empty($_dataCash)) {
                  $_dataCash->debit = $net_paid_amount;
                  $_dataCash->balance = $_dataCash->debit;
                  $_dataCash->type = 'D';
                  $_dataCash->modified = AppHelper::getDbTimestamp();
                  $_dataCash->modified_by = Yii::app()->user->id;
                  if (!$_dataCash->save()) {
                  throw new CException(Yii::t("App", "Error while saving transaction."));
                  }
                  } else {
                  $_modelCashAccount->product_out_payment_id = $_data->id;
                  $_modelCashAccount->purpose = 'Cash received from ' . Customer::model()->findByPk($_data->customer_id)->name . ' for product delivery';
                  $_modelCashAccount->debit = $net_paid_amount;
                  $_modelCashAccount->balance = $_modelCashAccount->debit;
                  $_modelCashAccount->type = 'D';
                  $_modelCashAccount->created = AppHelper::getDbTimestamp();
                  $_modelCashAccount->created_by = Yii::app()->user->id;
                  $_modelCashAccount->_key = AppHelper::getUnqiueKey();
                  if (!$_modelCashAccount->save()) {
                  throw new CException(Yii::t("App", "Error while saving transaction."));
                  }
                  } */

                $stock = Stock::model()->find("product_out_id=:product", array(":product" => $_data->id));
                $stock->customer_id = $_data->customer_id;
                $stock->agent_code = $_data->agent_code;
                $stock->qty_out = $_data->quantity;
                $stock->qty_total = -($stock->qty_out);
                $stock->lp_taken = $_data->loan_pack;
                $stock->lp_due = -($stock->lp_taken);
                if (!$stock->save()) {
                    throw new CException(Yii::t("App", "Error while updating stock data."));
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "Record update successfull.");
                $this->redirect(array(AppUrl::URL_PRODUCT_OUT));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_data;
        $this->model['loanPack'] = ProductIn::model()->getObj($_data->sr_no)->loan_pack;
        $this->render('edit', $this->model);
    }

    public function actionView($id) {
        $this->checkUserAccess('delivery_view');
        $this->setHeadTitle("Delivery");
        $this->setPageTitle("Delivery View");
        $this->setCurrentPage(AppUrl::URL_DELIVERY);

        $_model = new Delivery();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

        if (empty($_data)) {
            Yii::app()->user->setFlash("warning", "You are trying to access an invalid url.");
            $this->redirect(Yii::app()->createUrl(AppUrl::URL_DELIVERY));
        }

        $_item = $_data->items[0];
        $_srInfo = ProductIn::model()->find("sr_no=:sr", [":sr" => $_item->sr_no]);
        $_loanReceived = LoanReceiveItem::model()->find("delivery_number=:dn", [":dn" => $_data->delivery_number]);

        $this->model['model'] = $_data;
        $this->model['srinfo'] = $_srInfo;
        $this->model['item'] = $_item;
        $this->model['loanItem'] = $_loanReceived;
        $this->render('detail', $this->model);
    }

    public function actionSingle_edit($id) {
        $this->checkUserAccess('delivery_edit');
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
        $this->checkUserAccess('delivery_view');
        $this->setHeadTitle("Delivery");
        $this->setPageTitle("Delivery Invoice");
        $this->setCurrentPage(AppUrl::URL_DELIVERY);

        if (empty($id)) {
            Yii::app()->user->setFlash("warning", "You are trying to access an invalid url.");
            $this->redirect(Yii::app()->createUrl(AppUrl::URL_DELIVERY));
        }

        $_model = new DeliveryItem();
        $criteria = new CDbCriteria();
        $criteria->condition = "sr_no=:sr";
        $criteria->params = [':sr' => $id];
        $criteria->order = "delivery_date ASC";
        $deliveryItems = $_model->findAll($criteria);
        $loanReceiveItems = LoanReceiveItem::model()->findAll('sr_no=:sr', [':sr' => $id]);

        $this->model['deliveryItems'] = $deliveryItems;
        $this->model['loanReceiveItems'] = $loanReceiveItems;
        $this->model['loanSetting'] = LoanSetting ::model()->findByPk(1);
        $this->render('single_view', $this->model);
    }

    public function actionInvoice($id) {
        $this->checkUserAccess('entry_edit');
        $this->setHeadTitle("Delivery");
        $this->setPageTitle("Delivery Invoice");
        $this->setCurrentPage(AppUrl::URL_DELIVERY);

        $_model = new ProductOut();
        $_data = $_model->find('LOWER(delivery_sr_no) = ?', array(strtolower($id)));

        $this->model['model'] = $_data;
        $this->model['customer'] = Customer::model()->findByPk($_data->customer_id);
        $this->render('invoice', $this->model);
    }

    public function actionSave() {
        $_date = $_POST['Delivery']['delivery_date'];

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            $delivery = new Delivery();
            $delivery->delivery_number = Delivery::model()->lastNumber();
            $delivery->person = $_POST['Delivery']['person'];
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
            $delivery_srlist = $_POST['sr_no'];
            foreach ($delivery_srlist as $dkey => $dval) {
                if (!empty($delivery_srlist[$dkey])) {
                    $srinfo = ProductIn::model()->find('sr_no=:sr', [':sr' => $dval]);
                    $deliveryItem = new DeliveryItem();
                    $deliveryItem->delivery_id = $delivery_id;
                    $deliveryItem->customer_id = $srinfo->customer_id;
                    $deliveryItem->sr_no = $dval;
                    $deliveryItem->agent_code = $srinfo->agent_code;
                    $deliveryItem->lot_no = $srinfo->lot_no;
                    $deliveryItem->quantity = $_POST['quantity'][$dkey];
                    $deliveryItem->rent = $_POST['rent'][$dkey];
                    $deliveryItem->rent_total = $_POST['total'][$dkey];
                    $deliveryItem->net_total = $deliveryItem->rent_total;
                    $deliveryItem->delivery_date = $delivery->delivery_date;
                    $deliveryItem->created = AppHelper::getDbTimestamp();
                    $deliveryItem->created_by = Yii::app()->user->id;
                    $deliveryItem->_key = AppHelper::getUnqiueKey() . $dkey;
                    if (!$deliveryItem->save()) {
                        throw new CException(Yii::t("App", "Error while saving delivery item."));
                    }

                    $deliveryItemId = Yii::app()->db->getLastInsertId();
                    $_modelCashAccount = new CashAccount();
                    $_modelCashAccount->product_out_payment_id = $deliveryItemId;
                    $_modelCashAccount->type = 'D';
                    $_modelCashAccount->pay_date = $deliveryItem->delivery_date;
                    $_modelCashAccount->ledger_head_id = AppConstant::DELIVERY_HEAD_ID;
                    $_modelCashAccount->purpose = "Delivery for SR {$deliveryItem->sr_no}";
                    $_modelCashAccount->by_whom = User::model()->displayname(Yii::app()->user->id);
                    $_modelCashAccount->debit = $deliveryItem->net_total;
                    $_modelCashAccount->balance = $_modelCashAccount->debit;
                    $_modelCashAccount->created = $deliveryItem->delivery_date;
                    $_modelCashAccount->created_by = Yii::app()->user->id;
                    $_modelCashAccount->_key = AppHelper::getUnqiueKey() . $dkey;
                    if (!$_modelCashAccount->save()) {
                        throw new CException(Yii::t("App", "Error while saving transaction."));
                    }
                }
            }

            $_transaction->commit();
            $this->resp['success'] = true;
            $this->resp['message'] = "New record save successfull.";
        } catch (CException $e) {
            $_transaction->rollback();
            $this->resp['success'] = false;
            $this->resp['message'] = $e->getMessage();
        }

        echo json_encode($this->resp);
        return json_encode($this->resp);
    }

    public function actionSave_new() {
        $_date = $_POST['delivery_date'];

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            if (empty($_POST['Delivery']['srno'])) {
                throw new CException(Yii::t("App", "No sr number found for delivery."));
            }

            $receive_srqty = $_POST['LoanReceived']['quantity'];
            if (array_filter($receive_srqty)) {
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
                foreach ($receive_srqty as $rkey => $rval) {
                    if (!empty($receive_srqty[$rkey])) {
                        $srinfo = ProductIn::model()->find('sr_no=:sr', [':sr' => $_POST['LoanReceived']['srno'][$rkey]]);
                        $receiveItem = new LoanReceiveItem();
                        $receiveItem->receive_id = $receive_id;
                        $receiveItem->customer_id = $srinfo->customer_id;
                        $receiveItem->sr_no = $_POST['LoanReceived']['srno'][$rkey];
                        $receiveItem->agent_code = $srinfo->agent_code;
                        $receiveItem->lot_no = $srinfo->lot_no;
                        $receiveItem->qty = $rval;
                        $receiveItem->cost_per_qty = $_POST['LoanReceived']['per_bag_loan'][$rkey];
                        $receiveItem->loan_amount = $_POST['LoanReceived']['amount'][$rkey];
                        $receiveItem->loan_days = $_POST['LoanReceived']['day'][$rkey];
                        $receiveItem->interest_amount = $_POST['LoanReceived']['interest'][$rkey];
                        $receiveItem->net_amount = $_POST['LoanReceived']['total'][$rkey];
                        $receiveItem->cur_qty = (AppObject::srLoanRemainQty($receiveItem->sr_no) - $receiveItem->qty);
                        $receiveItem->receive_date = $receiveModel->receive_date;
                        $receiveItem->created = AppHelper::getDbTimestamp();
                        $receiveItem->created_by = Yii::app()->user->id;
                        $receiveItem->_key = AppHelper::getUnqiueKey() . $rkey;
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
                        $_modelCashAccount->_key = AppHelper::getUnqiueKey() . $rkey;
                        if (!$_modelCashAccount->save()) {
                            throw new CException(Yii::t("App", "Error while saving transaction."));
                        }
                    }
                }
            }

            $delivery = new Delivery();
            $delivery->delivery_number = Delivery::model()->lastNumber();
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
            $delivery_srlist = $_POST['Delivery']['srno'];
            foreach ($delivery_srlist as $dkey => $dval) {
                if (!empty($delivery_srlist[$dkey])) {
                    $srinfo = ProductIn::model()->find('sr_no=:sr', [':sr' => $dval]);
                    $deliveryItem = new DeliveryItem();
                    $deliveryItem->delivery_id = $delivery_id;
                    $deliveryItem->customer_id = $srinfo->customer_id;
                    $deliveryItem->sr_no = $dval;
                    $deliveryItem->agent_code = $srinfo->agent_code;
                    $deliveryItem->lot_no = $srinfo->lot_no;
                    $deliveryItem->quantity = $_POST['Delivery']['quantity'][$dkey];
                    $deliveryItem->rent = $_POST['Delivery']['rent'][$dkey];
                    $deliveryItem->rent_total = $_POST['Delivery']['total'][$dkey];
                    $deliveryItem->fan_charge = $_POST['Delivery']['fan_charge'][$dkey];
                    $deliveryItem->net_total = ($deliveryItem->rent_total + $deliveryItem->fan_charge);
                    $deliveryItem->cur_qty = (AppObject::currentStock($dval) - $deliveryItem->quantity);
                    $deliveryItem->delivery_date = $delivery->delivery_date;
                    $deliveryItem->created = AppHelper::getDbTimestamp();
                    $deliveryItem->created_by = Yii::app()->user->id;
                    $deliveryItem->_key = AppHelper::getUnqiueKey() . $dkey;
                    if (!$deliveryItem->save()) {
                        throw new CException(Yii::t("App", "Error while saving delivery item."));
                    }

                    $deliveryItemId = Yii::app()->db->getLastInsertId();
                    $_modelCashAccount = new CashAccount();
                    $_modelCashAccount->product_out_payment_id = $deliveryItemId;
                    $_modelCashAccount->type = 'D';
                    $_modelCashAccount->pay_date = $deliveryItem->delivery_date;
                    $_modelCashAccount->ledger_head_id = AppConstant::DELIVERY_HEAD_ID;
                    $_modelCashAccount->purpose = "Delivery for SR {$deliveryItem->sr_no}";
                    $_modelCashAccount->by_whom = User::model()->displayname(Yii::app()->user->id);
                    $_modelCashAccount->debit = $deliveryItem->net_total;
                    $_modelCashAccount->balance = $_modelCashAccount->debit;
                    $_modelCashAccount->created = $deliveryItem->delivery_date;
                    $_modelCashAccount->created_by = Yii::app()->user->id;
                    $_modelCashAccount->_key = AppHelper::getUnqiueKey() . $dkey;
                    if (!$_modelCashAccount->save()) {
                        throw new CException(Yii::t("App", "Error while saving transaction."));
                    }
                }
            }

            $_transaction->commit();
            $this->resp['success'] = true;
            $this->resp['message'] = "New record save successfull.";
        } catch (CException $e) {
            $_transaction->rollback();
            $this->resp['success'] = false;
            $this->resp['message'] = $e->getMessage();
        }

        echo json_encode($this->resp);
        return json_encode($this->resp);
    }

    public function actionSave_single() {
        $_form_option = $_POST['form_option'];
        $_srno = $_POST['srno'];

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            if (empty($_srno)) {
                throw new CException(Yii::t("App", "SR number required for delivery."));
            }

            if (empty($_form_option)) {
                throw new CException(Yii::t("App", "Cannot process the request for unknown reason. Please try again."));
            }

            if (empty($_POST['Delivery']['quantity'])) {
                throw new CException(Yii::t("App", "Quantity required for delivery."));
            }

            $srinfo = ProductIn::model()->find('sr_no=:sr', [':sr' => $_srno]);
            if (empty($srinfo)) {
                throw new CException(Yii::t("App", "SR number is invalid or not exist."));
            }

            $_hasLoan = LoanItem::model()->find('sr_no=:sr', [':sr' => $_srno]);
            $_currLoan = AppObject::currentLoan($_srno);
            $_deliveryQty = $_POST['Delivery']['quantity'];
            $_loanAmount = $_POST['LoanReceived']['amount'];
            $_remainQty = $_POST['Delivery']['quantity_remain'];
            $_remainloanAmount = $_POST['LoanReceived']['loan_remain'];

            if (!empty($_hasLoan)) {
                if ($_remainQty <= 0) {
                    if ($_loanAmount < $_currLoan) {
                        throw new CException(Yii::t("App", "You have to pay {$_currLoan} tk for loan."));
                    }
                } else {
                    $_currLPQ = ($_remainloanAmount / $_remainQty);
                    if ($_currLPQ > $_hasLoan->qty_cost) {
                        $_loanToPay = ($_deliveryQty * $_hasLoan->qty_cost);
                        if ($_loanAmount < $_loanToPay) {
                            throw new CException(Yii::t("App", "You have to pay {$_loanToPay} tk for loan."));
                        }
                    }
                }
            }

            $this->saveLoanReceive($_POST);
            $this->saveDelivery($_POST);

            $_transaction->commit();
            $this->resp['success'] = true;
            $this->resp['message'] = "New record save successfull.";
        } catch (CException $e) {
            $_transaction->rollback();
            $this->resp['success'] = false;
            $this->resp['message'] = $e->getMessage();
        }

        echo json_encode($this->resp);
        return json_encode($this->resp);
    }

    public function actionReport() {
        $this->checkUserAccess('delivery_list');
        $this->setHeadTitle("Delivery");
        $this->setPageTitle("Delivery Report");
        $this->setCurrentPage(AppUrl::URL_DELIVERY);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new DeliveryItem();
        $criteria = new CDbCriteria();
        $criteria->group = "delivery_date";
        $criteria->order = "delivery_date DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('report', $this->model);
    }

    public function actionReport_detail() {
        $this->checkUserAccess('delivery_list');
        $this->setHeadTitle("Delivery");
        $this->setPageTitle("Delivery Report");
        $this->setCurrentPage(AppUrl::URL_DELIVERY);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new DeliveryItem();
        $criteria = new CDbCriteria();
        $criteria->order = "delivery_date ASC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('report_detail', $this->model);
    }

    /*
     * Ajax search and other responses
     */

    public function actionSearch() {
        $this->is_ajax_request();
        $_limit = Yii::app()->request->getPost('itemCount');
        $_user = Yii::app()->request->getPost('user');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $_srno = Yii::app()->request->getPost('srno');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new Delivery();
        $criteria = new CDbCriteria();
        if (!empty($_user)) {
            $criteria->addCondition("created_by={$_user}");
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('delivery_date', $dateForm, $dateTo);
        }
        if (!empty($_srno)) {
            $criteria->addCondition("sr_no={$_srno}");
        }
        $criteria->order = "delivery_date DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('_view', $this->model);
    }

    public function actionSearch_item_list() {
        $this->is_ajax_request();
        $_limit = Yii::app()->request->getPost('itemCount');
        $_user = Yii::app()->request->getPost('user');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $_customer = Yii::app()->request->getPost('customer');
        $_srno = Yii::app()->request->getPost('srno');
        $_agent = Yii::app()->request->getPost('agent');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new DeliveryItem();
        $criteria = new CDbCriteria();
        if (!empty($_user)) {
            $criteria->addCondition("created_by={$_user}");
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('delivery_date', $dateForm, $dateTo);
        }
        if (!empty($_customer)) {
            $_customerModel = new Customer();
            $_customerCriteria = new CDbCriteria();
            $_customerCriteria->addCondition("name LIKE '%" . trim($_customer) . "%'");
            $_customerData = $_customerModel->findAll($_customerCriteria);
            foreach ($_customerData as $_cdata) {
                $_cid[] = $_cdata->id;
            }
            $criteria->addInCondition("customer_id", $_cid);
        }
        if (!empty($_srno)) {
            $criteria->addCondition("sr_no={$_srno}");
        }
        if (!empty($_agent)) {
            $criteria->addCondition("agent_code={$_agent}");
        }
        $criteria->order = "delivery_date DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('_view_item_list', $this->model);
    }

    public function actionSearch_report() {
        $this->is_ajax_request();
        $_limit = Yii::app()->request->getPost('itemCount');
        $_user = Yii::app()->request->getPost('user');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $_order = Yii::app()->request->getPost('order');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new DeliveryItem();
        $criteria = new CDbCriteria();
        if (!empty($_user)) {
            $criteria->addCondition("created_by={$_user}");
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('delivery_date', $dateForm, $dateTo);
        }
        $criteria->group = "delivery_date";
        $criteria->order = $_order;
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('_report', $this->model);
    }

    public function actionSearch_report_detail() {
        $this->is_ajax_request();
        $_limit = Yii::app()->request->getPost('itemCount');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $_order = Yii::app()->request->getPost('order');
        $_srno = Yii::app()->request->getPost('srno');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new DeliveryItem();
        $criteria = new CDbCriteria();
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('delivery_date', $dateForm, $dateTo);
        }
        if (!empty($_srno)) {
            $criteria->addCondition("sr_no={$_srno}");
        }
        $criteria->order = $_order;
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->model['dateForm'] = !empty($_from) ? $_from : (!empty($_dataset) ? $_dataset[0]->delivery_date : '');
        $this->model['dateTo'] = !empty($_to) ? $_to : (!empty($_dataset) ? $_dataset[count($_dataset) - 1]->delivery_date : '');
        $this->renderPartial('_report_detail', $this->model);
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
            $_modelCashAccount->purpose = "Loan paid to " . Customer::model()->findByPk($_modelItem->customer_id)->name . " @{$_modelItem->qty_cost} tk";
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
            $_transaction->rollback();
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        return json_encode($response);
    }

    public function actionRemove_item($id) {
        $response = array();
        $_model = DeliveryItem::model()->findByPk($id);

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            $_customerPayment = CustomerPayment::model()->find('pout_id=:poid', [':poid' => $id]);
            if (!empty($_customerPayment)) {
                if (!$_customerPayment->delete()) {
                    throw new CException(Yii::t('App', "Error while deleting customer payment."));
                }
            }

            $_cashPayment = CashAccount::model()->find('product_out_payment_id=:popid', [':popid' => $id]);
            if (!empty($_cashPayment)) {
                if (!$_cashPayment->delete()) {
                    throw new CException(Yii::t('App', "Error while deleting cash record."));
                }
            }

            if (!$_model->delete()) {
                throw new CException(Yii::t('App', "Error while deleting removing item record."));
            }

            $_transaction->commit();
            $response['success'] = true;

            $response['message'] = "Records deleted successfully!";
        } catch (CException $e) {
            $_transaction->rollback();
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        return json_encode($response);
    }

    public function actionDeleteall() {
        $response = array();
        $_model = new Delivery();

        if (isset($_POST['data'])) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < count($_POST['data']); $i++) {
                    $_obj = $_model->with('items')->findByPk($_POST['data'][$i]);

                    $loanReceive = LoanReceive::model()->with('items')->find("receive_number=:rn", [":rn" => $_obj->delivery_number]);
                    if (!empty($loanReceive)) {
                        if (!empty($loanReceive->items)) {
                            foreach ($loanReceive->items as $lritem) {
                                $_cashPaymentLr = CashAccount::model()->findAll('loan_receive_id=:lrid', [':lrid' => $lritem->id]);
                                if (!empty($_cashPaymentLr)) {
                                    foreach ($_cashPaymentLr as $_cashLr) {
                                        if (!$_cashLr->delete()) {
                                            throw new CException(Yii::t('App', "Error while deleting cash record."));
                                        }
                                    }
                                }

                                if (!$lritem->delete()) {
                                    throw new CException(Yii::t('App', "Error while deleting loan received item."));
                                }
                            }
                        }

                        if (!$loanReceive->delete()) {
                            throw new CException(Yii::t('App', "Error while deleting loan received record"));
                        }
                    }

                    if (!empty($_obj->items)) {
                        foreach ($_obj->items as $item) {
                            $_cashPayment = CashAccount::model()->findAll('product_out_payment_id=:popid', [':popid' => $item->id]);
                            if (!empty($_cashPayment)) {
                                foreach ($_cashPayment as $_cash) {
                                    if (!$_cash->delete()) {
                                        throw new CException(Yii::t('App', "Error while deleting cash record."));
                                    }
                                }
                            }

                            if (!$item->delete()) {
                                throw new CException(Yii::t('App', "Error while deleting delivery item."));
                            }
                        }
                    }

                    if (!$_obj->delete()) {
                        throw new CException(Yii::t('App', "Error while deleting delivery record"));
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

    /*
     * protected function for
     * delivery and loan receive
     */

    protected function saveDelivery($data) {
        $srinfo = ProductIn::model()->find('sr_no=:sr', [':sr' => $data['srno']]);
        $delivery = new Delivery();
        $delivery->sr_no = $data['srno'];
        $delivery->delivery_number = $data['receipt_no'];
        $delivery->person = $data['delivery_person'];
        $delivery->delivery_date = date('Y-m-d', strtotime($data['delivery_date']));
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
        $deliveryItem->sr_no = $data['srno'];
        $deliveryItem->type = $srinfo->type;
        $deliveryItem->agent_code = $srinfo->agent_code;
        $deliveryItem->lot_no = $srinfo->lot_no;
        $deliveryItem->loan_bag = $data['Delivery']['empty_bag'];
        $deliveryItem->loan_bag_price = $data['Delivery']['empty_bag_price'];
        $deliveryItem->loan_bag_price_total = $data['Delivery']['empty_bag_amount'];
        $deliveryItem->carrying = $data['Delivery']['carrying'];
        $deliveryItem->quantity = $data['Delivery']['quantity'];
        $deliveryItem->rent = $data['Delivery']['rent'];
        $deliveryItem->rent_total = $data['Delivery']['rent_total'];
        $deliveryItem->fan_charge = $data['Delivery']['fan_charge'];
        $deliveryItem->fan_charge_qty = $data['Delivery']['fan_charge_qty'];
        $deliveryItem->fan_charge_total = $data['Delivery']['fan_charge_total'];
        $deliveryItem->delivery_total = ($deliveryItem->loan_bag_price_total + $deliveryItem->carrying + $deliveryItem->rent_total + $deliveryItem->fan_charge_total);
        $deliveryItem->discount = $data['Delivery']['discount'];
        $deliveryItem->net_total = $data['Delivery']['net_amount'];
        $deliveryItem->cur_qty = (AppObject::currentStock($deliveryItem->sr_no) - $deliveryItem->quantity);
        $deliveryItem->delivery_date = $delivery->delivery_date;
        $deliveryItem->created = AppHelper::getDbTimestamp();
        $deliveryItem->created_by = Yii::app()->user->id;
        $deliveryItem->_key = AppHelper::getUnqiueKey();
        if (!$deliveryItem->save()) {
            throw new CException(Yii::t("App", "Error while saving delivery item."));
        }

        $_rent = !empty($deliveryItem->rent_total) ? $deliveryItem->rent_total : '';
        $_carrying = !empty($deliveryItem->carrying) ? $deliveryItem->carrying : '';
        $_fannying = !empty($deliveryItem->fan_charge_total) ? $deliveryItem->fan_charge_total : '';
        $_empty_bag = !empty($deliveryItem->loan_bag_price_total) ? $deliveryItem->loan_bag_price_total : '';

        $_headArr = [
            ['head_id' => AppConstant::HEAD_DELIVERY, 'amount' => $_rent, 'purpose' => 'Delivery receive'],
            ['head_id' => AppConstant::HEAD_CARRYING, 'amount' => $_carrying, 'purpose' => 'Carrying receive'],
            ['head_id' => AppConstant::HEAD_FANNYING, 'amount' => $_fannying, 'purpose' => 'Fannying receive'],
            ['head_id' => AppConstant::HEAD_EMPTY_BAG, 'amount' => $_empty_bag, 'purpose' => 'Empty Bag Amount receive'],
        ];

        $deliveryItemId = Yii::app()->db->getLastInsertId();
        foreach ($_headArr as $_key => $_val) {
            if (!empty($_headArr[$_key]['amount'])) {
                $_modelCashAccount = new CashAccount();
                $_modelCashAccount->product_out_payment_id = $deliveryItemId;
                $_modelCashAccount->type = 'D';
                $_modelCashAccount->pay_date = $deliveryItem->delivery_date;
                $_modelCashAccount->ledger_head_id = $_headArr[$_key]['head_id'];
                $_modelCashAccount->purpose = "{$_headArr[$_key]['purpose']} for SR {$deliveryItem->sr_no}";
                $_modelCashAccount->by_whom = User::model()->displayname(Yii::app()->user->id);
                $_modelCashAccount->debit = $_headArr[$_key]['amount'];
                $_modelCashAccount->balance = $_modelCashAccount->debit;
                $_modelCashAccount->created = $deliveryItem->delivery_date;
                $_modelCashAccount->created_by = Yii::app()->user->id;
                $_modelCashAccount->_key = AppHelper::getUnqiueKey();
                if (!$_modelCashAccount->save()) {
                    throw new CException(Yii::t("App", "Error while saving transaction."));
                }
            }
        }

        return true;
    }

    protected function saveLoanReceive($data) {
        $srinfo = ProductIn::model()->find('sr_no=:sr', [':sr' => $data['srno']]);
        $receiveModel = new LoanReceive();
        $receiveModel->sr_no = $data['srno'];
        $receiveModel->receive_number = $data['receipt_no'];
        $receiveModel->receive_date = date('Y-m-d', strtotime($data['delivery_date']));
        $receiveModel->created = AppHelper ::getDbTimestamp();
        $receiveModel->created_by = Yii::app()->user->id;
        $receiveModel->_key = AppHelper::getUnqiueKey();
        if (!$receiveModel->validate()) {
            throw new CException(Yii ::t("App", CHtml::errorSummary($receiveModel)));
        }
        if (!$receiveModel->save()) {
            throw new CException(Yii ::t("App", "Error while saving loan receive."));
        }

        $receive_id = Yii::app()->db->getLastInsertId();
        $receiveItem = new LoanReceiveItem();
        $receiveItem->receive_id = $receive_id;
        $receiveItem->delivery_number = $receiveModel->receive_number;
        $receiveItem->customer_id = $srinfo->customer_id;
        $receiveItem->sr_no = $data['srno'];
        $receiveItem->agent_code = $srinfo->agent_code;
        $receiveItem->lot_no = $srinfo->lot_no;
        $receiveItem->qty = NULL;
        $receiveItem->cost_per_qty = NULL;
        $receiveItem->loan_amount = $data['LoanReceived']['amount'];
        $receiveItem->loan_days = $data['LoanReceived']['day'];
        $receiveItem->interest_amount = !empty($receiveItem->loan_amount) ? $data['LoanReceived']['interest'] : 0;
        $receiveItem->total_amount = ($receiveItem->loan_amount + $receiveItem->interest_amount);
        $receiveItem->discount = NULL;
        $receiveItem->net_amount = $receiveItem->total_amount;
        $receiveItem->cur_qty = NULL;
        $receiveItem->receive_date = $receiveModel->receive_date;
        $receiveItem->created = AppHelper::getDbTimestamp();
        $receiveItem->created_by = Yii::app()->user->id;
        $receiveItem->_key = AppHelper::getUnqiueKey();
        if (!$receiveItem->save()) {
            throw new CException(Yii::t("App", "Error while saving loan receive item."));
        }

        $_loan = !empty($receiveItem->loan_amount) ? $receiveItem->loan_amount : '';
        $_interest = !empty($receiveItem->interest_amount) ? $receiveItem->interest_amount : '';

        $_headArr = [
            ['head_id' => AppConstant::HEAD_LOAN, 'amount' => $_loan, 'purpose' => 'Loan receive'],
            ['head_id' => AppConstant::HEAD_INTEREST, 'amount' => $_interest, 'purpose' => 'Interest receive'],
        ];

        $receiveItemId = Yii::app()->db->getLastInsertId();
        foreach ($_headArr as $_key => $_val) {
            $_modelCashAccount = new CashAccount();
            $_modelCashAccount->loan_receive_id = $receiveItemId;
            $_modelCashAccount->type = 'D';
            $_modelCashAccount->pay_date = $receiveItem->receive_date;
            $_modelCashAccount->ledger_head_id = $_headArr[$_key]['head_id'];
            $_modelCashAccount->purpose = "{$_headArr[$_key]['purpose']} for sr {$receiveItem->sr_no}";
            $_modelCashAccount->by_whom = User::model()->displayname(Yii::app()->user->id);
            $_modelCashAccount->debit = $_headArr[$_key]['amount'];
            $_modelCashAccount->balance = $_modelCashAccount->debit;
            $_modelCashAccount->created = $receiveItem->receive_date;
            $_modelCashAccount->created_by = Yii::app()->user->id;
            $_modelCashAccount->_key = AppHelper::getUnqiueKey();
            if (!$_modelCashAccount->save()) {
                throw new CException(Yii::t("App", "Error while saving transaction."));
            }
        }

        return true;
    }

    /* Upadate functions */

    public function actionUpdate_delivery_number() {
        $_model = new Delivery();
        $_dataset = $_model->findAll();

        foreach ($_dataset as $_data) {
            if (!empty($_data->items)) {
                foreach ($_data->items as $_delitem) {
                    $_delitem->delivery_number = $_data->delivery_number;
                    if ($_delitem->save()) {
                        echo "{$_delitem->id} = Saved<br>";
                    } else {
                        echo "{$_delitem->id} = Failed<br>";
                    }
                }
            }
        }

        $_modelLR = new LoanReceive();
        $_datasetLr = $_modelLR->findAll();

        foreach ($_datasetLr as $_datalr) {
            if (!empty($_datalr->items)) {
                foreach ($_datalr->items as $_datalritem) {
                    $_datalritem->delivery_number = $_datalr->receive_number;
                    if ($_datalritem->save()) {
                        echo "{$_datalritem->id} = Saved<br>";
                    } else {
                        echo "{$_datalritem->id} = Failed<br>";
                    }
                }
            }
        }
    }

    public function actionUpdate_delivery_info() {
        $_dataset = DeliveryItem::model()->findAll();

        $counter = 0;
        foreach ($_dataset as $_data) {
            $counter++;
            $_srInfo = ProductIn::model()->find("sr_no=:sr", [":sr" => $_data->sr_no]);
            $_data->type = $_srInfo->type;
            $_data->delivery_total = ($_data->loan_bag_price_total + $_data->carrying + $_data->rent_total + $_data->fan_charge_total);
            $_data->net_total = $_data->delivery_total;
            if ($_data->save()) {
                echo "{$counter} => saved <br>";
            } else {
                echo "{$counter} => failed <br>";
            }
        }
        exit;
    }

    public function actionUpdate_delivery_cash() {
        $_dataset = DeliveryItem::model()->findAll();

        $counter = 0;
        foreach ($_dataset as $_data) {
            $counter++;
            $_cashInfo = CashAccount::model()->find("product_out_payment_id=:popid", [":popid" => $_data->id]);
            $_cashInfo->debit = $_data->net_total;
            $_cashInfo->balance = $_data->net_total;
            if ($_cashInfo->save()) {
                echo "{$counter} => saved <br>";
            } else {
                echo "{$counter} => failed <br>";
            }
        }
        exit;
    }

    public function actionUpdate_all() {
        $_model = new DeliveryItem();
        $criteria = new CDbCriteria();
        $criteria->order = "id ASC";
        $_dataset = $_model->findAll($criteria);
        echo count($_dataset) . "<br>";

        $_counter = 0;
        foreach ($_dataset as $_data) {
            $_counter++;
            $_cashPayment = CashAccount::model()->findAll('product_out_payment_id=:popid', [':popid' => $_data->id]);
            if (!empty($_cashPayment)) {
                foreach ($_cashPayment as $_cash) {
                    if (!$_cash->delete()) {
                        throw new CException(Yii::t('App', "Error while deleting cash record."));
                    }
                }
            }

            $_rent = !empty($_data->rent_total) ? $_data->rent_total : '';
            $_carrying = !empty($_data->carrying) ? $_data->carrying : '';
            $_fannying = !empty($_data->fan_charge_total) ? $_data->fan_charge_total : '';
            $_empty_bag = !empty($_data->loan_bag_price_total) ? $_data->loan_bag_price_total : '';

            $_headArr = [
                ['head_id' => AppConstant::HEAD_DELIVERY, 'amount' => $_rent, 'purpose' => 'Delivery receive'],
                ['head_id' => AppConstant::HEAD_CARRYING, 'amount' => $_carrying, 'purpose' => 'Carrying receive'],
                ['head_id' => AppConstant::HEAD_FANNYING, 'amount' => $_fannying, 'purpose' => 'Fannying receive'],
                ['head_id' => AppConstant::HEAD_EMPTY_BAG, 'amount' => $_empty_bag, 'purpose' => 'Empty Bag Amount receive'],
            ];

            foreach ($_headArr as $_key => $_val) {
                if (!empty($_headArr[$_key]['amount'])) {
                    $_modelCashAccount = new CashAccount();
                    $_modelCashAccount->product_out_payment_id = $_data->id;
                    $_modelCashAccount->type = 'D';
                    $_modelCashAccount->pay_date = $_data->delivery_date;
                    $_modelCashAccount->ledger_head_id = $_headArr[$_key]['head_id'];
                    $_modelCashAccount->purpose = "{$_headArr[$_key]['purpose']} for SR {$_data->sr_no}";
                    $_modelCashAccount->by_whom = User::model()->displayname($_data->created_by);
                    $_modelCashAccount->debit = $_headArr[$_key]['amount'];
                    $_modelCashAccount->balance = $_modelCashAccount->debit;
                    $_modelCashAccount->created = $_data->delivery_date;
                    $_modelCashAccount->created_by = $_data->created_by;
                    $_modelCashAccount->_key = $_data->_key . $_key;
                    if ($_modelCashAccount->save()) {
                        echo "{$_counter} => Saved <br>";
                    } else {
                        echo "{$_counter} => Failed <br>";
                    }
                }
            }
        }
        exit;
    }

    public function actionUpdate_view() {
        $_model = new DeliveryItem();
        $criteria = new CDbCriteria();
        $criteria->order = "id ASC";
        $_dataset = $_model->findAll($criteria);
        echo count($_dataset);

        $_counter = 0;
        foreach ($_dataset as $_data) {
            $_counter++;
            $_rent = !empty($_data->rent_total) ? $_data->rent_total : '';
            $_carrying = !empty($_data->carrying) ? $_data->carrying : '';
            $_fannying = !empty($_data->fan_charge_total) ? $_data->fan_charge_total : '';
            $_empty_bag = !empty($_data->loan_bag_price_total) ? $_data->loan_bag_price_total : '';

            $_headArr = [
                ['head_id' => AppConstant::HEAD_DELIVERY, 'amount' => $_rent, 'purpose' => 'Delivery receive'],
                ['head_id' => AppConstant::HEAD_CARRYING, 'amount' => $_carrying, 'purpose' => 'Carrying receive'],
                ['head_id' => AppConstant::HEAD_FANNYING, 'amount' => $_fannying, 'purpose' => 'Fannying receive'],
                ['head_id' => AppConstant::HEAD_EMPTY_BAG, 'amount' => $_empty_bag, 'purpose' => 'Empty Bag Amount receive'],
            ];

            $_str = '<table border="1" style="margin-bottom:15px">';
            foreach ($_headArr as $_key => $_val) {
                if (!empty($_headArr[$_key]['amount'])) {
                    $_str.= '<tr>';
                    $_str.="<td style='padding:5px'>SL No = {$_counter}</td>";
                    $_str.="<td style='padding:5px'>ID = {$_data->id}</td>";
                    $_str.="<td style='padding:5px'>Head ID = {$_headArr[$_key]['head_id']}</td>";
                    $_str.="<td style='padding:5px'>Amount Debited = {$_headArr[$_key]['amount']} Tk</td>";
                    $_str.="<td style='padding:5px'>Purpose = {$_headArr[$_key]['purpose']}</td>";
                    $_str.="</tr>";
                }
            }
            $_str.="<tr>";
            $_str.="<td colspan='3' style='padding:5px'>Total Amount Collection</td>";
            $_str.="<td style='padding:5px;text-align:right;'>{$_data->delivery_total} Tk</td>";
            $_str.="<td></td>";
            $_str.="</tr>";
            $_str.="</table>";
            echo $_str;
        }
        exit;
    }

}
