<?php

    namespace Dez\Common\Validator\Rule;

    class Email extends RuleAbstract {
        public function check(){
            return preg_match( '/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/', $this->_data );
        }
    }