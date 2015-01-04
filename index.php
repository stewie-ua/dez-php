<?php

	include_once './vendor/dez-framework/Dez.php';
    include_once './constants.php';

    error_reporting( E_ALL );
    ini_set( 'display_errors', 'On' );

	Dez::newWebApplication(
		Dez::createConfig( APP_PATH . DS .'conf'. DS .'app.ini' )
	);

    \Dez\Autoloader::addIncludeDirs( APP_PATH . DS . 'tables' );
    \Dez\Autoloader::addIncludeDirs( APP_PATH . DS . 'helper' );

	$app = dez::app()->init();

    print $app->run();
