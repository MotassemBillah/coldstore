<?php

class SrController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('entry_list');
        $this->setHeadTitle("Entry");
        $this->setPageTitle("SR List");
        $this->setCurrentPage(AppUrl::URL_PRODUCT_IN);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        //$this->addJs('views/product/in.js');

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
        $this->render('index', $this->model);
    }

    public function actionView($id) {
        //$this->checkUserAccess('delivery_view');
        $this->setHeadTitle("SR");
        $this->setPageTitle("SR Detail");
        $this->setCurrentPage(AppUrl::URL_SR);

        if (empty($id)) {
            Yii::app()->user->setFlash("warning", "You are trying to access an invalid url.");
            $this->redirect(Yii::app()->createUrl(AppUrl::URL_SR));
        }

        $_modelDeliveryItem = new DeliveryItem();
        $criteria = new CDbCriteria();
        $criteria->condition = "sr_no=:sr";
        $criteria->params = [':sr' => $id];
        $criteria->order = "delivery_date ASC";
        $deliveryItems = $_modelDeliveryItem->findAll($criteria);

        $_modelLoanReceiveItem = new LoanReceiveItem();
        $criteriaLoan = new CDbCriteria();
        $criteriaLoan->condition = "sr_no=:sr";
        $criteriaLoan->params = [':sr' => $id];
        $criteriaLoan->order = "receive_date ASC";
        $loanReceiveItems = $_modelLoanReceiveItem->findAll($criteriaLoan);

        $this->model['srinfo'] = ProductIn::model()->find("sr_no=:sr", [":sr" => $id]);
        $this->model['loanItem'] = LoanItem::model()->find("sr_no=:sr", [":sr" => $id]);
        $this->model['deliveryItems'] = $deliveryItems;
        $this->model['loanReceiveItems'] = $loanReceiveItems;
        $this->model['loanSetting'] = LoanSetting ::model()->findByPk(1);
        $this->render('view_nncs', $this->model);
    }

    // Ajax Search
    public function actionSearch() {
        $_limit = Yii::app()->request->getPost('itemCount');
        $_userID = Yii::app()->request->getPost('user');
        $_sortType = Yii::app()->request->getPost('sort_type');
        $_sortBy = Yii::app()->request->getPost('sort_by');
        $_srno = Yii::app()->request->getPost('srno');

        $_model = new ProductIn();
        $criteria = new CDbCriteria();
        if (!empty($_userID)) {
            $criteria->addCondition("created_by={$_userID}");
        }
        if (!empty($_srno)) {
            $criteria->addCondition("sr_no={$_srno}");
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
        $this->renderPartial('_list', $this->model);
    }

}
