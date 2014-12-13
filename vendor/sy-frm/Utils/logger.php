<?php

	namespace Sy;
	
	class Logger{
		
		static function add( $message ){
			$log_file = SY_ROOT . DS . 'logs' . DS . date( 'd-m-Y' ) . '.log';			
			$message 	= '['. date( 'd-m-Y H:i:s' ) .' Host: '. $_SERVER['REMOTE_ADDR'] .'] - '. $message . "\n";
			$content 	= @ file_get_contents( $log_file );
			file_put_contents( $log_file, $content . $message );			
		}
		
	}