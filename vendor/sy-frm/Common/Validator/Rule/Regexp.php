<?php

    namespace Sy\Common\Validator\Rule;

    class Regex extends RuleAbstract {
        public function check(){
            return preg_match( $this->_rule->rule, $this->_data );
        }
    }