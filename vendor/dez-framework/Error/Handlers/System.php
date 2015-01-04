<?php

    namespace Dez\Error\Handlers;

    use \Dez\Error\ErrorHandler,
        \Dez\Core;
    use Dez\View\View;

    class System extends ErrorHandler {

        static private
            $stack          = [],
            $emptyStack     = [ 'critical'=> [], 'warning' => [], 'notify' => [] ];

        public function init(){
            parent::init( func_get_args()[0] );
            $session    = Core\Session::instance();
            if( ! $session->has( 'error_messages' ) ) {
                $session->set( 'error_messages', static::$emptyStack );
            }
            static::$stack = & $session->get( 'error_messages' );
        }

        protected function _message( $message = null ){
            switch( $this->priority ) {
                case ErrorHandler::NOTIFY: {
                    self::$stack['notify'][] = $message;
                    break;
                }
                case ErrorHandler::WARNING: {
                    self::$stack['warning'][] = $message;
                    break;
                }
                case ErrorHandler::CRITICAL: {
                    self::$stack['critical'][] = $message;
                    break;
                }
                default: { break; }
            }
        }

        static public function getStack() {
            $stack = self::$stack;
            static::$stack = static::$emptyStack;
            return $stack;
        }

        static public function emptyStack() {
            static::$stack = static::$emptyStack;
        }

        public function render(){
            $view   = clone View::instance();
            $view->setPath( __DIR__ .'/..' );
            $output = $view->render( 'error-tmpl', [ 'stack' => self::$stack ] );
            static::$stack = static::$emptyStack;
            return $output;
        }
    }

