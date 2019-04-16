<?php

class PallotController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess("pallot_list");
        $this->setHeadTitle("Pallots");
        $this->setPageTitle("Pallots");
        $this->setCurrentPage(AppUrl::URL_PALLOT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/pallot_list.js');

        $_model = new Pallot();
        $criteria = new CDbCriteria();
        $criteria->order = "sr_no ASC";
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
        $this->checkUserAccess("pallot_create");
        $this->setHeadTitle("Pallots");
        $this->setPageTitle("New Pallot Form");
        $this->setCurrentPage(AppUrl::URL_PALLOT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');

        $_model = new Pallot();
        $_modelItem = new PallotItem();

        if (isset($_POST['btnSubmit'])) {
            $_model->pallot_date = date("Y-m-d", strtotime($_POST['Pallot']['pallot_date']));
            $_model->pallot_number = $_POST['Pallot']['pallot_number'];
            $_model->sr_no = $_POST['sr_no'];
            $_model->sum_qty = $_POST['sum_quantity'];
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
                $pallotID = Yii::app()->db->getLastInsertId();

                $rooms = $_POST['PallotItem']['room'];
                if (empty($rooms)) {
                    throw new CException(Yii::t("App", "Atleast one room need to be selected."));
                }

                if (!empty($rooms)) {
                    foreach ($rooms as $key => $val) {
                        if (!empty($rooms[$key])) {
                            $roomName = LocationRoom::model()->findByPk($val)->name;
                            if (empty($_POST['PallotItem']['floor'][$key])) {
                                throw new CException(Yii::t("App", "Floor need to be selected for {$roomName}"));
                            }

                            $floorName = LocationFloor::model()->findByPk($_POST['PallotItem']['floor'][$key])->name;
                            if (empty($_POST['PallotItem']['pocket'][$key])) {
                                throw new CException(Yii::t("App", "Pocket need to be selected for {$floorName}"));
                            }

                            $_modelItem = new PallotItem();
                            $_modelItem->pallot_id = $pallotID;
                            $_modelItem->pallot_date = $_model->pallot_date;
                            $_modelItem->sr_no = $_model->sr_no;
                            $_modelItem->room = $val;
                            $_modelItem->floor = $_POST['PallotItem']['floor'][$key];
                            $_modelItem->pocket = $_POST['PallotItem']['pocket'][$key];
                            $_modelItem->quantity = $_POST['PallotItem']['quantity'][$key];
                            $_modelItem->created = AppHelper::getDbTimestamp();
                            $_modelItem->created_by = Yii::app()->user->id;
                            $_modelItem->_key = AppHelper::getUnqiueKey() . $key;
                            if (!$_modelItem->validate()) {
                                throw new CException(Yii::t("App", CHtml::errorSummary($_modelItem)));
                            }
                            if (!$_modelItem->save()) {
                                throw new CException(Yii::t("App", "Error while saving item data."));
                            }
                        }
                    }
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "New record save successfull.");
                $this->redirect($this->createUrl(AppUrl::URL_PALLOT));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_model;
        $this->model['modelItem'] = $_modelItem;
        $this->render('_form', $this->model);
    }

    // Ajax calls and search
    public function actionSearch() {
        $this->is_ajax_request();
        $_limit = Yii::app()->request->getPost('itemCount');
        $_user = Yii::app()->request->getPost('user');
        $_from = Yii::app()->request->getPost('from_date');
        $_to = Yii::app()->request->getPost('to_date');
        $_srno = Yii::app()->request->getPost('srno');
        $dateForm = date("Y-m-d", strtotime($_from));
        $dateTo = !empty($_to) ? date("Y-m-d", strtotime($_to)) : date("Y-m-d");

        $_model = new Pallot();
        $criteria = new CDbCriteria();
        if (!empty($_user)) {
            $criteria->addCondition("created_by={$_user}");
        }
        if (!empty($_from) || !empty($_to)) {
            $criteria->addBetweenCondition('pallot_date', $dateForm, $dateTo);
        }
        if (!empty($_srno)) {
            $criteria->addCondition("sr_no={$_srno}");
        }
        $criteria->order = "pallot_date DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('_view', $this->model);
    }

    public function actionDeleteall() {
        $response = array();
        $_model = new Pallot();

        if (isset($_POST['data'])) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < count($_POST['data']); $i++) {
                    $_obj = $_model->findByPk($_POST['data'][$i]);

                    if (!empty($_obj->items)) {
                        foreach ($_obj->items as $item) {
                            if (!$item->delete()) {
                                throw new CException(Yii::t('App', "Error while deleting item."));
                            }
                        }
                    }

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
