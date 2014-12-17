<?php

    namespace Dez\Error\Handlers;

    use \Dez\Error\ErrorHandler;

    class FileLog extends ErrorHandler {
        protected function _message( $message = null ){
            $logFile = LOGS_DIR . DS . date( 'Ymd' ) . '_' . $this->priority . '.log';
            $message = '['. date( 'Y-m-d H:i:s' ) .' '. getRealIP() .']' . PHP_EOL . $message;
            file_put_contents( $logFile, $message . str_repeat( PHP_EOL, 2 ), FILE_APPEND | LOCK_EX );
        }
    }