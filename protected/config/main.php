<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'E-Bantuan SMK',
    'theme' => 'blues',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.rights.*',
        'application.modules.rights.components.*',
        'ext.phpexcelreader.JPhpExcelReader',
		'ext.yii-mail.YiiMailMessage',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'dashboard',
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'generatorPaths' => array(
                'application.gii',
            ),
            'password' => '1',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
        'rights' => array(
            //'install'=>true, // Enables the installer. 
            'superuserName' => 'Admin',
        ),
		
    ),
    // application components
    'components' => array(
        'widgetFactory' => array(
            'widgets' => array(
            )
        ),
		'mail' => array(
			'class' => 'application.extensions.yii-mail.YiiMail',
			'transportType' => 'smtp',
			'transportOptions' => array(
				'host' => 'smtp.gmail.com',
				'username' => 'verifikasi_wilayah@ditpsmk.net',
				'password' => 'adminverifikasi2014',
				'port' => '26',
				'encryption'=>'ssl',
			),
			'viewPath' => 'application.views.mail',
			'logging' => true,
			'dryRun' => false
		),
		'Smtpmail'=>array(
            'class'=>'application.extensions.smtpmail.PHPMailer',
            'Host'=>'smtp.gmail.com',//"mail.yourdomain.com",
            'Username'=>'verifikasi_wilayah@ditpsmk.net',
            'Password'=>'adminverifikasi2014',
            'Mailer'=>'smtp',
            'Port'=>465,//26,
            'SMTPAuth'=>true, 
        ),
        'user' => array(
            'class' => 'WebUser',
            // enable cookie-based authentication
            'loginUrl' => array('/site/login'),
            'logoutUrl' => array('/site/logout'),
            'signupUrl' => array('/site/signup'),
            'allowAutoLogin' => true,
        ),
        'authManager' => array(
            'class' => 'RDbAuthManager', // Provides support authorization item sorting. ...... 
        ),
        // uncomment the following to enable URLs in path-format

        'urlManager' => array(
            'showScriptName' => false,
            'urlFormat' => 'path',
            'rules' => array(
                '/login' => '/site/login',
                '/logout' => '/site/logout',
                '/mReferencess/index/<view>/<parameter>' => '/mReferencess/index/',
                '/mReferencess/create/<view>/<parameter>' => '/mReferencess/create/',
                '/mReferencess/update/<view>/<parameter>/id/<id:\d+>' => '/mReferencess/update/',
                '/mReferencess/view/<view>/id/<id:\d+>' => '/mReferencess/view/',
                '/pengaturanAplikasi/update/<app>' => '/PengaturanAplikasi/update/',
                '/mstrSekolah//update/<id>' => '/PengaturanAplikasi/update/',
                '/pengaturanAplikasi/delete/<app>' => '/PengaturanAplikasi/delete/',
                '/pengaturanGroup/update/<group>' => '/pengaturanGroup/update/',
                '/pengaturanGroup/delete/<group>' => '/pengaturanGroup/delete/',
                '/pengaturanGroup/register/<group>' => '/pengaturanGroup/register/',
                '/user/setAkses/<userid>' => '/user/setAkses/',
				'/MPegawai/delete/nip/<nip:\w+>' => '/MPegawai/delete/',
				/*
				 ingat, /d berarti integer [0-9] kalau /w alphanumeric
				*/
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                /*
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
				*/
            ),
        ),
        'format' => array(
            'class' => 'application.components.Formatter',
            'datetimeFormat' => 'd-m-Y H:i:s',
            'dateFormat' => 'd-m-Y',
            'timeFormat' => 'H:i:s',
            'numberFormat' => array('decimals' => 0, 'decimalSeparator' => '.', 'thousandSeparator' => ','),
        ),
        'uploadImage' => array(
            'class' => 'AxUploadImage',
            'folder' => "Y",
            'basePath' => '/images/',
            'labels' => array(
                'medium' => array('label' => '_M', 'width' => 300, "height" => 400),
                'thumb' => array('label' => '_T', 'width' => 90, "height" => 120),
            ),
            'oLabel' => '',
            'width' => 1200,
            'height' => 1600,
        ),
        
		'db' => array(
			'class' => 'CDbConnection',
            'connectionString' => 'sqlsrv:Server=OBI-PC\SQLEXPRESS;Database=siban',
			'username' => 'sa',
			'password' => 'Bismillah',
			'charset' => 'GB2312',
			//'tablePrefix' => 'dbo',
			//'emulatePrepare' =>false
        ),
		'dbdapodik' => array(
			'class' => 'CDbConnection',
            'connectionString' => 'sqlsrv:Server=OBI-PC\SQLEXPRESS;Database=Dapodikmen',
			'username' => 'sa',
			'password' => 'Bismillah',
			'charset' => 'GB2312',
			//'tablePrefix' => 'dbo',
			//'emulatePrepare' =>false
        ),
		/*'db' => array(
			//'class' => 'CDbConnection',
            'connectionString' => 'sqlsrv:Server=192.168.75.9;Database=siban',
			'username' => 'verwil',
			'password' => 'd1km3n!2015',
			'charset' => 'GB2312',
			//'tablePrefix' => 'dbo',
			//'emulatePrepare' =>false
        ),*/
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
            */ 
            ),
        ),
        'ePdf' => array(
            'class' => 'ext.yii-pdf.EYiiPdf',
            'params' => array(
                'HTML2PDF' => array(
                    'librarySourcePath' => 'application.vendors.html2pdf.*',
                    'classFile' => 'html2pdf.class.php', // For adding to Yii::$classMap
                    'defaultParams' => array(// More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
                        'orientation' => 'P', // landscape or portrait orientation
                        'format' => 'A4', // format A4, A5, ...
                        'language' => 'en', // language: fr, en, it ...
                        'unicode' => true, // TRUE means clustering the input text IS unicode (default = true)
                        'encoding' => 'UTF-8', // charset encoding; Default is UTF-8
                        'marges' => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
                    )
                ),
                /*'mpdf' => array(
                    'librarySourcePath' => 'application.vendors.mpdf.*',
                    'constants' => array(
                        '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                    ),
                    'class' => 'mpdf',
                    'defaultParams' => array(
                        'mode' => '',
                        'format' => 'A4',
                        'default_font_size' => 0, // Sets the default document font size in points (pt)
                        'default_font' => '', // Sets the default font-family for the new document.
                        'mgl' => 15, // margin_left. Sets the page margins for the new document.
                        'mgr' => 15, // margin_right
                        'mgt' => 16, // margin_top
                        'mgb' => 16, // margin_bottom
                        'mgh' => 9, // margin_header
                        'mgf' => 9, // margin_footer
                        'orientation' => 'L', // landscape or portrait orientation
                    ),
                ),*/
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),
);
