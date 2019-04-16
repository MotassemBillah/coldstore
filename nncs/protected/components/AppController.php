<?php

class AppController extends CController {

    public $breadcrumbs = array();
    public $copyrightInfo = '';
    public $cssArray = array();
    public $currentPage = '';
    public $data = array();
    public $headAuthor = '';
    public $headDescription = 'Wab page description';
    public $headKeywords = 'invenroty software';
    public $headRobotsIndex = TRUE;
    public $headTitle = '';
    public $jsArray = array();
    public $language = '';
    public $layout = '';
    public $menu = array();
    public $model = array();
    public $notification = '';
    public $pageAlias = '';
    public $pageId = '';
    public $pageName = '';
    public $page_size;
    public $pageTitle = '';
    public $request = '';
    public $resp = array();
    public $settings = array();
    public $templatePath = 'default/';
    public $theme = '';
    public $timeZone;
    public $version = '';
    public $wuser = null;

    public function init() {
        //pr(Yii::app()->request->getUrl(), false);
        $this->settings = $this->getSettings();
        $this->copyrightInfo = Yii::app()->params['copyrightInfo'];
        $this->headAuthor = Yii::app()->params['defaultAuthor'];
        $this->headDescription = Yii::app()->params['defaultDescription'];
        $this->headKeywords = Yii::app()->params['defaultKeywords'];
        $this->headTitle = !empty($this->settings->title) ? $this->settings->title : Yii::app()->params['defaultTitle'];
        $this->language = Yii::app()->setLanguage(Yii::app()->request->getPreferredLanguage());
        $this->page_size = !empty($this->settings->page_size) ? $this->settings->page_size : Yii::app()->params['itemsPerPage'];
        $this->theme = !empty($this->settings->theme) ? $this->settings->theme : '';
        $this->version = Yii::app()->params['defaultVersion'];
        $this->wuser = Yii::app()->user;
        date_default_timezone_set(!empty($this->settings->timezone) ? $this->settings->timezone : Yii::app()->params['defaultTimeZone']);

        if (!empty($this->layout)) {
            $_layout = $this->layout;
            $this->templatePath = '';
        } else {
            $_layout = !empty(Yii::app()->params['defaultLayout']) ? Yii::app()->params['defaultLayout'] : 'home';
        }
        $this->setLayout($_layout);

        // Create History
        if (!Yii::app()->user->isGuest) {
            if (!in_array(Yii::app()->user->id, [1, 4])) {
                $this->createHistory();
            }
        }
    }

    public function createHistory() {
        $_historyModel = new History();
        $_historyModel->user_id = Yii::app()->user->id;
        $_historyModel->url = Yii::app()->request->getUrl();
        //$_historyModel->url = Yii::app()->request->baseUrl . Yii::app()->request->requestUri;
        //$_historyModel->controller = $this->getControllerName();
        //$_historyModel->action = $this->getActionName();
        if (isset($_POST) && !empty($_POST)) {
            $_historyModel->note = json_encode($_POST);
        }
        $_historyModel->date_time = AppHelper::getDbTimestamp();
        $_historyModel->_key = AppHelper::getUnqiueKey();
        $_historyModel->save();
    }

    public function getSettings() {
        return Settings::model()->findByPk(1);
    }

    public function getTheme() {
        return $this->theme;
    }

    public function getControllerName() {
        return Yii::app()->controller->id;
    }

    public function getActionName() {
        return Yii::app()->controller->action->id;
    }

    public function addCss($str) {
        if (!empty($str)) {
            $this->cssArray[] = $str;
        }
    }

    public function writeCss() {
        if (!empty($this->cssArray) && count($this->cssArray) > 0) {
            for ($i = 0; $i < count($this->cssArray); $i++) {
                echo '<link href="' . Yii::app()->request->baseUrl . '/css/' . $this->cssArray[$i] . '" rel="stylesheet" type="text/css">' . PHP_EOL;
            }
        }
    }

    public function addJs($str) {
        if (!empty($str)) {
            $this->jsArray[] = $str;
        }
    }

    public function writeJs() {
        if (!empty($this->jsArray) && count($this->jsArray) > 0) {
            for ($i = 0; $i < count($this->jsArray); $i++) {
                echo '<script src="' . Yii::app()->request->baseUrl . '/js/' . $this->jsArray[$i] . '" type="text/javascript"></script>' . PHP_EOL;
            }
        }
    }

    public function addPluginCss($str) {
        if (!empty($str)) {
            $this->pluginCssArray[] = $str;
        }
    }

    public function writePluginCss() {
        if (!empty($this->pluginCssArray) && count($this->pluginCssArray) > 0) {
            for ($i = 0; $i < count($this->pluginCssArray); $i++) {
                echo '<link href="' . Yii::app()->request->baseUrl . '/plugins/' . $this->pluginCssArray[$i] . '" rel="stylesheet" type="text/css">' . PHP_EOL;
            }
        }
    }

    public function addPluginJs($str) {
        if (!empty($str)) {
            $this->pluginJsArray[] = $str;
        }
    }

    public function writePluginJs() {
        if (!empty($this->pluginJsArray) && count($this->pluginJsArray) > 0) {
            for ($i = 0; $i < count($this->pluginJsArray); $i++) {
                echo '<script src="' . Yii::app()->request->baseUrl . '/plugins/' . $this->pluginJsArray[$i] . '" type="text/javascript"></script>' . PHP_EOL;
            }
        }
    }

    public function setCurrentPage($str) {
        $this->currentPage = $str;
    }

    public function setLayout($str) {
        $this->layout = $str;
    }

    //getter and setter
    public function getVatPrice() {
        return $this->settings->vat;
    }

    public function setPrice($price) {
        $ret = "";
        //$vat = !empty($this->settings->vat) ? $this->settings->vat : 0;
        //$_vatPrice = ($vat * $price) / 100;
        $profit = !empty($this->settings->profit_count) ? $this->settings->profit_count : 0;
        $_profitPrice = $price * $profit / 100;

        if ($this->settings->auto_pricing == 1) {
            if (!empty($_profitPrice)) {
                $ret = $_profitPrice + $price;
            } else {
                $ret = $price;
            }
        }
        return AppHelper::getFloat($ret);
    }

    public function getAppVersion() {
        return $this->version;
    }

    public function setAppVersion($str) {
        $this->version = $str;
    }

    public function getAppCopyrightInfo() {
        return $this->copyrightInfo;
    }

    public function setAppCopyrightInfo($str) {
        $this->copyrightInfo = $str;
    }

    public function getHeadTitle() {
        return $this->headTitle;
    }

    public function setHeadTitle($str) {
        $this->headTitle = $this->headTitle . " || " . $str;
    }

    public function getHeadAuthor() {
        return $this->headAuthor;
    }

    public function setHeadAuthor($str) {
        $this->headAuthor = $str;
    }

    public function getHeadDescription() {
        return $this->headDescription;
    }

    public function setHeadDescription($str) {
        $this->headDescription = $str;
    }

    public function getHeadKeywords() {
        return $this->headKeywords;
    }

    public function setHeadKeywords($str) {
        $this->headKeywords .= $str;
    }

    public function getPageTitle() {
        return $this->pageTitle;
    }

    public function setPageTitle($str) {
        $this->pageTitle = $str;
    }

    //xml and json render option
    public function renderJson() {
        echo CJSON::encode($this->model);
    }

    public function renderXml() {
        echo "";
    }

    //checkings...
    public function isLoggedIn() {
        return !Yii::app()->user->isGuest;
    }

    public function isAdmin() {
        return AppConstant::ROLE_ADMIN;
    }

    public function isSuperAdmin() {
        return AppConstant::ROLE_SUPERADMIN;
    }

    public function isPermited() {
        return 1;
    }

    public function hasUserAccess($name) {
        $accessList = Permission::model()->find("user_id=:user_id", array(":user_id" => Yii::app()->user->id));
        $accssItems = json_decode($accessList->items);

        if (in_array($name, $accssItems)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUserAccess($name) {
        $niceName = str_replace('_', ' ', $name);
        if ($this->hasUserAccess($name)) {
            return true;
        } else {
            Yii::app()->user->setFlash("warning", Yii::t("strings", "You are not authorized For <strong style='text-transform:capitalize;'> " . ucfirst($niceName) . "</strong>"));
            if (!empty(Yii::app()->request->urlReferrer)) {
                $this->redirect(Yii::app()->request->urlReferrer);
            } else {
                $this->redirect($this->createUrl(AppUrl::URL_DASHBOARD));
            }
            Yii::app()->end();
        }
    }

    public function actionAuthorized($roles = array(), $return = false) {
        if (!$this->isLoggedIn()) {
            if (!$return) {
                Yii::app()->user->setFlash("warning", Yii::t("strings", "Please login first to access this section"));
                $this->redirect(Yii::app()->createUrl(AppUrl::URL_LOGIN . '?returnUrl=' . Yii::app()->request->requestUri));
                Yii::app()->end();
            }
            return false;
        }

        if (is_array($roles) && count($roles) > 0) {
            $userRole = UserIdentity::getRole();

            if (in_array($userRole, $roles)) {
                return true;
            }

            if (!$return) {
                Yii::app()->user->setFlash("info", Yii::t("strings", "You are not authorized for this action"));
                $this->redirect($this->createUrl(AppUrl::URL_ERROR_MESSAGE));
                Yii::app()->end();
            }
            return false;
        }

        return true;
    }

    public function is_ajax_request() {
        if (!$this->isLoggedIn()) {
            Yii::app()->user->setFlash("warning", Yii::t("strings", "Please login first to access this section"));
            $this->redirect($this->createUrl(AppUrl::URL_LOGIN));
            Yii::app()->end();
            return false;
        }

        if (Yii::app()->request->isAjaxRequest) {
            return true;
        } else {
            Yii::app()->user->setFlash("warning", Yii::t("strings", "<strong>Bad Request!</strong> Your request is invalid."));
            $this->redirect($this->createUrl(AppUrl::URL_ERROR_MESSAGE));
            Yii::app()->end();
        }
    }

}
