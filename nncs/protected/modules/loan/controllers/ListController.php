<?php

class ListController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('loan_payment_list');
        $this->setHeadTitle("Loan Payment");
        $this->setPageTitle("Loan Payment List");
        $this->setCurrentPage(AppUrl::URL_LOAN_LIST);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new LoanItem();
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

    public function actionUpdate() {
        $itemDataset = LoanItem::model()->findAll();
        foreach ($itemDataset as $itemData) {
            $itemData->type = ProductIn::model()->find('sr_no=:sr', [':sr' => $itemData->sr_no])->type;
            $itemData->create_date = date("Y-m-d", strtotime($itemData->created));
            $itemData->save();
        }
        $this->redirect(array(AppUrl::URL_LOAN_LIST));
    }

    public function actionDuplicate() {
        $this->setHeadTitle("Loan");
        $this->setPageTitle("Duplicate Loan");

        $_dataset = LoanItem::model()->duplicateEntry();

        $this->model['dataset'] = $_dataset;
        $this->render('duplicate', $this->model);
    }

}
