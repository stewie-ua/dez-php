<?php

    namespace Dez\Common\Validator\Rule;

    class Len extends RuleAbstract {
        public function check(){
            list( $lenMin, $lenMax )
                = explode( '-', $this->_rule->rule );
            return preg_match( '/^.{'. $lenMin .','. $lenMax .'}$/sui', $this->_data );
        }
    }