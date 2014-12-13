<?php

    namespace Sy\Common\Validator\Rule;

    class Inset extends RuleAbstract {
        public function check(){
            $values = explode( ',', $this->_rule->rule );
            return in_array( $this->_data, $values );
        }
    }