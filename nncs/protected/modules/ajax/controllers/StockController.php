<?php

class StockController extends AppController {

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
        $_userID = Yii::app()->request->getPost('user');
        $_typeID = Yii::app()->request->getPost('type');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
		$_customer_name = Yii::app()->request->getPost('customer_name');
        $_search = Yii::app()->request->getPost('q');
        $_agent = Yii::app()->request->getPost('agent');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");
        $_sortType = Yii::app()->request->getPost('sort_type');
        $_sortBy = Yii::app()->request->getPost('sort_by');
        $_officeCode = Yii::app()->request->getPost('office_code');

        $_model = new ProductIn();
        $criteria = new CDbCriteria();
        if (Yii::app()->user->role != AppConstant::ROLE_SUPERADMIN) {
            $criteria->addCondition("created_by =" . Yii::app()->user->id);
        }
        if (!empty($_userID)) {
            $criteria->addCondition("created_by =" . $_userID);
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
            $criteria->addCondition("type =" . $_typeID);
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('create_date', $dateForm, $dateTo);
        }
        if (!empty($_search)) {
            $criteria->addCondition("sr_no=" . $_search);
        }
        if (!empty($_agent)) {
            $criteria->addCondition("agent_code=" . $_agent);
            $this->model['agentTotal'] = AppObject::stockOfAgent($_agent);
        } else {
            $this->model['agentTotal'] = '';
        }
        $this->model['officeStock'] = '';
        if (isset($_officeCode)) {
            $criteria->addCondition("agent_code IS NULL OR agent_code=0");
            $this->model['officeStock'] = AppObject::stockOfice($_agent);
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
        $this->renderPartial('index', $this->model);
    }

    public function actionLocation_form() {
        $_id = Yii::app()->request->getParam('did');
        $_info = Yii::app()->request->getParam('info');

        $_criteria = new CDbCriteria();
        $_criteria->condition = "stock_id=:stid";
        $_criteria->params = [":stid" => $_id];
        $_criteria->order = "id DESC";
        $_criteria->limit = 1;
        $_location = StockLocation::model()->find($_criteria);

        $this->model['stockID'] = $_id;
        $this->model['modatTitle'] = ($_info == 'add') ? "Add Location" : "Change Location";
        $this->model['btnText'] = ($_info == 'add') ? "Save" : "Update";
        $this->model['locationInfo'] = !empty($_location) ? $_location : "";
        $this->renderPartial('location', $this->model);
    }

    public function actionLocation_save() {
        $response = [];
        $_stockID = Yii::app()->request->getPost('stockID');

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            $stock = Stock::model()->findByPk($_stockID);
            if (empty($stock->id)) {
                throw new CException(Yii::t('App', "No record found to update."));
            }

            $stock_location = new StockLocation();
            $stock_location->stock_id = $stock->id;
            $stock_location->stock_srno = $stock->sr_no;
            $stock_location->room_id = $_POST['room'];
            $stock_location->floor_id = $_POST['floor'];
            $stock_location->pockets = isset($_POST['pockets']) ? json_encode($_POST['pockets']) : '["No Pocket"]';
            $stock_location->created_by = Yii::app()->user->id;
            $stock_location->_key = AppHelper::getUnqiueKey();
            if (!$stock_location->save()) {
                throw new CException(Yii::t('App', "Error while saving pocket location data."));
            }

            $_transaction->commit();
            $response['success'] = true;
            $response['message'] = "Record update successfull.";
        } catch (CException $e) {
            $_transaction->rollback();
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        return json_encode($response);
    }

    public function actionCustomer_stock() {
        $_id = Yii::app()->request->getParam('cid');
        $stocks = ProductIn::model()->findAll('customer_id=:customer_id', [':customer_id' => $_id]);
        foreach ($stocks as $stock) {
            echo "Customer = " . $stock->customer->name . "<br>";
            echo "Sr = " . $stock->sr_no . "<br>";
            echo "Stock = " . AppObject::currentStock($stock->sr_no) . "<br>";
            echo "===============================<br>";
        }
        AppHelper::pr($stocks);
        //$this->model['stockID'] = $_id;
        //$this->renderPartial('location', $this->model);
    }

    /*
     * protected functions for
     * delete related model data
     */

    protected function delete_stock($_object) {
        if (!empty($_object->stocks)) {
            foreach ($_object->stocks as $_data) {
                if (!$_data->delete()) {
                    throw new CException(Yii::t("App", "Error while deleting stocks."));
                }
            }
        }
    }

}
