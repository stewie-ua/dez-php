<?php

    namespace Dez\Hook;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait,
        Dez\Error\Exception\RuntimeError;

    class Hook extends Object {

        use SingletonTrait;

        protected
            $stack  = [];

        protected function init() {}

        public function dispatch( $eventName, $context ) {
            if( isset( $this->stack[$eventName] ) ) {
                foreach( $this->stack[$eventName] as $callback ) {
                    if( $callback instanceof \Closure ) {
                        $callback( $context );
                    } else if( is_array( $callback ) && method_exists( $callback[0], $callback[1] ) ) {
                        call_user_func_array( $callback, [ $context ] );
                    } else {
                        throw new RuntimeError( __METHOD__ .' bad callback' );
                    }
                }
            }
        }

        public function attach( $eventName, $hook ) {
            $this->stack[$eventName][] = $hook;
        }

    }