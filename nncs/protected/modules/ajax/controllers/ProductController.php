<?php

class ProductController extends AppController {

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
        $_sort = Yii::app()->request->getPost('itemSort');
        $_sortBy = Yii::app()->request->getPost('sort_by');
        $_sortType = Yii::app()->request->getPost('sort_type');
        $_search = Yii::app()->request->getPost('search');

        $_model = new Agent();
        $criteria = new CDbCriteria();
        if (!empty($_search)) {
            $criteria->condition = "name LIKE :match OR father_name LIKE :match OR mobile LIKE :match";
            $criteria->params = array(':match' => "%$_search%");
        }
        if (!empty($_sort) && $_sort != "ALL") {
            $criteria->addCondition("name LIKE '$_sort%'");
        }
        if (!empty($_sortBy)) {
            $criteria->order = "{$_sortBy} {$_sortType}";
        } else {
            $criteria->order = "name ASC";
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

    public function actionType() {
        $_limit = Yii::app()->request->getPost('itemCount');
        $_search = Yii::app()->request->getPost('search');

        $_model = new ProductType();
        $criteria = new CDbCriteria();
        if (!empty($_search)) {
            $criteria->condition = "name LIKE :match";
            $criteria->params = array(":match" => "%$_search%");
        }
        $criteria->order = "name ASC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('type_list', $this->model);
    }

    public function actionDeleteall_type() {
        $_model = new ProductType();
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

}
