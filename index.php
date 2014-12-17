<?php

	include_once './vendor/sy-frm/Sy.php';

    error_reporting( E_ALL );
    ini_set( 'display_errors', 'On' );

	Sy::newWebApplication(
		Sy::createConfig( APP_PATH . DS .'conf'. DS .'app.ini' )
	);

    \Sy\Autoloader::addIncludeDirs( APP_PATH . DS . 'tables' );
    \Sy\Autoloader::addIncludeDirs( APP_PATH . DS . 'helper' );

	$app = Sy::app();

    try {
        $app->attach( 'auth', new \Sy\Core\Auth() );
    } catch( \Exception $e ) {
        \Sy\Error\Error::critical( $e->getMessage() );
    }

    print $app->run();
