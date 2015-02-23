<?php

    namespace Dez\ORM\Invoker;

    trait Invoker {

        public function __call( $name, array $params = [] ) {
            if( in_array( $name, $this->getMethodList() ) ) {
                return call_user_func_array( [ $this, $name ], $params );
            } else {
                throw new \BadMethodCallException( 'Call undefined method from Invoker' );
            }
        }

        abstract protected function init();

        abstract protected function getMethodList();

    }