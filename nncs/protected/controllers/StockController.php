<?php

class StockController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('stock_list');
        $this->setHeadTitle("Product Stocks");
        $this->setPageTitle("Product Stocks");
        $this->setCurrentPage(AppUrl::URL_STOCK);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/product/stock.js');

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

    /* Ajax calls */

    public function actionSearch() {
        $this->is_ajax_request();
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
        $this->renderPartial('_list', $this->model);
    }

}
