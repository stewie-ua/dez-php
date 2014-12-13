<?php

    namespace Sy\Common\Validator\Rule;

    class Callback extends RuleAbstract {

        public function check() {
            return call_user_func_array( $this->_rule->rule, array( $this->_data ) );
        }

    }