<?php

    namespace Dez\ORM;

    class Autoloader {

        public function __construct() {
            spl_autoload_register( array( $this, 'loader' ) );
        }

        private function loader( $class ) {
            $parts  = array_slice( explode( '\\', $class ), 2 );

            if( strpos( $class, 'Dez\\ORM' ) === false ) {
                return true;
            }

            $file   = __DIR__ . DIRECTORY_SEPARATOR . implode( DIRECTORY_SEPARATOR, $parts ) . '.php';

            if( ! file_exists( $file ) ) {
                die( __CLASS__ . ': cant find file ('. $file .')' );
            } else {
                require_once $file;
            }
        }

    }