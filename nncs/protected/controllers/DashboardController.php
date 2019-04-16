<?php

class DashboardController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->setHeadTitle("Dashboard");
        $this->setPageTitle("Dashboard");
        $this->setCurrentPage(AppUrl::URL_DASHBOARD);
        $this->render('index', $this->model);
    }

}
