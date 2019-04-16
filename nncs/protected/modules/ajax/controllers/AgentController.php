<?php

class AgentController extends AppController {

    public function beginRequest() {
        if (Yii::app()->request->isAjaxRequest) {
            return true;
        }
        return false;
    }

    public function beforeAction($action) {
        $this->actionAuthorized();
        $this->is_ajax_request();
        return true;
    }

    public function actionIndex() {
        $_limit = Yii::app()->request->getPost('itemCount');
        $_sortType = Yii::app()->request->getPost('sort_type');
        $_sortBy = Yii::app()->request->getPost('sort_by');
        $_search = Yii::app()->request->getPost('search');
        $_agcode = Yii::app()->request->getPost('agcode');

        $_model = new Agent();
        $criteria = new CDbCriteria();
        if (!empty($_search)) {
            $criteria->condition = "name LIKE :match OR father_name LIKE :match OR mobile LIKE :match";
            $criteria->params = array(':match' => "%$_search%");
        }
        if (!empty($_agcode)) {
			$criteria->addCondition("code={$_agcode}");
        }
        if (!empty($_sortBy)) {
            $criteria->order = "{$_sortBy} {$_sortType}";
        } else {
            $criteria->order = "code,name ASC";
        }
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('index', $this->model);
    }

    public function actionDeleteall() {
        $_model = new Agent();
        $response = array();
        $_data = $_POST['data'];

        if (isset($_data)) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < count($_data); $i++) {
                    $_obj = $_model->findByPk($_data[$i]);

                    if (!$_obj->delete()) {
                        throw new CException(Yii::t('App', "Error while deleting record"));
                    }
                }

                $_transaction->commit();
                $response['success'] = true;
                $response['message'] = "Records deleted successfully!";
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

    public function actionLoan() {
        $_cid = Yii::app()->request->getPost('code_no');
        $_limit = Yii::app()->request->getPost('itemCount');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new LoanItem();
        $criteria = new CDbCriteria();
        $criteria->condition = "agent_code=:agcd";
        $criteria->params = array(':agcd' => $_cid);
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('created', $dateForm, $dateTo);
        }
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('loan_list', $this->model);
    }

    public function actionLoan_adv_list() {
        $_cid = Yii::app()->request->getPost('code_no');
        $_limit = Yii::app()->request->getPost('itemCount');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new LoanPaymentAdvance();
        $criteria = new CDbCriteria();
        $criteria->condition = "agent_code=:agcd";
        $criteria->params = array(':agcd' => $_cid);
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('created', $dateForm, $dateTo);
        }
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('adv_loan_list', $this->model);
    }

}
