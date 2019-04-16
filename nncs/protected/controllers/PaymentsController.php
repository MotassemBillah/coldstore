<?php

class PaymentsController extends AppController {

    public $layout = 'admin';

    public function beforeAction($action) {
        $this->actionAuthorized();
        return true;
    }

    public function actionIndex() {
        $this->checkUserAccess("payment_list");
        $this->setHeadTitle("Payments");
        $this->setPageTitle("Payments");
        $this->setCurrentPage(AppUrl::URL_PAYMENT);
        $this->addCss('datepicker.css');
        $this->addJs('datepicker.js');
        $this->addJs('views/payment.js');

        $_model = new ProductIn();
        $criteria = new CDbCriteria();
        $criteria->order = "create_date DESC";
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = $this->page_size;
        $pages->applyLimit($criteria);
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->render('index', $this->model);
    }

    /* public function actionList_loading() {
      $this->checkUserAccess("loading_payment_list");
      $this->setHeadTitle("Payments");
      $this->setPageTitle("Payments For Loading");
      $this->setCurrentPage(AppUrl::URL_PAYMENT_LOADING);
      $this->addCss('datepicker.css');
      $this->addJs('datepicker.js');

      $_model = new PaymentLoading();
      $criteria = new CDbCriteria();
      $criteria->condition = "type='Loading'";
      if (Yii::app()->user->role != AppConstant::ROLE_SUPERADMIN) {
      $criteria->addCondition("created_by = " . Yii::app()->user->id);
      }
      $criteria->order = "id DESC";
      $count = $_model->count($criteria);
      $pages = new CPagination($count);
      $pages->pageSize = $this->page_size;
      $pages->applyLimit($criteria);
      $_dataset = $_model->findAll($criteria);

      $this->model['dataset'] = $_dataset;
      $this->model['pages'] = $pages;
      $this->render('loading_list', $this->model);
      }

      public function actionNew_loading() {
      $this->checkUserAccess("loading_payment_create");
      $this->setHeadTitle("Payments");
      $this->setPageTitle("New Loading Payment");
      $this->setCurrentPage(AppUrl::URL_PAYMENT_LOADING);
      $this->addCss('datepicker.css');
      $this->addJs('datepicker.js');

      $_model = new PaymentLoading();

      if (isset($_POST['PaymentLoading'])) {
      $_date = $_POST['PaymentLoading']['created'];
      $_model->attributes = $_POST['PaymentLoading'];
      $_model->type = "Loading";
      $_model->pament_for = !empty($_POST['PaymentLoading']['pament_for']) ? $_POST['PaymentLoading']['pament_for'] : NULL;
      $_model->created = !empty($_date) ? date("Y-m-d", strtotime($_date)) : AppHelper::getDbDate();
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

      $last_id = Yii::app()->db->getLastInsertId();
      $_modelCashAccount = new CashAccount();
      $_modelCashAccount->payment_load_unload_id = $last_id;
      $_modelCashAccount->purpose = "{$_model->type} cash paid for {$_model->pament_for}";
      $_modelCashAccount->credit = $_POST['PaymentLoading']['price_total'];
      $_modelCashAccount->balance = -($_modelCashAccount->credit);
      $_modelCashAccount->type = 'W';
      $_modelCashAccount->created = AppHelper::getDbTimestamp();
      $_modelCashAccount->created_by = Yii::app()->user->id;
      $_modelCashAccount->_key = AppHelper::getUnqiueKey();
      if (!$_modelCashAccount->save()) {
      throw new CException(Yii::t("App", "Error while saving transaction."));
      }

      $_transaction->commit();
      Yii::app()->user->setFlash("success", "New record save successfull.");
      $this->redirect($this->createUrl(AppUrl::URL_PAYMENT_LOADING));
      } catch (CException $e) {
      $_transaction->rollback();
      Yii::app()->user->setFlash("danger", $e->getMessage());
      }
      }

      $this->model['model'] = $_model;
      $this->render('loading_form', $this->model);
      }

      public function actionEdit_loading($id) {
      $this->checkUserAccess("loading_payment_edit");
      $this->setHeadTitle("Payments");
      $this->setPageTitle("Update Loading Payment");
      $this->setCurrentPage(AppUrl::URL_PAYMENT_LOADING);
      $this->addCss('datepicker.css');
      $this->addJs('datepicker.js');

      $_model = new PaymentLoading();
      $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

      if (isset($_POST['PaymentLoading'])) {
      $_date = $_POST['PaymentLoading']['created'];
      $_data->attributes = $_POST['PaymentLoading'];
      $_data->pament_for = !empty($_POST['PaymentLoading']['pament_for']) ? $_POST['PaymentLoading']['pament_for'] : NULL;
      $_data->created = !empty($_date) ? date("Y-m-d", strtotime($_date)) : AppHelper::getDbDate();
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

      $_modelCashAccount = CashAccount::model()->find('payment_load_unload_id=:lupid', [':lupid' => $_data->id]);
      $_modelCashAccount->credit = $_POST['PaymentLoading']['price_total'];
      $_modelCashAccount->balance = -($_modelCashAccount->credit);
      $_modelCashAccount->modified = AppHelper::getDbTimestamp();
      $_modelCashAccount->modified_by = Yii::app()->user->id;
      if (!$_modelCashAccount->save()) {
      throw new CException(Yii::t("App", "Error while saving transaction."));
      }

      $_transaction->commit();
      Yii::app()->user->setFlash("success", "Record update successfull.");
      $this->redirect($this->createUrl(AppUrl::URL_PAYMENT_LOADING));
      } catch (CException $e) {
      $_transaction->rollback();
      Yii::app()->user->setFlash("danger", $e->getMessage());
      }
      }

      $this->model['model'] = $_data;
      $this->render('loading_form', $this->model);
      }

      public function actionList_unloading() {
      $this->checkUserAccess("loading_payment_list");
      $this->setHeadTitle("Payments");
      $this->setPageTitle("Payments For Unloading");
      $this->setCurrentPage(AppUrl::URL_PAYMENT_UNLOADING);
      $this->addCss('datepicker.css');
      $this->addJs('datepicker.js');

      $_model = new PaymentLoading();
      $criteria = new CDbCriteria();
      $criteria->condition = "type='Unloading'";
      if (Yii::app()->user->role != AppConstant::ROLE_SUPERADMIN) {
      $criteria->addCondition("created_by = " . Yii::app()->user->id);
      }
      $criteria->order = "id DESC";
      $count = $_model->count($criteria);
      $pages = new CPagination($count);
      $pages->pageSize = $this->page_size;
      $pages->applyLimit($criteria);
      $_dataset = $_model->findAll($criteria);

      $this->model['dataset'] = $_dataset;
      $this->model['pages'] = $pages;
      $this->render('unloading_list', $this->model);
      }

      public function actionNew_unloading() {
      $this->checkUserAccess("loading_payment_create");
      $this->setHeadTitle("Payments");
      $this->setPageTitle("New Unloading Payment");
      $this->setCurrentPage(AppUrl::URL_PAYMENT_UNLOADING);
      $this->addCss('datepicker.css');
      $this->addJs('datepicker.js');

      $_model = new PaymentLoading();

      if (isset($_POST['PaymentLoading'])) {
      $_date = $_POST['PaymentLoading']['created'];
      $_model->attributes = $_POST['PaymentLoading'];
      $_model->type = "Unloading";
      $_model->pament_for = !empty($_POST['PaymentLoading']['pament_for']) ? $_POST['PaymentLoading']['pament_for'] : NULL;
      $_model->created = !empty($_date) ? date("Y-m-d", strtotime($_date)) : AppHelper::getDbDate();
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

      $last_id = Yii::app()->db->getLastInsertId();
      $_modelCashAccount = new CashAccount();
      $_modelCashAccount->payment_load_unload_id = $last_id;
      $_modelCashAccount->purpose = "{$_model->type} cash paid for {$_model->pament_for}";
      $_modelCashAccount->credit = $_POST['PaymentLoading']['price_total'];
      $_modelCashAccount->balance = -($_modelCashAccount->credit);
      $_modelCashAccount->type = 'W';
      $_modelCashAccount->created = AppHelper::getDbTimestamp();
      $_modelCashAccount->created_by = Yii::app()->user->id;
      $_modelCashAccount->_key = AppHelper::getUnqiueKey();
      if (!$_modelCashAccount->save()) {
      throw new CException(Yii::t("App", "Error while saving transaction."));
      }

      $_transaction->commit();
      Yii::app()->user->setFlash("success", "New record save successfull.");
      $this->redirect($this->createUrl(AppUrl::URL_PAYMENT_UNLOADING));
      } catch (CException $e) {
      $_transaction->rollback();
      Yii::app()->user->setFlash("danger", $e->getMessage());
      }
      }

      $this->model['model'] = $_model;
      $this->render('loading_form', $this->model);
      }

      public function actionEdit_unloading($id) {
      $this->checkUserAccess("loading_payment_edit");
      $this->setHeadTitle("Payments");
      $this->setPageTitle("Update Loading Payment");
      $this->setCurrentPage(AppUrl::URL_PAYMENT_LOADING);
      $this->addCss('datepicker.css');
      $this->addJs('datepicker.js');

      $_model = new PaymentLoading();
      $_data = $_model->find('LOWER(_key) = ?', array(strtolower($id)));

      if (isset($_POST['PaymentLoading'])) {
      $_date = $_POST['PaymentLoading']['created'];
      $_data->attributes = $_POST['PaymentLoading'];
      $_data->pament_for = !empty($_POST['PaymentLoading']['pament_for']) ? $_POST['PaymentLoading']['pament_for'] : NULL;
      $_data->created = !empty($_date) ? date("Y-m-d", strtotime($_date)) : AppHelper::getDbDate();
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

      $_modelCashAccount = CashAccount::model()->find('payment_load_unload_id=:lupid', [':lupid' => $_data->id]);
      $_modelCashAccount->credit = $_POST['PaymentLoading']['price_total'];
      $_modelCashAccount->balance = -($_modelCashAccount->credit);
      $_modelCashAccount->modified = AppHelper::getDbTimestamp();
      $_modelCashAccount->modified_by = Yii::app()->user->id;
      if (!$_modelCashAccount->save()) {
      throw new CException(Yii::t("App", "Error while saving transaction."));
      }

      $_transaction->commit();
      Yii::app()->user->setFlash("success", "Record update successfull.");
      $this->redirect($this->createUrl(AppUrl::URL_PAYMENT_UNLOADING));
      } catch (CException $e) {
      $_transaction->rollback();
      Yii::app()->user->setFlash("danger", $e->getMessage());
      }
      }

      $this->model['model'] = $_data;
      $this->render('loading_form', $this->model);
      }

      public function actionPallot() {
      $this->checkUserAccess("loading_payment_list");
      $this->setHeadTitle("Payments");
      $this->setPageTitle("Payments For Pallot");
      $this->setCurrentPage(AppUrl::URL_PAYMENT_UNLOADING);
      $this->addCss('datepicker.css');
      $this->addJs('datepicker.js');

      $_model = new PaymentLoading();
      $criteria = new CDbCriteria();
      if (Yii::app()->user->role != AppConstant::ROLE_SUPERADMIN) {
      $criteria->addCondition("created_by = " . Yii::app()->user->id);
      }
      $criteria->order = "id DESC";
      $count = $_model->count($criteria);
      $pages = new CPagination($count);
      $pages->pageSize = $this->page_size;
      $pages->applyLimit($criteria);
      $_dataset = $_model->findAll($criteria);

      $this->model['dataset'] = $_dataset;
      $this->model['pages'] = $pages;
      $this->render('pallot_list', $this->model);
      }

      public function actionNew_pallot() {
      $this->checkUserAccess("loading_payment_create");
      $this->setHeadTitle("Payments");
      $this->setPageTitle("New Pallot Payment");
      $this->setCurrentPage(AppUrl::URL_PAYMENT_PALLOT);
      $this->addCss('datepicker.css');
      $this->addJs('datepicker.js');

      $_model = new PaymentLoading();

      if (isset($_POST['PaymentLoading'])) {
      $_cur_loc_data = [];
      $_new_loc_data = [];
      $_cur_loc_data['stock_id'] = $_POST['cur_stock_id'];
      $_cur_loc_data['room_id'] = $_POST['cur_room'];
      $_cur_loc_data['floor_id'] = $_POST['cur_floor'];
      $_cur_loc_data['pockets'] = $_POST['cur_pockets'];
      $_new_loc_data['stock_id'] = $_POST['cur_stock_id'];
      $_new_loc_data['room_id'] = $_POST['room'];
      $_new_loc_data['floor_id'] = $_POST['floor'];
      $_new_loc_data['pockets'] = $_POST['pockets'];
      $_date = $_POST['PaymentLoading']['created'];

      $_model->attributes = $_POST['PaymentLoading'];
      $_model->type = "Pallot";
      $_model->pament_for = !empty($_POST['PaymentLoading']['pament_for']) ? $_POST['PaymentLoading']['pament_for'] : NULL;
      $_model->sr_no = $_POST['PaymentLoading']['sr_no'];
      $_model->quantity = $_POST['PaymentLoading']['quantity'];
      $_model->quantity_price = $_POST['PaymentLoading']['quantity_price'];
      $_model->price_total = $_POST['PaymentLoading']['price_total'];
      $_model->current_location = json_encode($_cur_loc_data);
      $_model->new_location = json_encode($_new_loc_data);
      $_model->created = !empty($_date) ? date("Y-m-d", strtotime($_date)) : AppHelper::getDbDate();
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

      $last_id = Yii::app()->db->getLastInsertId();
      $stock_location = new StockLocation();
      $stock_location->stock_id = $_POST['cur_stock_id'];
      $stock_location->stock_srno = $_model->sr_no;
      $stock_location->room_id = $_POST['room'];
      $stock_location->floor_id = $_POST['floor'];
      $stock_location->pockets = isset($_POST['pockets']) ? json_encode($_POST['pockets']) : '["No Pocket"]';
      $stock_location->created = $_model->created;
      $stock_location->created_by = Yii::app()->user->id;
      $stock_location->_key = AppHelper::getUnqiueKey();
      if (!$stock_location->save()) {
      throw new CException(Yii::t('App', "Error while saving pocket location data."));
      }

      $_modelCashAccount = new CashAccount();
      $_modelCashAccount->payment_load_unload_id = $last_id;
      $_modelCashAccount->purpose = "Cash paid for {$_model->pament_for}";
      $_modelCashAccount->credit = $_POST['PaymentLoading']['price_total'];
      $_modelCashAccount->balance = -($_modelCashAccount->credit);
      $_modelCashAccount->type = 'W';
      $_modelCashAccount->created = AppHelper::getDbTimestamp();
      $_modelCashAccount->created_by = Yii::app()->user->id;
      $_modelCashAccount->_key = AppHelper::getUnqiueKey();
      if (!$_modelCashAccount->save()) {
      throw new CException(Yii::t("App", "Error while saving transaction."));
      }

      $_transaction->commit();
      Yii::app()->user->setFlash("success", "New record save successfull.");
      $this->redirect($this->createUrl(AppUrl::URL_PAYMENT_PALLOT));
      } catch (CException $e) {
      $_transaction->rollback();
      Yii::app()->user->setFlash("danger", $e->getMessage());
      }
      }

      $this->model['model'] = $_model;
      $this->render('pallot_form', $this->model);
      } */
}
