<?php

class ProfitController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('profit');
        $this->setHeadTitle("Profit");
        $this->setPageTitle("Profit And Loss Statement");
        $this->setCurrentPage(AppUrl::URL_PROFIT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/profit.js');

        $curDate = date('Y-m-d');
        $_model = new Invoice();
        $criteria = new CDbCriteria();
        $criteria->addBetweenCondition('invoice_date', $curDate, $curDate);
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $criteria->order = "invoice_date DESC";
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('index', $this->model);
    }

}
