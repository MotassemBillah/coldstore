<?php

class Loan_receiveController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('loan_receive_list');
        $this->setHeadTitle("Loan Receive");
        $this->setPageTitle("Loan Received List");
        $this->setCurrentPage(AppUrl::URL_LOAN_RECEIVE);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/loan/receive_list.js');

        $_model = new LoanReceive();
        $criteria = new CDbCriteria();
        $criteria->order = "receive_date DESC";
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
        $this->checkUserAccess('loan_receive_list');
        $this->setHeadTitle("Loan Receive");
        $this->setPageTitle("Loan Receive");
        $this->setCurrentPage(AppUrl::URL_LOAN_RECEIVE);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

//        $this->render('receive_form', $this->model);
        $this->render('receive_delivery_form', $this->model);
    }

}
