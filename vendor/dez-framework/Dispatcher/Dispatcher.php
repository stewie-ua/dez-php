<?php

    namespace Dez\Dispatcher;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait,
        Dez\Error\Exception\RuntimeError;

    class DispatcherException extends RuntimeError {}

    class Dispatcher extends Object {

        use SingletonTrait;

        protected
            $stack  = [];

        protected function init() {}

        public function loadHooks() {
            $hooks  = glob( \Dez::getAlias( '@app/hooks/*' ) );
            if( count( $hooks ) > 0 ) {
                foreach( $hooks as $hook ) {
                    $hookName       = str_replace( '.php', '', basename( $hook ) );
                    include_once $hook;
                    $hookClass      = "\\Hook\\{$hookName}";
                    ( new $hookClass )->registerListeners();
                }
            }
            return $this;
        }

        public function dispatch( $eventName, $context ) {
            if( isset( $this->stack[$eventName] ) ) {
                foreach( $this->stack[$eventName] as $callback ) {
                    if( is_callable( $callback ) ) {
                        call_user_func_array( $callback, ! is_array( $context ) ? [ $context ] : $context );
                    } else {
                        throw new DispatcherException( __METHOD__ .' bad callback for event ['. $eventName .']' );
                    }
                }
            }
        }

        public function listen( $eventName, $callback ) {
            $this->stack[$eventName][] = $callback;
        }

    }