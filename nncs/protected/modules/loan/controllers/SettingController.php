<?php

class SettingController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('loan_setting');
        $this->setHeadTitle("Loan Setting");
        $this->setPageTitle("Loan Setting");
        $this->setCurrentPage(AppUrl::URL_LOAN_SETTING);

        $model = new LoanSetting();
        $data = $model->findByPk(1);

        $this->model['model'] = $data;
        $this->render('index', $this->model);
    }

    public function actionUpdate() {
        $this->is_ajax_request();
        $response = [];
        $loanSetting = LoanSetting::model()->findByPk(1);
        $loanSetting->interest_rate = Yii::app()->request->getPost('interest_rate');
        $loanSetting->period = Yii::app()->request->getPost('period');
        $loanSetting->min_day = Yii::app()->request->getPost('min_day');
        $loanSetting->empty_bag_price = Yii::app()->request->getPost('empty_bag_price');
        $loanSetting->max_loan_per_qty = Yii::app()->request->getPost('max_loan_per_qty');
        $loanSetting->max_rent_per_qty = Yii::app()->request->getPost('max_rent_per_qty');

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            if (!$loanSetting->save()) {
                throw new CException(Yii::t('App', "Error while saving data."));
            }

            $_transaction->commit();
            $response['success'] = true;
            $response['message'] = "Record update successfull!";
        } catch (CException $e) {
            $_transaction->rollback();
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        return json_encode($response);
    }

}
