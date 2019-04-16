<?php

class HistoryController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        if (!in_array(Yii::app()->user->id, [1, 4])) {
            $this->redirect($this->createUrl(AppUrl::URL_DASHBOARD));
        }
        return true;
    }

    public function actionIndex() {
        $this->setHeadTitle("History");
        $this->setPageTitle("History List");
        $this->setCurrentPage(AppUrl::URL_HISTORY);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new History();
        $criteria = new CDbCriteria();
        $criteria->order = "date_time DESC";
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
        $this->setHeadTitle("History");
        $this->setPageTitle("History View");
        $this->setCurrentPage(AppUrl::URL_HISTORY);

        $_model = new History();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

        $this->model['model'] = $_data;
        $this->render('view', $this->model);
    }

    public function actionDelete($id) {
        $_model = new History();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            if (empty($id)) {
                throw new CException(Yii::t("App", "You are trying to access invalid Url."));
            }

            if (empty($_data->id)) {
                throw new Exception(Yii::t("App", "No record found to delete!"));
            }

            if (!$_data->delete()) {
                throw new CException(Yii::t("App", "Error while deleting record."));
            }

            $_transaction->commit();
            Yii::app()->user->setFlash("success", 'History Record Deleted Successfully.');
        } catch (CException $e) {
            $_transaction->rollback();
            Yii::app()->user->setFlash("danger", $e->getMessage());
        }
        $this->redirect(Yii::app()->request->urlReferrer);
    }

    public function actionDeleteall() {
        $response = array();
        $_model = new History();

        if (isset($_POST['data'])) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < count($_POST['data']); $i++) {
                    $_obj = $_model->findByPk($_POST['data'][$i]);

                    if (!$_obj->delete()) {
                        throw new CException(Yii::t('App', "Error while deleting history record"));
                    }
                }

                $_transaction->commit();
                $response['success'] = true;
                $response['message'] = "Records deleted successfully.";
            } catch (CException $e) {
                $_transaction->rollback();
                $response['success'] = false;
                $response['message'] = $e->getMessage();
            }
        } else {
            $response['success'] = false;
            $response['message'] = "No record found to delete!";
        }

        echo json_encode($response);
        return json_encode($response);
    }

    public function actionClear() {
        $_model = new History();
        $_dataset = $_model->findAll();

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            foreach ($_dataset as $_data) {
                if (!$_data->delete()) {
                    throw new CException(Yii::t("App", "Error while clearing record."));
                }
            }

            $_transaction->commit();
            Yii::app()->user->setFlash("success", 'History Clear Successfull.');
        } catch (CException $e) {
            $_transaction->rollback();
            Yii::app()->user->setFlash("danger", $e->getMessage());
        }
        $this->redirect(Yii::app()->request->urlReferrer);
    }

    /*
     * Ajax search and other responses
     */

    public function actionSearch() {
        $this->is_ajax_request();
        $_limit = Yii::app()->request->getPost('itemCount');
        $_userID = Yii::app()->request->getPost('user');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new History();
        $criteria = new CDbCriteria();
        if (!empty($_userID)) {
            $criteria->addCondition("user_id={$_userID}");
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('date_time', $dateForm, $dateTo);
        }
        $criteria->order = "date_time DESC";
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
