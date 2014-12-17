<?php

    namespace Dez\Common\Validator;

    class Rule{

        private
            $_handler 	= null,
            $_data 		= array();

        public
            $key,
            $type,
            $rule,
            $errorMessage;

        public function __construct( $key = null, $type = null, $rule = null, $errorMessage = null ){

            $handlerClass = __NAMESPACE__ . '\\Rule\\'. ucfirst( strtolower( $type ) );

            $this->key 				= $key;
            $this->type 			= $type;
            $this->rule 			= $rule;
            $this->errorMessage 	= $errorMessage;

            if( class_exists( $handlerClass ) ){
                $this->_handler = new $handlerClass( $this );
            }
        }

        public function attachData( & $data ){
            $this->_data = & $data;
        }

        public function checkRule(){
            if( ! $this->_handler ){
                return new RuleError( $this->key, 'Handler not found for type ['. $this->type .']' );
            }else{
                $this->_handler->attachData( $this->_data[$this->key] );
            }

            if( $this->_handler->check() ) {
                return true;
            } else {
                return new RuleError( $this->key, $this->errorMessage );
            }

        }

    }