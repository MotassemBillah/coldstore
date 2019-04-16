<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Cold Store Management',
    'preload' => array('log', 'cache'),
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.extensions.yii-mail.*',
        'application.extensions.mandrill.*',
    ),
    'defaultController' => 'login',
    'charset' => 'UTF-8',
    'sourceLanguage' => 'en',
    'language' => 'en',
    'components' => array(
        'db' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=localhost;dbname=coldstore_nncs',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => '',
            'emulatePrepare' => true,
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
        'user' => array(
            'class' => 'WebUser',
            'allowAutoLogin' => true,
            'identityCookie' => array(
                'path' => '/nncs',
                'domain' => '.coldstore.com',
            ),
        ),
        'session' => array(
            'cookieMode' => 'allow',
            'cookieParams' => array(
                'path' => '/nncs',
                'domain' => '.coldstore.com',
            ),
        ),
        'cache' => array(
            'class' => 'system.caching.CDbCache'
        ),
        'errorHandler' => array(
            'errorAction' => 'error/index',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'cacheID' => 'cache',
            'showScriptName' => false,
            'appendParams' => true,
            'urlSuffix' => '',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CWebLogRoute',
                    'levels' => 'error, info, warning',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                array(
                    'class' => 'CProfileLogRoute',
                    'enabled' => false,
                ),
            ),
        ),
    ),
    'params' => include(dirname(__FILE__) . '/params.php'),
    'modules' => array(
        'ajax' => array(
            'defaultController' => 'user',
            'class' => 'application.modules.ajax.AjaxModule',
        ),
        'ledger' => array(
            'defaultController' => 'balancesheet',
            'class' => 'application.modules.ledger.LedgerModule',
        ),
    /* 'loan' => array(
      'defaultController' => 'list',
      'class' => 'application.modules.loan.LoanModule',
      ), */
    ),
);
