<?php

    namespace Sy\Common\Validator\Rule;

    use Sy\Common\Validator;

    abstract class RuleAbstract {

        protected
            $_data      = null,
            $_rule      = null;

        public function __construct( Validator\Rule $rule ) {
            $this->_rule = $rule;
        }

        public function attachData( & $data ){
            $this->_data = & $data;
        }

        abstract public function check();

    }