<?php

Yii::setPathOfAlias('LoanModule', dirname(__FILE__));

class LoanModule extends CWebModule {

    public function init() {
        parent::init();
        $this->layoutPath = Yii::getPathOfAlias('application.modules.loan.views.layouts');

        $this->setImport(array(
            'loan.controllers.*',
        ));
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }

}
