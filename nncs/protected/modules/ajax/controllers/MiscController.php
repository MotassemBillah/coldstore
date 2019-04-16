<?php

class MiscController extends AppController {

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
        $_search = Yii::app()->request->getPost('q');

        $_model = new Company();
        $criteria = new CDbCriteria();
        $criteria->condition = "is_deleted = 0";
        if (!empty($_search)) {
            $criteria->addCondition("name LIKE :match OR mobile LIKE :match OR phone LIKE :match");
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

    public function actionFind_agent() {
        $resp = array();
        $_model = new Agent();
        $agentCode = Yii::app()->request->getPost('aid');
        $data = $_model->find('code=:code', [':code' => $agentCode]);

        if (!empty($data)) {
            $resp['success'] = true;
            $resp['name'] = $data->name;
            $resp['vill'] = $data->village;
            $resp['distid'] = $data->zila;
            $resp['dist'] = !empty($data->zila) ? $data->zila : '';
            $resp['mobile'] = $data->mobile;
        } else {
            $resp['success'] = false;
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    public function actionFind_customer() {
        $resp = array();
        $_model = new Customer();
        $_customerID = Yii::app()->request->getPost('cid');
        $data = $_model->findByPk($_customerID);

        if (!empty($data)) {
            $resp['success'] = true;
            $resp['name'] = $data->name;
            $resp['father_name'] = $data->father_name;
            $resp['distid'] = !empty($data->district) ? $data->district : '';
            $resp['dist'] = !empty($data->district) ? $data->district : '';
            $resp['thana'] = !empty($data->thana) ? $data->thana : '';
            $resp['vill'] = $data->village;
            $resp['mobile'] = $data->mobile;
        } else {
            $resp['success'] = false;
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    public function actionFind_floor() {
        $resp = array();
        $roomID = Yii::app()->request->getPost('room');

        $_model = new LocationFloor();
        $dataset = $_model->findAll('room_id=:room_id', [':room_id' => $roomID]);

        $html = "<option value=''>Select</option>";

        if (!empty($dataset) && count($dataset) > 0) {
            foreach ($dataset as $data) {
                $html .= "<option value='{$data->id}'>{$data->name}</option>";
            }

            $resp['success'] = true;
            $resp['html'] = $html;
        } else {
            $resp['success'] = false;
            $resp['html'] = "<option value=''>Not Found</option>";
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    public function actionFind_pocket() {
        $resp = array();
        $roomID = Yii::app()->request->getPost('room');
        $floorID = Yii::app()->request->getPost('floor');

        $_model = new LocationPocket();
        $dataset = $_model->findAll('room_id=:rid AND floor_id=:fid', [':rid' => $roomID, ':fid' => $floorID]);

        $html = "<option value=''>Select</option>";

        if (!empty($dataset) && count($dataset) > 0) {
            foreach ($dataset as $data) {
                $html .= "<option value='{$data->id}'>{$data->name}</option>";
            }

            $resp['success'] = true;
            $resp['html'] = $html;
        } else {
            $resp['success'] = false;
            $resp['html'] = "<option value=''>Not Found</option>";
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    public function actionFind_pocket_by_floor() {
        $resp = array();
        $roomID = Yii::app()->request->getPost('room');
        $floorID = Yii::app()->request->getPost('floor');

        $_model = new LocationPocket();
        $dataset = $_model->findAll('room_id=:rid AND floor_id=:fid', [':rid' => $roomID, ':fid' => $floorID]);

        $html = "";

        if (!empty($dataset) && count($dataset) > 0) {
            foreach ($dataset as $data) {
                $html .= "<li><label style='font-weight:500' for='pkt_{$data->id}'><input id='pkt_{$data->id}' type='checkbox' name='pockets[]' value='{$data->pocket_no}'>&nbsp;{$data->pocket_no}</label></li>";
            }

            $resp['success'] = true;
            $resp['html'] = $html;
        } else {
            $resp['success'] = false;
            $resp['html'] = "<li style='width:100%'>No pocket found.<li>";
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    public function actionFind_srinfo() {
        $resp = array();
        $loanSetting = LoanSetting::model()->findByPk(1);
        $_srno = Yii::app()->request->getPost('srno');
        $_isNew = Yii::app()->request->getPost('isNew');
        $_srData = ProductIn::model()->find('sr_no=:sr', [':sr' => $_srno]);

        try {
            if ($_isNew == 'yes') {
                if ($this->hasLoan($_srno)) {
                    throw new CException(Yii::t("App", "SR Number <b>{$_srno}</b> already in loan."));
                }
            }

            if (empty($_srData)) {
                throw new CException(Yii::t("App", "Invalid SR Number or not exist."));
            }

            $resp['success'] = true;
            $resp['cid'] = $_srData->customer_id;
            $resp['customer'] = !empty($_srData->customer_id) ? Customer::model()->findByPk($_srData->customer_id)->name : '';
            $resp['tid'] = $_srData->type;
            $resp['type'] = !empty($_srData->type) ? ProductType::model()->findByPk($_srData->type)->name : '';
            $resp['aid'] = !empty($_srData->agent_code) ? $_srData->agent_code : '';
            $resp['agent'] = !empty($_srData->agent_code) ? Agent::model()->find("code=:ac", [":ac" => $_srData->agent_code])->name : '';
            $resp['qty'] = AppObject::currentStock($_srno);
            $resp['loan_per_qty'] = $loanSetting->max_loan_per_qty;
            $resp['loan_total'] = ($resp['qty'] * $resp['loan_per_qty']);
        } catch (CException $ex) {
            $resp['success'] = false;
            $resp['message'] = $ex->getMessage();
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    public function actionAdd_srto_loan() {
        $resp = array();
        $loanSetting = LoanSetting::model()->findByPk(1);
        $_srno = Yii::app()->request->getPost('srno');

        try {
            if ($this->hasLoan($_srno)) {
                throw new CException(Yii::t("App", "SR Number <b>{$_srno}</b> already in loan."));
            }

            $_srData = ProductIn::model()->find('sr_no=:sr', [':sr' => $_srno]);
            if (empty($_srData)) {
                throw new CException(Yii::t("App", "Invalid SR Number or not exist."));
            }

            $resp['success'] = true;
            $resp['qty'] = AppObject::currentStock($_srno);
            $resp['loan_per_qty'] = $loanSetting->max_loan_per_qty;
            $resp['loan_total'] = ($resp['qty'] * $resp['loan_per_qty']);
        } catch (CException $ex) {
            $resp['success'] = false;
            $resp['message'] = $ex->getMessage();
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    public function actionFind_srloan_info() {
        $resp = array();
        $_srno = Yii::app()->request->getPost('srno');
        $_date = Yii::app()->request->getPost('dt');
        $_dt = !empty($_date) ? date("Y-m-d", strtotime($_date)) : date('Y-m-d');
        $loanSetting = LoanSetting::model()->findByPk(1);

        try {
            if (empty($_srno)) {
                $resp['success'] = false;
                throw new CException(Yii::t("App", "Empty SR Number Info Not Found."));
            }

            $srExist = ProductIn::model()->exists("sr_no=:sr", [":sr" => $_srno]);
            if (!$srExist) {
                throw new CException(Yii::t("App", "SR number info not found or invalid SR number."));
            }

            $srinfo = ProductIn::model()->find('sr_no=:sr', [':sr' => $_srno]);
            $carryingReceived = DeliveryItem::model()->sumCarrying($_srno);
            $carryingRemain = ($srinfo->carrying_cost - $carryingReceived);
            if ($carryingRemain < 0) {
                $carryingRemain = 0;
            }

            if (empty($srinfo)) {
                $resp['success'] = false;
                throw new CException(Yii::t("App", "SR number is invalid or not exist."));
            } else {
                $resp['success'] = true;
                $resp['product']['lot_no'] = $srinfo->lot_no;
                $resp['product']['qty'] = $srinfo->quantity;
                $resp['product']['agent'] = $srinfo->agent_code;
                $resp['product']['loan_bag'] = !empty($srinfo->loan_pack) ? $srinfo->loan_pack : 0;
                $resp['product']['carrying'] = $carryingRemain;
            }

            $customer = Customer::model()->findByPk($srinfo->customer_id);
            if (!empty($customer)) {
                $resp['success'] = true;
                $resp['customer']['name'] = $customer->name;
                $resp['customer']['father_name'] = $customer->father_name;
                $resp['customer']['village'] = $customer->village;
                $resp['customer']['thana'] = $customer->thana;
                $resp['customer']['dist'] = $customer->district;
            } else {
                $resp['customer']['name'] = '';
                $resp['customer']['father_name'] = '';
                $resp['customer']['village'] = '';
                $resp['customer']['thana'] = '';
                $resp['customer']['dist'] = '';
            }

            $criteria = new CDbCriteria();
            $criteria->condition = "sr_no=:sr";
            $criteria->params = [':sr' => $_srno];
            $itemExists = LoanItem::model()->find($criteria);
            if (!empty($itemExists)) {
                $resp['success'] = true;
                $resp['loan']['duration'] = $loanSetting->period;
                $resp['loan']['rate'] = !empty($loanSetting->interest_rate) ? $loanSetting->interest_rate : 0;
                $resp['loan']['qty'] = AppObject::srLoanRemainQty($_srno);
                $resp['loan']['cost'] = $itemExists->qty_cost;
                //$resp['loan']['amount'] = ($resp['loan']['qty'] * $resp['loan']['cost']);
                $resp['loan']['amount'] = AppObject::currentLoan($_srno);
                $resp['loan']['day'] = AppHelper::get_day_diff($itemExists->create_date, $_dt);
                $resp['loan']['interest'] = ceil(AppHelper::countInterest($resp['loan']['amount'], $resp['loan']['day'], $loanSetting->interest_rate, $loanSetting->period));
                $resp['loan']['total'] = ($resp['loan']['amount'] + $resp['loan']['interest']);
            } else {
                $resp['loan']['duration'] = 0;
                $resp['loan']['rate'] = 0;
                $resp['loan']['qty'] = 0;
                $resp['loan']['cost'] = 0;
                $resp['loan']['amount'] = 0;
                $resp['loan']['day'] = 0;
                $resp['loan']['interest'] = 0;
                $resp['loan']['total'] = 0;
            }

            $resp['delivery']['qty'] = AppObject::currentStock($_srno);
            $resp['delivery']['remain'] = AppObject::currentStock($_srno);
            $resp['delivery']['rent'] = $loanSetting->max_rent_per_qty;
            $resp['delivery']['rent_total'] = ($resp['delivery']['qty'] * $resp['delivery']['rent']);
            $resp['delivery']['total'] = ($resp['delivery']['rent_total'] + $carryingRemain);
            $resp['delivery']['eb_price'] = $loanSetting->empty_bag_price;
            $resp['delivery']['fan_charge'] = $loanSetting->fan_charge;
            $resp['delivery']['net_total'] = ($resp['loan']['total'] + $resp['delivery']['total']);
            $resp['success'] = true;
        } catch (CException $ex) {
            $resp['success'] = false;
            $resp['message'] = $ex->getMessage();
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    public function actionFind_delivery_srinfo() {
        $resp = array();
        $_srno = Yii::app()->request->getPost('srno');

        try {
            if (empty($_srno)) {
                throw new CException(Yii::t("App", "SR Number Required."));
            }

            $_srData = ProductIn::model()->find('sr_no=:sr', [':sr' => $_srno]);
            if (empty($_srData)) {
                throw new CException(Yii::t("App", "SR Number not exist or invalid."));
            }

            $itemExists = LoanItem::model()->exists('sr_no=:sr AND status=:sts', [':sr' => $_srno, ':sts' => AppConstant::ORDER_PENDING]);
            if ($itemExists) {
                throw new CException(Yii::t("App", "SR Number is under loan."));
            }

            $resp['success'] = true;
            $resp['loan_status'] = 'Paid';
            $resp['qty_in'] = AppObject::stockIn($_srno);
            $resp['qty_out'] = AppObject::stockOut($_srno);
            $resp['qty'] = AppObject::currentStock($_srno);
        } catch (CException $ex) {
            $resp['success'] = false;
            $resp['message'] = $ex->getMessage();
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    public function actionFind_sr_qty() {
        $resp = array();
        $_srno = Yii::app()->request->getPost('srno');
        $_qty = AppObject::currentStock($_srno);

        if (!empty($_qty)) {
            $resp['success'] = true;
            $resp['qty'] = $_qty;
        } else {
            $resp['success'] = false;
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    public function actionFind_sr_location() {
        $resp = array();
        $_srno = Yii::app()->request->getPost('srno');

        try {
            if (empty($_srno)) {
                throw new CException(Yii::t("App", "No SR Number found."));
            }

            $srExist = ProductIn::model()->exists('sr_no=:sr', [":sr" => $_srno]);
            if (!$srExist) {
                throw new CException(Yii::t("App", "SR Number Not exist."));
            }

            $_qty = AppObject::currentStock($_srno);
            if (empty($_qty)) {
                throw new CException(Yii::t("App", "No quantity found."));
            }

            $criteria = new CDbCriteria();
            $criteria->condition = "sr_no=:sr";
            $criteria->params = [":sr" => $_srno];
            $criteria->order = "id DESC";
            $criteria->limit = 1;
            $_location = Pallot::model()->find($criteria);

            $_str = "<table class='table table-striped table-bordered tbl_invoice_view no_mrgn'>";
            $_str.= "<tr>";
            $_str.= "<th style='16%'>Date</th>";
            $_str.= "<th style='20%'>Pallot Number</th>";
            $_str.= "<th style='16%'>Room</th>";
            $_str.= "<th style='16%'>Floor</th>";
            $_str.= "<th style='16%'>Pocket</th>";
            $_str.= "<th style='16%'>Quantity</th>";
            $_str.= "</tr>";

            if (empty($_location)) {
                $_str.= "<tr><td colspan='6'>No location is set yet.</td></tr>";
                $_str.= "</table>";
            } else {
                foreach ($_location->items as $item) {
                    $_dt = date('d-m-Y', strtotime($item->pallot_date));
                    $_num = $item->pallot->pallot_number;
                    $_room = !empty($item->room) ? LocationRoom::model()->findByPk($item->room)->name : '';
                    $_floor = !empty($item->floor) ? LocationFloor::model()->findByPk($item->floor)->name : '';
                    $_pocket = !empty($item->pocket) ? LocationPocket::model()->findByPk($item->pocket)->name : '';
                    $_quantity = !empty($item->quantity) ? $item->quantity : '';

                    $_str.= "<tr>";
                    $_str.= "<td>{$_dt}</td>";
                    $_str.= "<td>{$_num}</td>";
                    $_str.= "<td>{$_room}</td>";
                    $_str.= "<td>{$_floor}</td>";
                    $_str.= "<td>{$_pocket}</td>";
                    $_str.= "<td>{$_quantity}</td>";
                    $_str.= "</tr>";
                }
                $_str.= "</table>";
            }

            $resp['success'] = true;
            $resp['location'] = $_str;
            $resp['qty'] = $_qty;
            $resp['pallotNo'] = Pallot::model()->lastNumber($_srno);
        } catch (CException $ex) {
            $resp['success'] = false;
            $resp['message'] = $ex->getMessage();
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    public function actionFind_account() {
        $resp = array();
        $_model = new Account();
        $_bid = Yii::app()->request->getPost('bid');

        $criteria = new CDbCriteria();
        $criteria->condition = "bank_id=:bid";
        $criteria->params = [":bid" => $_bid];
        $criteria->order = "account_name ASC";
        $dataset = $_model->findAll($criteria);

        if (!empty($dataset) && count($dataset) > 0) {
            $html = "<option value=''>Select</option>";
            foreach ($dataset as $data) {
                $html .= "<option value='{$data->id}'>{$data->name}</option>";
            }
            $resp['success'] = true;
            $resp['html'] = $html;
        } else {
            $resp['success'] = false;
            $resp['html'] = "<option value=''>No account found.</option>";
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    public function actionSr_loan_info() {
        $resp = array();
        $_srno = Yii::app()->request->getPost('srno');
        $_date = Yii::app()->request->getPost('dt');
        $_dt = !empty($_date) ? date("Y-m-d", strtotime($_date)) : date('Y-m-d');
        $loanSetting = LoanSetting::model()->findByPk(1);

        try {
            if (empty($_srno)) {
                throw new CException(Yii::t("App", "Empty SR number."));
            }

            $srExist = ProductIn::model()->exists("sr_no=:sr", [":sr" => $_srno]);
            if (!$srExist) {
                throw new CException(Yii::t("App", "SR number is invalid or not found."));
            }

            $srinfo = ProductIn::model()->find('sr_no=:sr', [':sr' => $_srno]);
            if (empty($srinfo)) {
                throw new CException(Yii::t("App", "SR number info not found."));
            }
            $carryingReceived = DeliveryItem::model()->sumCarrying($_srno);
            $carryingRemain = ($srinfo->carrying_cost - $carryingReceived);
            if ($carryingRemain < 0) {
                $carryingRemain = 0;
            }

            $resp['product']['lot_no'] = $srinfo->lot_no;
            $resp['product']['qty'] = $srinfo->quantity;
            $resp['product']['agent'] = $srinfo->agent_code;
            $resp['product']['loan_bag'] = !empty($srinfo->loan_pack) ? $srinfo->loan_pack : 0;
            $resp['product']['carrying'] = $carryingRemain;

            $customer = Customer::model()->findByPk($srinfo->customer_id);
            if (!empty($customer)) {
                $resp['customer']['name'] = $customer->name;
                $resp['customer']['father_name'] = $customer->father_name;
                $resp['customer']['village'] = $customer->village;
                $resp['customer']['thana'] = $customer->thana;
                $resp['customer']['dist'] = $customer->district;
            } else {
                $resp['customer']['name'] = '';
                $resp['customer']['father_name'] = '';
                $resp['customer']['village'] = '';
                $resp['customer']['thana'] = '';
                $resp['customer']['dist'] = '';
            }

            $loanItem = LoanItem::model()->find('sr_no=:sr', [':sr' => $_srno]);
            if (!empty($loanItem)) {
                $resp['loan']['item'] = true;
                $resp['loan']['qty'] = AppObject::srLoanRemainQty($_srno);
                $resp['loan']['cost'] = $loanItem->qty_cost;
                $resp['loan']['amount'] = AppObject::currentLoan($_srno);
                $resp['loan']['duration'] = $loanSetting->period;
                $resp['loan']['rate'] = !empty($loanSetting->interest_rate) ? $loanSetting->interest_rate : 0;
                $resp['loan']['day'] = AppHelper::get_day_diff($loanItem->create_date, $_dt);
                $resp['loan']['interest'] = ceil(AppHelper::countInterest($resp['loan']['amount'], $resp['loan']['day'], $loanSetting->interest_rate, $loanSetting->period));
                $resp['loan']['total'] = ($resp['loan']['amount'] + $resp['loan']['interest']);
            } else {
                $resp['loan']['item'] = false;
                $resp['loan']['qty'] = 0;
                $resp['loan']['cost'] = 0;
                $resp['loan']['amount'] = 0;
                $resp['loan']['duration'] = 0;
                $resp['loan']['rate'] = 0;
                $resp['loan']['day'] = 0;
                $resp['loan']['interest'] = 0;
                $resp['loan']['total'] = 0;
            }

            $resp['success'] = true;
        } catch (CException $ex) {
            $resp['success'] = false;
            $resp['message'] = $ex->getMessage();
        }

        echo json_encode($resp);
        return json_encode($resp);
    }

    /* Protected Function */

    protected function hasLoan($_srno) {
        $itemExist = LoanItem::model()->exists("sr_no=:sr", [":sr" => $_srno]);
        if ($itemExist) {
            return true;
        } else {
            return false;
        }
    }

}
