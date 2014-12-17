<?php
	
	$domain = explode( '.', $_SERVER['HTTP_HOST'] );
	define( 'LOCALHOST', ( 'local' === end( $domain ) || $_SERVER['REMOTE_ADDR'] === '127.0.0.1' ) );

    define( 'GOD_MODE', LOCALHOST && true );

	define( 'DS',           DIRECTORY_SEPARATOR );
	define( 'DEZ_PATH',      __DIR__ );

    define( 'ERRORS_DIR',   DEZ_PATH . DS . 'errors' );

	define( 'APP_PATH',     preg_replace( '/\\/+/', DS, dirname( $_SERVER['SCRIPT_FILENAME'] ) ) );
	define( 'CACHE_DIR',    APP_PATH . DS . 'cache' );
	define( 'LOGS_DIR',     APP_PATH . DS . 'logs' );

	
	define( 'DEZ_VERVION',  '1.0-beta' );
	define( 'DEZ_NAME',     'dez-framework' );
	define( 'DEZ_CODENAME', 'Tits' );
	define( 'DEZ_AUTHOR',   'Ivan Gontarenko' );