<?php

    namespace Sy\Common\Validator;

    class RuleError{
        public 	$key,
            $message;
        public function __construct( $key, $message ){
            $this->key 		= $key;
            $this->message 	= $message;
        }
        public function getError(){
            return $this;
        }
    }