<?php

class LocationController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess('location_list');
        $this->setHeadTitle("Locations");
        $this->setPageTitle("Locations");
        $this->setCurrentPage(AppUrl::URL_LOCATION);

        $_model = new LocationRoom();
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
        $this->checkUserAccess('location_create');
        $this->setHeadTitle("Location");
        $this->setPageTitle("New Location");
        $this->setCurrentPage(AppUrl::URL_LOCATION);

        $_location = new LocationRoom();

        if (isset($_POST['create_location'])) {
            $floorArr = $_POST['floor_no'];

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if (!empty($floorArr[0])) {
                    foreach ($floorArr as $key => $val) {
                        if (!empty($val)) {
                            $_model = new Location();
                            $_model->floor_no = $floorArr[$key];
                            $_model->_key = AppHelper::getUnqiueKey();
                            if (!$_model->save()) {
                                throw new CException(Yii::t("App", "Error while saving data."));
                            }
                        }
                    }
                } else {
                    throw new CException(Yii::t("App", "Enter atleast one floor number."));
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "New record save successfull.");
                $this->redirect(array(AppUrl::URL_LOCATION));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_location;
        $this->render('create', $this->model);
    }

    public function actionEdit($id) {
        $this->checkUserAccess('location_edit');
        $this->setHeadTitle("Location");
        $this->setPageTitle("Edit Location");
        $this->setCurrentPage(AppUrl::URL_LOCATION);

        $_model = new Location();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

        if (isset($_POST['Location'])) {
            AppHelper::pr($_POST);
            $_data->attributes = $_POST['Location'];
            $_data->floor_no = $_POST['Location']['floor_no'];
            $_data->room_no = $_POST['Location']['room_no'];
            $_data->pocket_no = $_POST['Location']['pocket_no'];

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
                $this->redirect(array(AppUrl::URL_LOCATION));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->model['model'] = $_data;
        $this->render('edit', $this->model);
    }

    public function actionCreate_room() {
        $this->checkUserAccess('location_create');
        $this->setHeadTitle("Location");
        $this->setPageTitle("New Location Room");
        $this->setCurrentPage(AppUrl::URL_LOCATION);

        if (isset($_POST['create_room'])) {
            $arrData = $_POST['room_name'];

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if (!empty($arrData[0])) {
                    foreach ($arrData as $key => $val) {
                        if (!empty($val)) {
                            $_model = new LocationRoom();
                            $_model->name = $arrData[$key];
                            $_model->_key = AppHelper::getUnqiueKey() . $key;
                            if (!$_model->save()) {
                                throw new CException(Yii::t("App", "Error while saving data."));
                            }
                        }
                    }
                } else {
                    throw new CException(Yii::t("App", "Enter atleast one room name."));
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "New record save successfull.");
                $this->redirect(array(AppUrl::URL_LOCATION));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->render('add_room', $this->model);
    }

    public function actionCreate_floor() {
        $this->checkUserAccess('location_create');
        $this->setHeadTitle("Location");
        $this->setPageTitle("New Location Floor");
        $this->setCurrentPage(AppUrl::URL_LOCATION);

        if (isset($_POST['create_floor'])) {
            $arrData = $_POST['floor_name'];

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if (!empty($arrData[0])) {
                    foreach ($arrData as $key => $val) {
                        if (!empty($val)) {
                            $_model = new LocationFloor();
                            $_model->room_id = $_POST['room_id'];
                            $_model->name = $arrData[$key];
                            $_model->_key = AppHelper::getUnqiueKey() . $key;
                            if (!$_model->save()) {
                                throw new CException(Yii::t("App", "Error while saving data."));
                            }
                        }
                    }
                } else {
                    throw new CException(Yii::t("App", "Enter atleast one room name."));
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "New record save successfull.");
                $this->redirect(array(AppUrl::URL_LOCATION));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->render('add_floor', $this->model);
    }

    public function actionCreate_pocket() {
        $this->checkUserAccess('location_create');
        $this->setHeadTitle("Location");
        $this->setPageTitle("New Location Pocket");
        $this->setCurrentPage(AppUrl::URL_LOCATION);

        if (isset($_POST['create_pocket'])) {
            $arrData = $_POST['pocket_no'];

            $_transaction = Yii::app()->db->beginTransaction();
            try {
                if (!empty($arrData[0])) {
                    foreach ($arrData as $key => $val) {
                        if (!empty($val)) {
                            $_model = new LocationPocket();
                            $_model->room_id = $_POST['room_id'];
                            $_model->floor_id = $_POST['floor_id'];
                            $_model->name = $arrData[$key];
                            if (!$_model->save()) {
                                throw new CException(Yii::t("App", "Error while saving data."));
                            }
                        }
                    }
                } else {
                    throw new CException(Yii::t("App", "Enter atleast one pocket number."));
                }

                $_transaction->commit();
                Yii::app()->user->setFlash("success", "New record save successfull.");
                $this->redirect(array(AppUrl::URL_LOCATION));
            } catch (CException $e) {
                $_transaction->rollback();
                Yii::app()->user->setFlash("danger", $e->getMessage());
            }
        }

        $this->render('add_pocket', $this->model);
    }

    public function actionDelete($id) {
        $this->checkUserAccess('location_delete');

        $_model = new Location();
        $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            if (empty($_data->id)) {
                throw new Exception(Yii::t("App", "No record found to delete!"));
            }

            if (!empty($_data->rooms)) {
                foreach ($_data->rooms as $room) {
                    if (!empty($room->pockets)) {
                        foreach ($room->pockets as $pocket) {
                            if (!$pocket->delete()) {
                                throw new CException(Yii::t("App", "Error while deleting pocket data."));
                            }
                        }
                    }
                    if (!$room->delete()) {
                        throw new CException(Yii::t("App", "Error while deleting room data."));
                    }
                }
            }

            if (!$_data->delete()) {
                throw new CException(Yii::t("App", "Error while deleting data."));
            }

            $_transaction->commit();
            Yii::app()->user->setFlash("success", 'Record delete successfull!');
        } catch (CException $e) {
            $_transaction->rollback();
            Yii::app()->user->setFlash("danger", $e->getMessage());
        }
        $this->redirect(Yii::app()->request->urlReferrer);
    }

    public function actionUpdate_pocket() {
        $this->is_ajax_request();
        $id = Yii::app()->request->getPost('pktid');
        $name = Yii::app()->request->getPost('pktname');
        $_model = LocationPocket::model()->findByPk($id);

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            $_model->name = $name;
            if (!$_model->save()) {
                throw new CException(Yii::t('App', "Error while updating record."));
            }

            $_transaction->commit();
            $response['success'] = true;
            $response['message'] = "Record update successfull.";
        } catch (CException $e) {
            $_transaction->rollback();
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        return json_encode($response);
    }

    public function actionDelete_pocket($id) {
        $this->is_ajax_request();
        $_model = LocationPocket::model()->findByPk($id);

        $_transaction = Yii::app()->db->beginTransaction();
        try {
            if ($this->pocket_in_use($id)) {
                throw new CException(Yii::t('App', "You cannot delete this pocket. It is already used in pallot."));
            }

            if (!$_model->delete()) {
                throw new CException(Yii::t('App', "Error while updating record."));
            }

            $_transaction->commit();
            $response['success'] = true;
            $response['message'] = "Record delete successfull.";
        } catch (CException $e) {
            $_transaction->rollback();
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        return json_encode($response);
    }

    protected function pocket_in_use($id) {
        $data = PallotItem::model()->exists('pocket=:pkt', [':pkt' => $id]);
        if ($data) {
            return true;
        } else {
            return false;
        }
    }

}
