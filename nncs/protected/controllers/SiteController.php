<?php

class SiteController extends AppController {

    public $layout = 'home';

    public function actionIndex() {
        $this->redirect($this->createUrl(AppUrl::URL_DASHBOARD));
    }

    public function actionClear_cache() {
        try {
            if (!Yii::app()->cache->flush()) {
                throw new CException(Yii::t("App", "Could not clear all cache data"));
            }

            Yii::app()->user->setFlash("success", "Page refresh successfull");
        } catch (CException $e) {
            Yii::app()->user->setFlash("danger", $e->getMessage());
        }

        if (!empty(Yii::app()->request->urlReferrer)) {
            $this->redirect(Yii::app()->request->urlReferrer);
        } else {
            $this->redirect($this->createUrl(AppUrl::URL_DASHBOARD));
        }
        Yii::app()->end();
    }

}
