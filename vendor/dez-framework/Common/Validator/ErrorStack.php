<?php

    namespace Dez\Common\Validator;

    class ErrorStack{

        private $_errors = array();

        public function addStack( $key,  $message ){
            if( ! isset( $this->_errors[$key] ) ){
                $this->_errors[$key] = array();
            }
            $this->_errors[$key][] = $message;
        }

        public function isError(){
            return ( 0 < sizeOf( $this->_errors ) );
        }

        public function getMessage( $key ) {
            if( isset( $this->_errors[$key] ) && ! empty( $this->_errors[$key] ) ){
                return join( "\n", $this->_errors[$key] );
            }else{
                return null;
            }
        }

        public function getErrors() {
            $errors = array();
            foreach( array_keys( $this->_errors ) as $key ) {
                $errors[] = $this->getMessage( $key );
            }
            return $errors;
        }

    }