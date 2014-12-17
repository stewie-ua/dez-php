<?php

	namespace Dez\Common;

    class Validator {
		
		private $_rules 		= array(),
				$_filter 		= null,
				$_data			= array(),
				$_errorStack	= null;
		
		public function __construct(){
			$this->_errorStack = new Validator\ErrorStack;
		}
		
		public function addRule( Validator\Rule $rule ){
			$this->_rules[] = $rule;
			$rule->attachData( $this->_data );			
			return $this;
		}

		public function addFilter( Validator\Filter $filter ){
			$this->_filter = $filter;
			$filter->attachData( $this->_data );
		}

		public function attachData( array $array = array() ){
			$this->_data = $array;
		}

		public function reset(){
			$this->_rules 	= array();
			$this->_filter	= null;
			$this->_data	= array();
		}
		
		public function run(){
			
			foreach( $this->_rules as $rule ){
				$result = $rule->checkRule();
				if( $result instanceOf Validator\RuleError ){
					$error = $result->getError();
					$this->_errorStack->addStack( $error->key, $error->message );
				}			
			}
			
			if( $this->_filter instanceOf Validator\Filter ){
				$this->_filter->process();
			}
			
		}
		
		public function getResult(){
			return $this->_data;
		}
		
		public function isError(){
			return $this->_errorStack->isError();
		}

        public function getErrors() {
            return $this->_errorStack->getErrors();
        }
		
    }