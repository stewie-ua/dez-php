<?php
	
	$domain = explode( '.', $_SERVER['HTTP_HOST'] );
	define( 'LOCALHOST', ( 'local' === end( $domain ) || $_SERVER['REMOTE_ADDR'] === '127.0.0.1' ) );

    define( 'GOD_MODE', LOCALHOST && true );

	define( 'DS', DIRECTORY_SEPARATOR );
	define( 'SY_PATH', __DIR__ );

    define( 'ERRORS_DIR', SY_PATH . DS . 'errors' );

	define( 'APP_PATH', preg_replace( '/\\/+/', DS, dirname( $_SERVER['SCRIPT_FILENAME'] ) ) );
	define( 'CACHE_DIR', APP_PATH . DS . 'cache' );
	define( 'LOGS_DIR', APP_PATH . DS . 'logs' );

	
	define( 'SY_VERVION', '1.0-rc' );
	define( 'SY_NAME', 'SyFramework' );
	define( 'SY_CODENAME', 'Tits' );
	define( 'SY_AUTHOR', 'Ivan Gontarenko' );