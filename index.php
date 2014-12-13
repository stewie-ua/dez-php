<?php

	include_once './vendor/sy-frm/Sy.php';

	Sy::newWebApplication(
		Sy::createConfig( APP_PATH . DS .'conf'. DS .'app.ini' )
	);

    \Sy\Autoloader::addIncludeDirs( APP_PATH . DS . 'tables' );
    \Sy\Autoloader::addIncludeDirs( APP_PATH . DS . 'helper' );

	$app = Sy::app();

    try {
        $app->attachObject( 'auth', new \Sy\Core\Auth() );
    } catch( \Exception $e ) {
        \Sy\Error\Error::critical( $e->getMessage() );
    }

    $app->get( 'phpinfo', function(){ die( phpinfo() ); } );

    print $app->run();
