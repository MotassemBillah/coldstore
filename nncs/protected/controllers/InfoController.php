<?php

class InfoController extends AppController {

    public $layout = 'home';

    public function beforeAction($action) {
        return true;
    }

    public function actionIndex() {
        $this->setHeadTitle("Server PHP Information");
        phpinfo();
    }

}
