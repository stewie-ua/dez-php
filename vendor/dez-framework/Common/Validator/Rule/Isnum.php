<?php

    namespace Dez\Common\Validator\Rule;

    class Isnum extends RuleAbstract {
        public function check(){
            return is_numeric( $this->_data );
        }
    }