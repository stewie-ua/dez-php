<?php

    namespace Sy\Common\Validator\Rule;

    class Notempty extends RuleAbstract {
        public function check(){
            return ! empty( $this->_data );
        }
    }