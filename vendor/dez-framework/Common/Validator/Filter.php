<?php

    namespace Dez\Common\Validator;

    class Filter{

        private $_data 			= array(),
            $_needFields 	= array();

        public function __construct(){
            $this->_needFields = func_get_args();
        }

        public function attachData( & $data ){
            $this->_data = & $data;
        }

        public function process(){
            if( ! empty( $this->_needFields ) ){
                $newData = array();
                foreach( $this->_needFields as $field ){
                    if( isset( $this->_data[$field] ) ){
                        $newData[$field] = $this->_data[$field];
                    }
                }
                $this->_data = $newData;
            }
        }
    }