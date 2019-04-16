<?php

class UserController extends AppController {

    public function beginRequest() {
        if (Yii::app()->request->isAjaxRequest) {
            return true;
        }
        return false;
    }

    public function beforeAction($action) {
        return true;
    }

    public function actionIndex() {
        $this->is_ajax_request();
        $_model = new User();
        $_sortBy = Yii::app()->request->getPost('sort_by');
        $_sortType = Yii::app()->request->getPost('sort_type');
        $_q = Yii::app()->request->getPost('q');
        $_limit = Yii::app()->request->getPost('itemCount');

        $criteria = new CDbCriteria();
        $count = $_model->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = !empty($_limit) ? $_limit : $this->page_size;
        $pages->applyLimit($criteria);
        $criteria->condition = "id<>:id";
        $criteria->addCondition("deletable=1");
        $criteria->params = array(":id" => Yii::app()->user->id);
        if (!empty($_q)) {
            $criteria->condition = "login LIKE :match OR email LIKE :match";
            $criteria->params = array(':match' => "%$_q%");
        }
        if (!empty($_sortBy)) {
            $criteria->order = "{$_sortBy} {$_sortType}";
        } else {
            $criteria->order = "display_name ASC";
        }
        $_dataset = $_model->findAll($criteria);

        $this->model['dataset'] = $_dataset;
        $this->model['pages'] = $pages;
        $this->renderPartial('index', $this->model);
    }

    public function actionLogin() {
        $_resp = new AjaxResponse($this);

        $_login = AppHelper::getCleanValue($_POST['LoginForm']['username']);
        $_password = AppHelper::getCleanValue($_POST['LoginForm']['password']);

        $_objUser = new AppUser;

        if ($_objUser->auth($_login, $_password)) {
            $_resp->message = 'Logged in successfull. Redirecting...';
            $_resp->success = true;
        } else {
            $_resp->error = true;
            $_resp->exception = $_objUser->errorMessage;
        }

        $this->model = $_resp;

        $this->renderJson();
    }

    public function actionSave() {
        $_resp = new AjaxResponse($this);

        $_userKey = AppHelper::getValue($_POST['hidUserKey']);
        $_firstName = AppHelper::getCleanValue($_POST['txtFirstName']);
        $_lastName = AppHelper::getCleanValue($_POST['txtLastName']);
        $_phone = AppHelper::getValue($_POST['txtPhone']);
        $_roleId = AppHelper::getValue($_POST['ddlRole']);
        $_ip = AppHelper::getCleanValue($_POST['txtIP']);
        if (empty($_roleId))
            $_roleId = AppConstant::USER_ROLE_NORMAL;

        $_objUserModel = new User;
        $_objUser = $_objUserModel->find('user_key = :key', array('key' => $_userKey));

        if (!empty($_objUser->user_id)) {
            $_objUser->user_first_name = $_firstName;
            $_objUser->user_last_name = $_lastName;
            $_objUser->user_phone = $_phone;
            $_objUser->user_ip = $_ip;

            if ($_objUser->user_id != Yii::app()->user->id)
                $_objUser->user_role = $_roleId;

            if ($_objUser->save()) {
                $_resp->message = 'Information saved successfully.';
                $_resp->success = true;
            } else {
                $_resp->error = true;
                $_resp->exception = "Error while saving information.";
            }
        } else {
            $_resp->error = true;
            $_resp->exception = "Error while saving information.";
        }

        $this->model = $_resp;

        $this->renderJson();
    }

    public function actionCreate() {
        $_resp = new AjaxResponse($this);

        $_login = AppHelper::getCleanValue($_POST['txtUser']);
        $_email = AppHelper::getCleanValue($_POST['txtEmail']);
        $_firstName = AppHelper::getCleanValue($_POST['txtFirstName']);
        $_lastName = AppHelper::getCleanValue($_POST['txtLastName']);
        $_password = AppHelper::getValue($_POST['txtPassword']);
        $_repeatPassword = AppHelper::getValue($_POST['txtRepeatPassword']);
        $_ip = AppHelper::getValue($_POST['txtIP']);
        $_roleId = AppHelper::getValue($_POST['ddlRole']);

        $_objUserModel = new User;
        $_objUser = new AppUser;

        $_objUserModel->user_login = $_login;
        $_objUserModel->user_email = $_email;
        $_objUserModel->user_first_name = $_firstName;
        $_objUserModel->user_last_name = $_lastName;
        $_objUserModel->user_password = $_password;
        $_objUserModel->user_ip = $_ip;

        if (empty($_roleId))
            $_roleId = AppConstant::USER_ROLE_NORMAL;

        $_objUserModel->user_role = $_roleId;

        $_objUser->emailVerify = false;

        if ($_objUser->register($_objUserModel, $_repeatPassword)) {
            $_resp->message = 'You are created successfully.';
            $_resp->success = true;
        } else {
            $_resp->error = true;
            $_resp->exception = $_objUser->errorMessage;
        }

        $this->model = $_resp;

        $this->renderJson();
    }

    public function actionRegister() {
        $_resp = new AjaxResponse($this);

        $_login = AppHelper::getCleanValue($_POST['txtUser']);
        $_email = AppHelper::getCleanValue($_POST['txtEmail']);
        $_firstName = AppHelper::getCleanValue($_POST['txtFirstName']);
        //$_lastName = AppHelper::getCleanValue($_POST['txtLastName']);
        $_password = AppHelper::getValue($_POST['txtPassword']);
        $_repeatPassword = AppHelper::getValue($_POST['txtRepeatPassword']);

        $_objUserModel = new User;
        $_objUser = new AppUser;

        $_objUserModel->user_login = $_login;
        $_objUserModel->user_email = $_email;
        $_objUserModel->user_first_name = $_firstName;
        //$_objUserModel->user_last_name = $_lastName;
        $_objUserModel->user_password = $_password;

        $_objUser->emailVerify = true;

        if ($_objUser->register($_objUserModel, $_repeatPassword)) {
            $_resp->message = 'You are registered successfully. Please check your email to verify.';
            $_resp->success = true;
        } else {
            $_resp->error = true;
            $_resp->exception = $_objUser->errorMessage;
        }

        $this->model = $_resp;

        $this->renderJson();
    }

    public function actionCheckuser() {
        $_userId = AppHelper::getCleanValue($_GET['txtUser']);

        $_objUser = new AppUser;
        $this->model = $_objUser->checkUserId($_userId);

        $this->renderJson();
    }

    public function actionCheckemail() {
        $_userId = AppHelper::getCleanValue($_GET['txtEmail']);

        $_objUser = new AppUser;
        $this->model = $_objUser->checkEmail($_userId);

        $this->renderJson();
    }

    public function actionList() {
        $_model = new User();
        $criteria = new CDbCriteria();
        $_q = Yii::app()->request->getParam('q');
        $criteria->order = "id DESC";

        if (!empty($_q)) {
            $criteria->addCondition("CONCAT(login,' ',email ) like '%" . trim($_q) . "%'");
        }

        $_dataset = $_model->findAll($criteria);
        $this->model['dataset'] = $_dataset;
        $this->renderPartial('index', $this->model);
    }

    public function actionDeleteall() {
        $_model = new User();
        $response = array();
        $_data = $_POST['data'];

        if (isset($_data)) {
            $_transaction = Yii::app()->db->beginTransaction();
            try {
                for ($i = 0; $i < count($_data); $i++) {
                    $_obj = $_model->findByPk($_data[$i]);

                    if ($_obj->deletable == 0) {
                        throw new CException(Yii::t('App', "You cannot delete a superadmin user"));
                    }

                    if (!empty($_obj->profile)) {
                        if (!$_obj->profile->delete()) {
                            throw new CException(Yii::t('App', "Error while deleting record {profile}"));
                        }
                    }

                    if (!empty($_obj->access_item)) {
                        if (!$_obj->access_item->delete()) {
                            throw new CException(Yii::t('App', "Error while deleting record {access items}"));
                        }
                    }

//                    if (!empty($_obj->logins)) {
//                        if (!$_obj->logins->delete()) {
//                            throw new CException(Yii::t('App', "Error while deleting record {logins}"));
//                        }
//                    }

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
