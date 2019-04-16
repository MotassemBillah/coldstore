<?php

class BalancesheetController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('balance_sheet');
        $this->setHeadTitle("Ledger Balancesheet");
        $this->setPageTitle("Ledger Balancesheet");
        $this->setCurrentPage(AppUrl::URL_LEDGER_BALANCE_SHEET);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new CashAccount();
        $criteria = new CDbCriteria();
        $criteria->order = "created ASC";
        $criteria->group = "created";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('index', $this->model);
    }

    public function actionSearch() {
        $this->is_ajax_request();
        $_limit = Yii::app()->request->getPost('itemCount');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new CashAccount();
        $criteria = new CDbCriteria();
        $criteria->order = "created ASC";
        $criteria->group = "created";
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('pay_date', $dateForm, $dateTo);
        }
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('search', $this->model);
    }

    public function actionUpdate() {
        $_model = new CashAccount();
        $criteria = new CDbCriteria();
        $criteria->order = "created ASC";
        $criteria->group = "created";
        $_dataset = $_model->findAll($criteria);

        $openingBalance = 0;
        $prevDayDebit = 0;
        $prevDayCredit = 0;

        $_counter = 0;
        foreach ($_dataset as $data) {
            $_counter++;
            $_debit = AppObject::balancesheetSumDebit(date('Y-m-d', strtotime($data->created)));
            $_credit = AppObject::balancesheetSumCredit(date('Y-m-d', strtotime($data->created)));
            $prevDay = date('Y-m-d', strtotime($data->created . ' -1 day'));
            $prevDayDebit = AppObject::balancesheetSumDebit($prevDay);
            $prevDayCredit = AppObject::balancesheetSumCredit($prevDay);
            $openingBalance = (($openingBalance + $prevDayDebit) - $prevDayCredit);
            $closingBalance = (($openingBalance + $_debit) - $_credit);
            $data->opening_balance = $openingBalance;
            $data->closing_balance = $closingBalance;
            if ($data->save()) {
                echo "{$_counter} => Saved <br>";
            } else {
                echo "{$_counter} => Failed <br>";
            }
        }
        exit;
    }

}
