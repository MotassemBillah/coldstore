<?php

class Product_typeController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('product_type_list');
        $this->setHeadTitle("Prodct Type");
        $this->setPageTitle("Prodct Type List");
        $this->setCurrentPage(AppUrl::URL_PRODUCT_TYPE);
        $this->addJs('views/product/type_list.js');

        $_model = new ProductType();
        $criteria = new CDbCriteria();
        $criteria->order = "name ASC";
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
        $this->checkUserAccess('product_type_create');
        $this->setHeadTitle("Agents");
        $this->setPageTitle("Create Agent");
        $this->setCurrentPage(AppUrl::URL_PRODUCT_TYPE);

        $_model = new ProductType();

        if (isset($_POST['ProductType'])) {
            $_model->attributes = $_POST['ProductType'];
            $_model->name = ucwords($_POST['ProductType']['name']);
            $_model->created = AppHelper::getDbTimestamp();
            $_model->created_by = Yii::app()->user->id;
            $_model->_key = AppHelper::getUnqiueKey();

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if (!$_model->validate()) {
                    throw new CException(Yii::t("App", CHtml::errorSummary($_model)));
                }
                if (!$_model->save()) {
                    throw new CException(Yii::t("App", "Error while saving data."));
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "New record save successfull.");
                $this->redirect(array(AppUrl::URL_PRODUCT_TYPE));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_model;
        $this->render('form', $this->model);
    }

    public function actionEdit($id) {
        $this->checkUserAccess('product_type_edit');
        $this->setHeadTitle("Agents");
        $this->setPageTitle("Edit Agent");
        $this->setCurrentPage(AppUrl::URL_AGENT);

        $_model = new ProductType();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

        if (isset($_POST['ProductType'])) {
            $_data->attributes = $_POST['ProductType'];
            $_data->name = ucwords($_POST['ProductType']['name']);
            $_data->modified = AppHelper::getDbTimestamp();
            $_data->modified_by = Yii::app()->user->id;

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if (!$_data->validate()) {
                    throw new CException(Yii::t("App", CHtml::errorSummary($_data)));
                }
                if (!$_data->save()) {
                    throw new CException(Yii::t("App", "Error while saving data."));
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "Record update successfull.");
                $this->redirect(array(AppUrl::URL_PRODUCT_TYPE));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_data;
        $this->render('form', $this->model);
    }

    /* Ajax calls */

    public function actionSearch() {
        $this->is_ajax_request();
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
        $this->renderPartial('_list', $this->model);
    }

    public function actionDeleteall() {
        $this->is_ajax_request();
        $response = array();
        $_data = $_POST['data'];
        $_model = new ProductType();

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
